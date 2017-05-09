<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Request as DiplomaRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DiplomaRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ajax');
    }

    public function store(Request $request, $id)
    {
        // TODO: Создать отдельный Request с валидацией
        $this->validate($request, [
            'message' => 'max:255',
        ]);
        $diplomaRequest = Student::find($id)->requests()->save(
            new DiplomaRequest([
                'task_id' => request('id'),
                'status' => 0,
                'message' =>
                    request('message') ? request('message') : '',
                'created_at' => Carbon::now()->format('Y-d-m'),
            ])
        )->toArray();
        return Response::json([
            'request' => $diplomaRequest,
        ]);
    }
}
