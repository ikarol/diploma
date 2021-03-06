@extends('layouts.app')

@section('content')
<div class="container">
    @if ($userType == 1)
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default text-center">
                    <div class="panel-heading">@lang('Course projects')</div>
                    <div class="panel-body">
                        <a class="btn btn-primary"
                            href="/course_projects#/prof-course_project-list">
                                @lang('Course projects management')
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default text-center">
                    <div class="panel-heading">@lang('Diploma projects')</div>
                    <div class="panel-body">
                        <a class="btn btn-primary"
                            href="/diplomas#/prof-diploma-list">
                                @lang('Diploma projects management')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($userType == 2)
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default text-center">
                    <div class="panel-heading">@lang('Course projects')</div>
                    <div class="panel-body">
                        <a class="btn btn-primary"
                        href="/course_projects#/stud-course_project-list">
                                @lang('List of course projects')
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default text-center">
                    <div class="panel-heading">@lang('Diploma projects')</div>
                    <div class="panel-body">
                        <a class="btn btn-primary" href="/diplomas#/stud-diploma-list">
                                @lang('List of diploma projects')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
