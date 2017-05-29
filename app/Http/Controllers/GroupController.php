<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Group;
use App\Models\Request as DiplomaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public $userType = 0;

    public function __contstruct()
    {
        $this->middleware('auth')->except([
            'store',
            'update',
        ]);
        $this->middleware('ajax');
        $this->middleware('professor')->only('professor_groups');
        $this->middleware('student')->only('student_groups');
    }

    public function professor_groups()
    {
        $groups = Group::all()->toArray();
        return Response::json($groups);
    }

    public function student_groups()
    {
        $groups = Group::where('id', Auth::user()->student->group_id)->get()->toArray();
        return Response::json($groups);
    }

    public function professor_request_groups()
    {
        $groups = Group::whereHas((new Task)->getTable(), function ($query) {
            $query->where([
                ['professor_id', Auth::user()->professor->id],
            ])->has((new DiplomaRequest)->getTable());
        })->get()->toArray();
        return Response::json($groups);
    }

    public function student_request_groups()
    {
        $groups = Group::where('id', Auth::user()->student->group_id)->get()->toArray();
        return Response::json($groups);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:groups'
        ]);
        $group = Group::create([
            'name' => $request['name'],
        ]);
        return Response::json($group);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $group = Group::find($request['id']);
        $group->name = $request['name'];
        $group->save();
        return Response::json($group);
    }

}
