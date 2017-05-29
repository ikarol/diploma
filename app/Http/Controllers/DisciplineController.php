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

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:groups'
        ]);
        $group = Discipline::create([
            'name' => $request['name'],
        ]);
        return Response::json($group);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $group = Discipline::find($request['id']);
        $group->name = $request['name'];
        $group->save();
        return Response::json($group);
    }
}
