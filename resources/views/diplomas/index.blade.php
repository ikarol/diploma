@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1>@lang('Diploma projects')</h1>
            <i>@lang('List of tasks')</i>
        </div>
        <div class="panel-body">
            <div>
                <label for="group" class="control-label">@lang('Group')</label>
                <select name="group" id="group">
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            @if ($diplomas->isEmpty())
                <p>@lang('You haven\'t published any tasks yet')</p>
            @else
                @foreach ($diplomas as $diploma)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <input type="checkbox">
                            <a href="diplomas/{{ $diploma->id }}">
                                {{ $diploma->title }}
                            </a>
                        </div>
                        <div class="panel-body">
                            {{ str_limit($diploma->description, 50, '...') }}
                        </div>
                        <div class="panel-footer">
                            <a href="#" class="btn btn-primary">@lang('Edit')</a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="panel-footer">
            <a href="#" class="btn btn-primary">@lang('New task')</a>
            <a href="#" class="btn btn-danger">@lang('Delete')</a>
        </div>
    </div>
</div>
@endsection
