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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $diplomas = Task::latest()->where('type', 2)
          ->where('professor_id', Auth::user()->professor->id);
        if ($group_id = request('group')) {
            $diplomas->where('group_id', $group_id);
        }
        $diplomas = $diplomas->get();
        return view('diplomas.index')->with([
            'professor' => Auth::user()->professor,
            'groups' => Group::all(),
            'diplomas' => $diplomas,
        ]);
    }

    public function data(Request $request)
    {
        $diplomas = Task::latest()->where('type', 2)
            ->where('professor_id', Auth::user()->professor->id)
            ->where('group_id', request('group_id'))
            ->get()->toArray();
        foreach ($diplomas as &$diploma) {
            $diploma['requests'] = count(Task::find($diploma['id'])->requests);
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
        ]));
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
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }
}
