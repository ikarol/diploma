@extends('layouts.app')

@section('content')
<div class="container">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h1>{{ $info->title }}</h1>
            <span>@lang('Jobs')</span>
        </div>
        <div class="panel-body">
            @if ($userType == 1)
                <course-project-jobs courseproject={{ $id }}></course-project-jobs>
            @elseif ($userType == 2)
                @if ($jobs === null)
                    <p>@lang('There are no jobs for this project yet')</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Description')</th>
                                <th>@lang('Assigned')</th>
                                <th>@lang('Deadline')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jobs as $job)
                                <tr>
                                    <td>{{ $job->description }}</td>
                                    <td>{{ \Carbon\Carbon::parse($job->created_at)->format('d.m.Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($job->deadline)->format('d.m.Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endif
        </div>
    </div>
        {{-- <ul class="nav nav-tabs">
          <router-link tag="li" to="/{{ $id }}/prof-diploma-info" exact><a class="lul">@lang('Information')</a></router-link>
          <router-link tag="li" to="/prof-diploma-jobs"><a>@lang('Tasks')</a></router-link>
        </ul> --}}

        {{-- <ul class="nav nav-tabs">
            <router-link tag="li" to="/stud-diploma-info" exact><a>@lang('Information')</a></router-link>
            @if ($access === 1)
                <router-link tag="li" to="/stud-diploma-jobs"><a>@lang('Tasks')</a></router-link>
            @endif
        </ul> --}}
    {{-- <div class="panel panel-default">
        <div class="panel-body">
            <router-view></router-view>
        </div>
    </div> --}}
</div>
@endsection
