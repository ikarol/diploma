@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default text-center">
                <div class="panel-heading">@lang('Groups')</div>
                <div class="panel-body">
                    <a class="btn btn-primary"
                        href="/admin/groups">
                            @lang('Groups management')
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default text-center">
                <div class="panel-heading">@lang('Disciplines')</div>
                <div class="panel-body">
                    <a class="btn btn-primary"
                        href="/admin/disciplines">
                            @lang('Disciplines management')
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
