<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Group;
use App\Models\Student;
use App\Models\Request as DiplomaRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function professor_requests_list(Request $request)
    {
        $diplomaRequests = DiplomaRequest::whereHas('task', function ($query) {
            $query->where([
                ['professor_id', Auth::user()->professor->id],
                ['type', 2],
                ['group_id', request('group_id')],
            ]);
        })->get()->toArray();
        foreach ($diplomaRequests as &$request) {
            $request['student'] = Student::find($request['student_id'])
                ->user->surname . ' ' . Student::find($request['student_id'])->user->name;
            $request['group'] = Group::find(Task::find($request['task_id'])->group_id)->name;
            $request['created_at'] = Carbon::parse($request['created_at'])->format('d.m.Y');
            $request['task_title'] = Task::find($request['task_id'])->title;
            $request['diploma_id'] = Task::find($request['task_id'])->id;
        }
        return Response::json([
            'requests' => $diplomaRequests,
        ]);
    }

    public function accept($student, $request)
    {
        $diplomaRequest = DiplomaRequest::where([
            ['student_id', $student],
            ['task_id', $request]
        ])->update([
            'status' => 1,
            'started_at' => Carbon::now()->format('Y-d-m'),
        ]);
        // $diplomaRequest->save();
        return Response::json([
            'request' => $diplomaRequest,
        ]);
    }

    public function decline($student, $request)
    {
        $diplomaRequest = DiplomaRequest::where([
            ['student_id', $student],
            ['task_id', $request]
        ])->update([
            'status' => 2,
        ]);
        return Response::json([
            'request' => $diplomaRequest,
        ]);
    }
}
