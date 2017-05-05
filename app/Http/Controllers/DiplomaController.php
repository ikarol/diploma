<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Professor;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;


class DiplomaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ajax')->except('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('diplomas.index');
    }

    public function data(Request $request)
    {
        $diplomas = Task::where('type', 2)
            ->where('professor_id', Auth::user()->professor->id)
            ->where('group_id', request('group_id'))
            ->get()->toArray();
        foreach ($diplomas as &$diploma) {
            $diploma['requests'] = count(Task::find($diploma['id'])->requests);
            $diploma['created_at'] = Carbon::parse($diploma['created_at'])->format('d.m.Y');
        }
        return Response::json($diplomas);
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
        $diploma['requests'] = count(Task::find($diploma['id'])->requests);
        // TODO: Дата отображается неверно
        $diploma['created_at'] = Carbon::parse($diploma['created_at'])->format('m.d.Y');
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
        $updatedTask['created_at'] = Carbon::parse($updatedTask['created_at'])->format('d.m.Y');
        $updatedTask['requests'] = count(Task::find($updatedTask['id'])->requests);
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
        Task::destroy($id);
        return Response::json('success');
    }
}
