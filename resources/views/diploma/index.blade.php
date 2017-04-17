@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1>@lang('diplomas.professor.index.title')</h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-8">
                    @foreach ($diplomas as $diploma)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1>
                                <a href="/diplomas/{{ $diploma->id }}"> {{ $diploma->title }}</a>
                            </h1>
                        <i>
                            {{ $diploma->professor->surname }}
                            {{ $diploma->professor->name }}
                            {{ $diploma->professor->middlename }}
                            {{  Carbon\Carbon::parse($diploma->created_at)->format('d.m.Y') }}
                        </i>
                        </div>
                        <div class="panel-body">
                            <p>{{ str_limit($diploma->description, 100, '...') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @include ('layouts.sidebar')
            </div>
        </div>
    </div>
</div>
@endsection
