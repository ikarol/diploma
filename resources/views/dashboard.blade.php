@extends('layouts.app')

@section('content')
<div class="container">
    @if ($userType == 1)
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default text-center">
                    <div class="panel-heading">@lang('Course works')</div>
                    <div class="panel-body">
                        <a class="btn btn-primary"
                            href="/course_works">
                                @lang('Course works management')
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default text-center">
                    <div class="panel-heading">@lang('Diploma projects')</div>
                    <div class="panel-body">
                        <a class="btn btn-primary"
                            href="/diplomas">
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
                <div class="panel-heading">@lang('Course works')</div>
                <div class="panel-body">
                    <a class="btn btn-primary"
                    href="/course_works">
                            @lang('List of tasks')
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default text-center">
                <div class="panel-heading">@lang('Diploma projects')</div>
                <div class="panel-body">
                    <a class="btn btn-primary" href="/diplomas">
                            @lang('List of tasks')
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
