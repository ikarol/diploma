<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocalizationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocalizationController extends Controller
{

    public function index(LocalizationRequest $request)
    {
        Session::put('locale', $request['locale']);

        return redirect()->back();
    }
}
