<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Group;
use App\Models\Student;
use App\Models\Request as CourseProjectRequest;
use App\Models\Professor;
use App\Models\Task;
use App\Models\Job;
use App\Models\Discipline;
use App\Models\TasksDiscipline;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;


class CourseProjectController extends Controller
{
    public $userType = 0;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ajax')->except([
            'index',
            'show',
        ]);
        $this->middleware('professor')->only([
            'store',
            'update',
            'destroy',
            'courseProjectListProfessor',
        ]);
        $this->middleware('student')->only([
            'courseProjectListStudent',
        ]);
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
        return view('course_projects.index')->with(['userType' => $this->userType]);
    }

    public function courseProjectListProfessor(Request $request)
    {
        $courseProjects = Task::where('type', 1)
            ->where([
                ['professor_id', Auth::user()->professor->id],
                ['group_id', request('group_id')]
            ])->get()->toArray();
        foreach ($courseProjects as &$courseProject) {
            $user = User::whereHas('student', function($query) use ($courseProject) {
                $query->where([
                    ['group_id', request('group_id')],
                ])->whereHas((new CourseProjectRequest)->getTable(), function ($queryRequest) use ($courseProject) {
                    $queryRequest->where([
                        ['status', 1],
                    ])->whereHas('task', function($queryTask) use ($courseProject) {
                        $queryTask->where([
                            ['task_id', $courseProject['id']],
                            ['type', 1],
                        ]);
                    });
                });
            })->first(); // fetch first user
            $courseProject['requests'] = [
                'accepted' => count(Task::find($courseProject['id'])->requests()->where([
                    ['status', 1],
                ])->get()),
                'pending' => count(Task::find($courseProject['id'])->requests()->where([
                    ['status', 0],
                ])->get()),
                'declined' => count(Task::find($courseProject['id'])->requests()->where([
                    ['status', 2],
                ])->get()),
            ];
            $courseProject['student'] = count($user) ? $user->getFullName() : null;
            // $request ?
            //     $request->student()->first()->user->surname
            //         . ' ' . $request->student()->first()->user->name : null;
            $courseProject['created_at'] = Carbon::parse($courseProject['created_at'])->format('d.m.Y');
            if (is_null($courseProject['updated_at']) === false) {
                $courseProject['updated_at'] = Carbon::parse($courseProject['updated_at'])->format('d.m.Y');
            }
            $courseProject['disciplines'] = Discipline::whereHas('tasks', function ($queryRequest) use($courseProject) {
                $queryRequest->where([
                    ['task_id', $courseProject['id']],
                ]);
            })->get();
        }
        return Response::json([
            'course_projects' => $courseProjects,
        ]);
    }

    public function courseProjectListStudent(Request $request)
    {
        $courseProjects = Task::where([
            ['type', 1],
            ['group_id', request('group_id')]
        ])->whereDoesntHave((new CourseProjectRequest)->getTable(), function ($query) {
            $query->where([
                ['student_id', '<>', Auth::user()->student->id],
                ['status', 1],
            ]);
        })->get()->toArray();
        foreach ($courseProjects as &$courseProject) {
            $courseProjectRequest = CourseProjectRequest::where([
                ['task_id', $courseProject['id']],
                ['student_id', Auth::user()->student->id],
            ])->first();
            $courseProject['professor'] = Task::find($courseProject['id'])
                ->professor->user->surname . ' ' . Task::find($courseProject['id'])->professor->user->name;
            $courseProject['status'] = $courseProjectRequest ? $courseProjectRequest->status : null;
            $courseProject['created_at'] = Carbon::parse($courseProject['created_at'])->format('d.m.Y');
            $courseProject['disciplines'] = Discipline::whereHas('tasks', function ($queryRequest) use ($courseProject) {
                $queryRequest->where([
                    ['task_id', $courseProject['id']],
                ]);
            })->get();
        }
        return Response::json([
            'course_projects' => $courseProjects,
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
            'description' => 'required',
            'disciplines' => 'required',
        ]);
        $courseProject = Professor::find(Auth::user()->professor->id)->tasks()->save(new Task([
            'type' => 1,
            'title' => request('title'),
            'description' => request('description'),
            'technologies' =>
                request('technologies') ? request('technologies') : '',
            'group_id' => request('group_id'),
            'created_at' => Carbon::now()->format('Y-d-m'),
        ]))->toArray();

        foreach ($request['disciplines'] as $discipline) {
            TasksDiscipline::create([
                'discipline_id' => $discipline['id'],
                'task_id' => $courseProject['id'],
            ]);
            // Discipline::find($discipline['id'])->save(new TasksDiscipline([
            //
            // ]));
        }
        $user = User::whereHas('student', function($query) use ($courseProject) {
            $query->where([
                ['group_id', request('group_id')],
            ])->whereHas((new CourseProjectRequest)->getTable(), function ($queryRequest) use ($courseProject) {
                $queryRequest->where([
                    ['status', 1],
                ])->whereHas('task', function($queryTask) use ($courseProject) {
                    $queryTask->where([
                        ['task_id', $courseProject['id']],
                        ['type', 1],
                    ]);
                });
            });
        })->first(); // fetch first user
        $courseProject['requests'] = [
            'accepted' => count(Task::find($courseProject['id'])->requests()->where([
                ['status', 1],
            ])->get()),
            'pending' => count(Task::find($courseProject['id'])->requests()->where([
                ['status', 0],
            ])->get()),
            'declined' => count(Task::find($courseProject['id'])->requests()->where([
                ['status', 2],
            ])->get()),
        ];
        $courseProject['student'] = count($user) ? $user->getFullName() : null;
        // $request ?
        //     $request->student()->first()->user->surname
        //         . ' ' . $request->student()->first()->user->name : null;
        $courseProject['created_at'] = Carbon::createFromFormat('Y-d-m', $courseProject['created_at'])->format('d.m.Y');

        $courseProject['updated_at'] = null;

        $courseProject['disciplines'] = Discipline::whereHas('tasks', function ($queryRequest) use($courseProject) {
            $queryRequest->where([
                ['task_id', $courseProject['id']],
            ]);
        })->get();
        return Response::json($courseProject);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // if (request()->ajax()) {
        //     return Response::json([
        //         'diploma' => Task::find($id),
        //     ]);
        // }
        $info = Task::find($id);
        $user = User::whereHas('student', function($query) use ($info) {
            $query->where([
                ['group_id', $info->group_id],
            ])->whereHas((new CourseProjectRequest)->getTable(), function ($queryRequest) use ($info) {
                $queryRequest->where([
                    ['status', 1],
                ])->whereHas('task', function($queryTask) use ($info) {
                    $queryTask->where([
                        ['task_id', $info->id],
                        ['type', 1],
                    ]);
                });
            });
        })->first();
        $info->student = count($user) ? $user->getFullName() : null;
        $info->disciplines = Discipline::whereHas('tasks', function ($queryRequest) use ($info) {
            $queryRequest->where([
                ['task_id', $info['id']],
            ]);
        })->get();
        return view('course_projects.show')->with([
            'userType' => $this->userType,
            'id' => $id,
            'info' => $info,
        ]);
    }

    // public function info($id)
    // {
    //
    // }
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
            'description' => 'required',
            'disciplines' => 'required',
        ]);
        $updatedProject = Task::find(request('id'));
        $updatedProject->title = request('title');
        $updatedProject->description = request('description');
        $updatedProject->technologies = request('technologies');
        $updatedProject->updated_at = Carbon::now()->format('Y-d-m');
        $updatedProject->save();
        $updatedProject->toArray();

        $existingDisciplines = [];
        foreach ($request['disciplines'] as $discipline) {
            $existingDisciplines[] = $discipline['id'];
        }
        if ($removedDisciplines = TasksDiscipline::where([
            ['task_id', $updatedProject['id']],
        ])->whereNotIn('discipline_id', $existingDisciplines)) {
            $removedDisciplines->delete();
        }

        foreach ($request['disciplines'] as $discipline) {
            if (TasksDiscipline::where([
                ['discipline_id', $discipline['id']],
                ['task_id', $updatedProject['id']],
            ])->get()->isEmpty()
            ) {
                TasksDiscipline::create([
                    'discipline_id' => $discipline['id'],
                    'task_id' => $updatedProject['id'],
                ]);
            }

            // Discipline::find($discipline['id'])->save(new TasksDiscipline([
            //
            // ]));
        }

        $user = User::whereHas('student', function($query) use ($updatedProject) {
            $query->where([
                ['group_id', request('group_id')],
            ])->whereHas((new CourseProjectRequest)->getTable(), function ($queryRequest) use ($updatedProject) {
                $queryRequest->where([
                    ['status', 1],
                ])->whereHas('task', function($queryTask) use ($updatedProject) {
                    $queryTask->where([
                        ['task_id', $updatedProject['id']],
                        ['type', 1],
                    ]);
                });
            });
        })->first(); // fetch first user
        $updatedProject['requests'] = [
            'accepted' => count(Task::find($updatedProject['id'])->requests()->where([
                ['status', 1],
            ])->get()),
            'pending' => count(Task::find($updatedProject['id'])->requests()->where([
                ['status', 0],
            ])->get()),
            'declined' => count(Task::find($updatedProject['id'])->requests()->where([
                ['status', 2],
            ])->get()),
        ];

        $updatedProject['disciplines'] = Discipline::whereHas('tasks', function ($queryRequest) use ($updatedProject) {
            $queryRequest->where([
                ['task_id', $updatedProject['id']],
            ]);
        })->get();

        $updatedProject['student'] = count($user) ? $user->getFullName() : null;
        $updatedProject['created_at'] = Carbon::parse($updatedProject['created_at'])->format('d.m.Y');
        $updatedProject['updated_at'] = Carbon::createFromFormat('Y-d-m', $updatedProject['updated_at'])->format('d.m.Y');
        return Response::json($updatedProject);
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
         * Cascade deleting disciplines if they exist
         */
        if ($discipline = TasksDiscipline::where('task_id', $id)) {
            $discipline->delete();
        }

        /**
         * Cascade deleting requests if they exist
         */
        if ($requests = CourseProjectRequest::where('task_id', $id)) {
            $requests->delete();
        }

        /**
         * Cascade deleting jobs if they exist
         */
        if ($jobs = Job::where('task_id', $id)) {
            $jobs->delete();
        }
        Task::destroy($id);
        return Response::json('success');
    }
}
