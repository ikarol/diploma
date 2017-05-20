<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Task;
use App\Models\Request as DiplomaRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class DiplomaJobController extends Controller
{

    public function _construct()
    {
        $this->middleware('auth');
        $this->middleware('professor')->except([
            'show'
        ]);
        $this->middleware('ajax')->except([
            'show'
        ]);
    }

    public function show($id)
    {
        $userType = 0;
        if (Auth::user()->professor) {
            $userType = 1;
        } elseif (Auth::user()->student) {
            $userType = 2;
        }
        $info = Task::find($id);
        $jobs = Job::where([
            ['task_id', $id]
        ])->get();
        if (request()->ajax() && $userType === 1) {
            foreach ($jobs as &$job) {
                $job->created_at = Carbon::parse($job->created_at)->format('d.m.Y');
                $job->deadline = Carbon::parse($job->deadline)->format('d.m.Y');
            }
            return Response::json([
                'jobs' => $jobs,
            ]);
        }
        if ($userType === 2) {
            try {
                $request = DiplomaRequest::where([
                    ['student_id', Auth::user()->student->id],
                    ['task_id', $id],
                    ['status', 1],
                ])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return redirect('/');
            }
        }
        return view('diplomas.jobs.show')->with([
            'info' => $info,
            'userType' => $userType,
            'id' => $id,
            'jobs' => $jobs->isNotEmpty() ? $jobs : null,
        ]);

    }

    public function store($id, Request $request)
    {
        // TODO: Создать отдельный Request с валидацией
        $this->validate($request, [
            'deadline' => 'required|date|after_or_equal:created_at',
            'description' => 'required'
        ]);

        $newJob = Task::find($id)->jobs()->save(
            new Job([
                'description' => $request['description'],
                'created_at' => Carbon::now()->format('Y-d-m'),
                'deadline' => Carbon::createFromFormat('Y-m-d', $request['deadline'])->format('Y-d-m')
            ])
        );
        $newJob->created_at = Carbon::createFromFormat('Y-d-m', $newJob->created_at)->format('d.m.Y');
        $newJob->deadline = Carbon::createFromFormat('Y-d-m', $newJob->deadline)->format('d.m.Y');
        return Response::json([
            'job' => $newJob,
        ]);
    }

    public function delete($id)
    {
        Job::destroy($id);
        return Response::json('success');
    }

    public function update(Request $request, $id)
    {
        // TODO: Создать отдельный Request с валидацией
        $this->validate($request, [
            'deadline' => 'required|date|after_or_equal:created_at',
            'description' => 'required'
        ]);
        $updatedJob = Job::find($id);
        $updatedJob->deadline = Carbon::createFromFormat('Y-m-d', $request['deadline'])->format('Y-d-m');
        $updatedJob->description = $request['description'];
        $updatedJob->save();
        $updatedJob->created_at = Carbon::parse($updatedJob->created_at)->format('d.m.Y');
        $updatedJob->deadline =  Carbon::createFromFormat('Y-d-m', $updatedJob->deadline)->format('d.m.Y');
        return Response::json([
            'updatedJob' => $updatedJob,
        ]);
    }
}
