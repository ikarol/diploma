@extends('layouts.app')

@section('content')
<div class="container">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h1>{{ $info->title }}</h1>
        </div>
        <div class="panel-body">
            <label for="professor">@lang('Professor')</label>
            <p name="professor">{{ $info->professor->user->surname }} {{ $info->professor->user->name }}</p>
            <label for="technologies">@lang('Technologies')</label>
            <p name="technologies">{{ $info->technologies ? $info->technologies : '-' }}</p>
            <label for="description">@lang('Description')</label>
            <p name="description">{{ $info->description}}</p>
            <label for="cr_at">@lang('Published')</label>
            <p name="cr_at">{{ \Carbon\Carbon::parse($info->created_at)->format('d.m.Y') }}</p>
            @if ($userType == 1)
                @if ($info->student !== null)
                    <label for="student">@lang('Student')</label>
                    <p name="student">{{ $info->student}}</p>
                @endif
                <a href="/diplomas/jobs/{{ $id }}" class="btn btn-primary">@lang('Show jobs')</a>
            @elseif ($userType == 2)
                @if ($info->student !== null)
                    <a href="/diplomas/jobs/{{ $id }}" class="btn btn-primary">@lang('Show jobs')</a>
                @endif
            @endif
        </div>
    </div>
        {{-- <ul class="nav nav-tabs">
          <router-link tag="li" to="/{{ $id }}/prof-diploma-info" exact><a class="lul">@lang('Information')</a></router-link>
          <router-link tag="li" to="/prof-diploma-jobs"><a>@lang('Tasks')</a></router-link>
        </ul> --}}

        {{-- <ul class="nav nav-tabs">
            <router-link tag="li" to="/stud-diploma-info" exact><a>@lang('Information')</a></router-link>
            @if ($access === 1)
                <router-link tag="li" to="/stud-diploma-jobs"><a>@lang('Tasks')</a></router-link>
            @endif
        </ul> --}}
    {{-- <div class="panel panel-default">
        <div class="panel-body">
            <router-view></router-view>
        </div>
    </div> --}}
</div>
@endsection
