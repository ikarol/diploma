@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1>@lang('Diploma projects')</h1>
            <i>@lang('List of tasks')</i>
        </div>
        <div class="panel-body">
            <professor-diplomas-table>
                <template slot="title-group">@lang('Group')</template>
                <template slot='button-edit'>@lang('Edit')</template>
                <template slot='button-delete'>@lang('Delete')</template>
                <template slot='title-topic'>@lang('Topic')</template>
                <template slot='title-request'>@lang('Number of requests')</template>
                <template slot='title-cr_at'>@lang('Publication date')</template>
                <template slot='title-actions'>@lang('Actions')</template>
                <template slot='no-diplomas'>@lang('You haven\'t published any tasks yet')</template>
                <template slot="open-diploma-modal">@lang('New task')</template>
                <template slot='modal-title'>@lang('New task')</template>
                <template slot='task-title'>@lang('Topic')</template>
                <template slot='task-description'>@lang('Description')</template>
                <template slot='task-technologies'>@lang('Technologies')</template>
                <template slot='task-group-title'>@lang('Group')</template>
                <template slot="publish-task">@lang('Publish')</template>
                <template slot="close-modal">@lang('Cancel')</template>
            </professor-diplomas-table>
        </div>
    </div>
</div>
@endsection
