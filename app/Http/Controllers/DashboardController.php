<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('home');
    }

    public function home()
    {
        return view('home');
    }

    public function index()
    {
        $userType = 0;
        if (Auth::user()->professor) {
            $userType = 1;
        } elseif (Auth::user()->student) {
            $userType = 2;
        }
        return view('dashboard')->with(['userType' => $userType]);
    }
}
