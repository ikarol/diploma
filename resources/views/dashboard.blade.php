@extends('layouts.app')

@section('content')
<div class="container">
    @if ($userType == 1)
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default text-center">
                    <div class="panel-heading">@lang('Course works')</div>
                    <div class="panel-body">
                        <a class="btn btn-primary" href="/course_works?professor=
                            {{Auth::user()->professor->id}}">
                                @lang('Course works management')
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default text-center">
                    <div class="panel-heading">@lang('Diploma projects')</div>
                    <div class="panel-body">
                        <a class="btn btn-primary" href="/diplomas?professor=
                            {{Auth::user()->professor->id}}">
                                @lang('Diploma projects management')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default text-center">
                <div class="panel-heading">@lang('Course works')</div>
                <div class="panel-body">
                    <a class="btn btn-primary" href="/course_works?group=
                        {{Auth::user()->student->group_id}}">
                            @lang('Course works management')
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default text-center">
                <div class="panel-heading">@lang('Diploma projects')</div>
                <div class="panel-body">
                    <a class="btn btn-primary" href="/diplomas?group=
                        {{Auth::user()->student->group_id}}">
                            @lang('Diploma projects management')
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
