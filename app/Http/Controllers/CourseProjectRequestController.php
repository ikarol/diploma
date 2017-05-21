<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Group;
use App\Models\Student;
use App\Models\Request as CourseProjectRequest;
use App\Models\Discipline;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CourseProjectRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ajax');
        $this->middleware('professor')->only([
            'professor_requests_list',
            'accept',
            'decline',
        ]);
    }

    public function store(Request $request, $id)
    {
        // TODO: Создать отдельный Request с валидацией
        $this->validate($request, [
            'message' => 'max:255',
        ]);
        $courseProjectRequest = Student::find($id)->requests()->save(
            new CourseProjectRequest([
                'task_id' => request('id'),
                'status' => 0,
                'message' =>
                    request('message') ? request('message') : '',
                'created_at' => Carbon::now()->format('Y-d-m'),
            ])
        )->toArray();
        $courseProjectStatus = $courseProjectRequest['status'];
        return Response::json([
            'courseProjectStatus' => $courseProjectStatus,
        ]);
    }

    public function professor_requests_list(Request $request)
    {
        if ($request->has('status_type') && isset($request['status_type'])) {
            $status_type = '';
            switch ($request['status_type']) {
                case '0':
                    $status_type = 0;
                    break;
                case '1':
                    $status_type = 1;
                    break;
                case '2':
                    $status_type = 2;
                    break;
                case '3':
                    $status_type = '';
                    break;
            }
            if ($status_type !== '') {
                $courseProjectRequests = CourseProjectRequest::whereHas('task', function ($query) {
                    $query->where([
                        ['professor_id', Auth::user()->professor->id],
                        ['type', 1],
                        ['group_id', request('group_id')],
                    ]);
                })->where([
                    ['status', $status_type],
                ])->get()->toArray();
            } else {
                $courseProjectRequests = CourseProjectRequest::whereHas('task', function ($query) {
                    $query->where([
                        ['professor_id', Auth::user()->professor->id],
                        ['type', 1],
                        ['group_id', request('group_id')],
                    ]);
                })->get()->toArray();
            }
        } else {
            $courseProjectRequests = CourseProjectRequest::whereHas('task', function ($query) {
                $query->where([
                    ['professor_id', Auth::user()->professor->id],
                    ['type', 1],
                    ['group_id', request('group_id')],
                ]);
            })->get()->toArray();
        }

        foreach ($courseProjectRequests as &$courseProjectRequest) {
            $courseProjectRequest['student'] = Student::find($courseProjectRequest['student_id'])
                ->user->surname . ' ' . Student::find($courseProjectRequest['student_id'])->user->name;
            $courseProjectRequest['group'] = Group::find(Task::find($courseProjectRequest['task_id'])->group_id)->name;
            $courseProjectRequest['created_at'] = Carbon::parse($courseProjectRequest['created_at'])->format('d.m.Y');
            $courseProjectRequest['task_title'] = Task::find($courseProjectRequest['task_id'])->title;
            $courseProjectRequest['courseProject_id'] = Task::find($courseProjectRequest['task_id'])->id;
            $courseProjectRequest['disciplines'] = Discipline::whereHas('tasks', function ($queryRequest) use ($courseProjectRequest) {
                $queryRequest->where([
                    ['task_id', $courseProjectRequest['task_id']],
                ]);
            })->get();
        }
        return Response::json([
            'requests' => $courseProjectRequests,
        ]);
    }

    public function accept($student, $request)
    {
        $courseProjectRequest = CourseProjectRequest::where([
            ['student_id', $student],
            ['task_id', $request]
        ])->update([
            'status' => 1,
            'started_at' => Carbon::now()->format('Y-d-m'),
        ]);
        // $courseProjectRequest->save();
        return Response::json([
            'request' => $courseProjectRequest,
        ]);
    }

    public function decline($student, $request)
    {
        $courseProjectRequest = CourseProjectRequest::where([
            ['student_id', $student],
            ['task_id', $request]
        ])->update([
            'status' => 2,
        ]);
        return Response::json([
            'request' => $courseProjectRequest,
        ]);
    }

    public function delete($id)
    {
        CourseProjectRequest::where([
            ['student_id', Auth::user()->student->id],
            ['task_id', $id],
        ])->delete();
        return Response::json('success');
    }

    public function resend(Request $request, $id)
    {
        // TODO: Создать отдельный Request с валидацией
        $this->validate($request, [
            'message' => 'max:255',
        ]);
        $courseProjectRequest = CourseProjectRequest::where([
            ['student_id', $id],
            ['task_id', $request['id']]
        ])->update([
            'status' => 0,
            'message' => $request['message'],
        ]);
        return Response::json([
            'courseProjectStatus' => 0,
        ]);
    }
}
