<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class GroupController extends Controller
{
    public function __contstruct()
    {
        $this->middleware('auth');
        $this->middleware('ajax');
    }
    public function index()
    {
        $groups = Group::all()->toArray();
        return Response::json($groups);
    }
}
