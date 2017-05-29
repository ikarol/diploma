<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Discipline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function groups()
    {
        if (request()->ajax()) {
            return Response::json(Group::all());
        }

        return view('admin.groups');
    }

    public function disciplines()
    {
        if (request()->ajax()) {
            return Response::json(Discipline::all());
        }

        return view('admin.disciplines');
    }
}
