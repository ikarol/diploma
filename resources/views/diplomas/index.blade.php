@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1>@lang('Diploma projects')</h1>
            <i>@lang('List of tasks')</i>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="group" class="control-label">@lang('Group')</label>
                <select name="group" id="group" class="form-control">
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            @if ($diplomas->isEmpty())
                <div id="noTasksDiv" class="col-md-4">
                    <p>@lang('You haven\'t published any tasks yet')</p>
                </div>
            @else
                <div class="form-group">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    @lang('Topic')
                                </th>
                                <th>
                                    @lang('Number of requests')
                                </th>
                                <th>
                                    @lang('Publication date')
                                </th>
                                <th>
                                    @lang('Actions')
                                </th>
                            </tr>
                        </thead>
                        <tbody id="diplomas">
                            @foreach ($diplomas as $diploma)
                                <tr>
                                    <td>
                                        {{ $diploma->title }}
                                    </td>
                                    <td>
                                        {{ count($diploma->requests) }}
                                    </td>
                                    <td>
                                        {{ Carbon\Carbon::parse($diploma->created_at)->format('d.m.Y') }}
                                    </td>
                                    <td>
                                        <button class="btn btn-primary">@lang('Edit')</button>
                                        <button class="btn btn-danger">@lang('Delete')</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <div class="panel-footer">
            <a id="new_task" data-toggle="modal" data-target="#myModal" href="#" class="btn btn-primary">@lang('New task')</a>
            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button id="closeSign" type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@lang('New task')</h4>
                  </div>
                  <div class="modal-body">
                    <div class="form-group">
                        <label for="title">@lang('Topic')</label>
                        <div id="titleGroup">
                            <input id="title" name="title" type="text" class="form-control">
                            <span class="help-block">
                                <strong id="titleErr"></strong>
                            </span>
                        </div>
                    </div>
                    <div id="descriptionGroup" class="form-group">
                        <label for="description">@lang('Description')</label>
                        <div>
                            <textarea id="description" name="description" class="form-control"></textarea>
                            <span class="help-block">
                                <strong id="descrErr"></strong>
                            </span>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button id="publishTask" class="btn btn-primary">@lang('Publish')</button>
                    <button id="closeModal" type="button" class="btn btn-default" data-dismiss="modal">@lang('Cancel')</button>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
