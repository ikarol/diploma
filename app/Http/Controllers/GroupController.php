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
        $this->middleware('auth');
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

}
