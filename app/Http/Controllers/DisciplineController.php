<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DisciplineController extends Controller
{
    public function list()
    {
        $disciplines = Discipline::all();

        return Response::json([
            'disciplines' => $disciplines,
        ]);
    }
}
