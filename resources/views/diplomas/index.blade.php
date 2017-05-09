@extends('layouts.app')

@section('content')
<div class="container">
    <h1>@lang('Diploma projects')</h1>
        @if ($userType == 1)
            <ul class="nav nav-tabs">
              <router-link tag="li" to="/prof-task-list" exact><a class="lul">@lang('List of tasks')</a></router-link>
              <router-link tag="li" to="/prof-requests"><a>@lang('Requests')</a></router-link>
            </ul>

        @elseif ($userType == 2)
            <ul class="nav nav-tabs">
              <router-link tag="li" to="/stud-task-list" exact><a>@lang('List of tasks')</a></router-link>
              <router-link tag="li" to="/stud-requests"><a>@lang('Requests')</a></router-link>
            </ul>
        @endif
        <div class="panel panel-default">
            <div class="panel-body">
                <router-view></router-view>
            </div>
        </div>
</div>
@endsection
