@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default text-center">
                <div class="panel-heading">@lang('dashboard.professor.course_work.title')</div>
                <div class="panel-body">
                    <a class="btn btn-primary" href="/course_works/create">
                        @lang('dashboard.professor.course_work.create')
                    </a>
                    <a class="btn btn-primary" href="/course_works">
                        @lang('dashboard.professor.course_work.list')
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default text-center">
                <div class="panel-heading">@lang('dashboard.professor.diploma.title')</div>
                <div class="panel-body">
                    <a class="btn btn-primary" href="/diplomas/create">
                        @lang('dashboard.professor.diploma.create')
                    </a>
                    <a class="btn btn-primary" href="/diplomas">
                        @lang('dashboard.professor.diploma.list')
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    <div class="col-md-6">
        <div class="panel panel-default text-center">
            <div class="panel-heading">@lang('dashboard.professor.discipline.title')</div>
            <div class="panel-body">
                <a href="/disciplines/create" class="btn btn-primary">
                    @lang('dashboard.professor.discipline.create')
                </a>
                <a href="/disciplines" class="btn btn-primary">
                    @lang('dashboard.professor.discipline.list')
                </a>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
