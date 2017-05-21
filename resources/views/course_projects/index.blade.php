@extends('layouts.app')

@section('content')
<div class="container">
    <h1>@lang('Course projects')</h1>
        @if ($userType == 1)
            <ul class="nav nav-tabs">
              <router-link tag="li" to="/prof-course_project-list" exact><a>@lang('List of course projects')</a></router-link>
              <router-link tag="li" to="/prof-course_project-requests"><a>@lang('Requests')</a></router-link>
            </ul>

        @elseif ($userType == 2)
            <ul class="nav nav-tabs">
              <router-link tag="li" to="/stud-course_project-list" exact><a>@lang('List of course projects')</a></router-link>
              {{-- <router-link tag="li" to="/stud-requests"><a>@lang('Requests')</a></router-link> --}}
            </ul>
        @endif
        <div class="panel panel-default">
            <div class="panel-body">
                <router-view></router-view>
            </div>
        </div>
</div>
@endsection
