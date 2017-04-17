@extends ('layouts.app')

@section ('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading text-center"><h1>@lang('diplomas.professor.create.title')</h1></div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="/diplomas">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">@lang('diplomas.professor.create.task.title')</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" required autofocus>

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">@lang('diplomas.professor.create.task.description')</label>

                            <div class="col-md-6">
                                <textarea id="description" style="resize: vertical;" class="form-control" name="description" value="{{ old('description') }}" required autofocus></textarea>

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('technologies') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">@lang('diplomas.professor.create.task.technologies')</label>

                            <div class="col-md-6">
                                <input id="technologies" type="text" class="form-control" name="technologies" value="{{ old('technologies') }}"  autofocus>

                                @if ($errors->has('technologies'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('technologies') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    @lang('diplomas.professor.create.submit')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
