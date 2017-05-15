<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Group;
use App\Models\Student;
use App\Models\Request as DiplomaRequest;
use App\Models\Professor;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;


class DiplomaController extends Controller
{
    public $userType = 0;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ajax')->except('index');
        $this->middleware('professor')->only([
            'store',
            'update',
            'destroy',
            'diplomaListProfessor',
        ]);
        $this->middleware('student')->only('diplomasListStudent');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->professor) {
                $this->userType = 1;
            } elseif (Auth::user()->student) {
                $this->userType = 2;
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('diplomas.index')->with(['userType' => $this->userType]);
    }

    public function diplomaListProfessor(Request $request)
    {
        $diplomas = Task::where('type', 2)
            ->where([
                ['professor_id', Auth::user()->professor->id],
                ['group_id', request('group_id')]
            ])->get()->toArray();
        foreach ($diplomas as &$diploma) {
            $user = User::whereHas('student', function($query) use ($diploma) {
                $query->where([
                    ['group_id', request('group_id')],
                ])->whereHas((new DiplomaRequest)->getTable(), function ($queryRequest) use ($diploma) {
                    $queryRequest->where([
                        ['status', 1],
                    ])->whereHas('task', function($queryTask) use ($diploma) {
                        $queryTask->where([
                            ['task_id', $diploma['id']],
                            ['type', 2],
                        ]);
                    });
                });
            })->first(); // fetch first user
            $diploma['requests'] = [
                'accepted' => count(Task::find($diploma['id'])->requests()->where([
                    ['status', 1],
                ])->get()),
                'pending' => count(Task::find($diploma['id'])->requests()->where([
                    ['status', 0],
                ])->get()),
                'declined' => count(Task::find($diploma['id'])->requests()->where([
                    ['status', 2],
                ])->get()),
            ];
            $diploma['student'] = count($user) ? $user->getFullName() : null;
            // $request ?
            //     $request->student()->first()->user->surname
            //         . ' ' . $request->student()->first()->user->name : null;
            $diploma['created_at'] = Carbon::parse($diploma['created_at'])->format('d.m.Y');
            if (is_null($diploma['updated_at']) === false) {
                $diploma['updated_at'] = Carbon::parse($diploma['updated_at'])->format('d.m.Y');
            }
        }
        return Response::json([
            'diplomas' => $diplomas,
        ]);
    }

    public function diplomasListStudent(Request $request)
    {
        $diplomas = Task::where([
            ['type', 2],
            ['group_id', request('group_id')]
        ])->whereDoesntHave((new DiplomaRequest)->getTable(), function ($query) {
            $query->where([
                ['student_id', Auth::user()->student->id],
            ]);
        })->get()->toArray();
        foreach ($diplomas as &$diploma) {
            $diploma['professor'] = Task::find($diploma['id'])
                ->professor->user->surname . ' ' . Task::find($diploma['id'])->professor->user->name;
            $diploma['created_at'] = Carbon::parse($diploma['created_at'])->format('d.m.Y');
        }
        return Response::json([
            'diplomas' => $diplomas,
            'student_id' => Auth::user()->student->id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // TODO: Создать отдельный Request с валидацией
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required'
        ]);
        $diploma = Professor::find(Auth::user()->professor->id)->tasks()->save(new Task([
            'type' => 2,
            'title' => request('title'),
            'description' => request('description'),
            'technologies' =>
                request('technologies') ? request('technologies') : '',
            'group_id' => request('group_id'),
            'created_at' => Carbon::now()->format('Y-d-m'),
        ]))->toArray();
        $user = User::whereHas('student', function($query) use ($diploma) {
            $query->where([
                ['group_id', request('group_id')],
            ])->whereHas((new DiplomaRequest)->getTable(), function ($queryRequest) use ($diploma) {
                $queryRequest->where([
                    ['status', 1],
                ])->whereHas('task', function($queryTask) use ($diploma) {
                    $queryTask->where([
                        ['task_id', $diploma['id']],
                        ['type', 2],
                    ]);
                });
            });
        })->first(); // fetch first user
        $diploma['requests'] = [
            'accepted' => count(Task::find($diploma['id'])->requests()->where([
                ['status', 1],
            ])->get()),
            'pending' => count(Task::find($diploma['id'])->requests()->where([
                ['status', 0],
            ])->get()),
            'declined' => count(Task::find($diploma['id'])->requests()->where([
                ['status', 2],
            ])->get()),
        ];
        $diploma['student'] = count($user) ? $user->getFullName() : null;
        // TODO: Дата отображается неверно
        // Не работает
        $diploma['created_at'] = Carbon::createFromFormat('Y-d-m', $diploma['created_at'])->format('d.m.Y');
        $diploma['updated_at'] = null;
        return Response::json($diploma);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // TODO: Создать отдельный Request с валидацией
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required'
        ]);
        $updatedTask = Task::find(request('id'));
        $updatedTask->title = request('title');
        $updatedTask->description = request('description');
        $updatedTask->technologies = request('technologies');
        $updatedTask->updated_at = Carbon::now()->format('Y-d-m');
        $updatedTask->save();
        $updatedTask->toArray();
        $user = User::whereHas('student', function($query) use ($updatedTask) {
            $query->where([
                ['group_id', request('group_id')],
            ])->whereHas((new DiplomaRequest)->getTable(), function ($queryRequest) use ($updatedTask) {
                $queryRequest->where([
                    ['status', 1],
                ])->whereHas('task', function($queryTask) use ($updatedTask) {
                    $queryTask->where([
                        ['task_id', $updatedTask['id']],
                        ['type', 2],
                    ]);
                });
            });
        })->first(); // fetch first user
        $updatedTask['requests'] = [
            'accepted' => count(Task::find($updatedTask['id'])->requests()->where([
                ['status', 1],
            ])->get()),
            'pending' => count(Task::find($updatedTask['id'])->requests()->where([
                ['status', 0],
            ])->get()),
            'declined' => count(Task::find($updatedTask['id'])->requests()->where([
                ['status', 2],
            ])->get()),
        ];
        $updatedTask['student'] = count($user) ? $user->getFullName() : null;
        $updatedTask['created_at'] = Carbon::parse($updatedTask['created_at'])->format('d.m.Y');
        $updatedTask['updated_at'] = Carbon::createFromFormat('Y-d-m', $updatedTask['updated_at'])->format('d.m.Y');
        return Response::json($updatedTask);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /**
         * Cascade deleting requests if they exist
         */
        if ($requests = DiplomaRequest::where('task_id', $id)) {
            $requests->delete();
        }
        Task::destroy($id);
        return Response::json('success');
    }
}
