@extends ('layouts.app')

@section ('content')

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="text-center">
                {{-- {{ __('System for organization and management
                    of course and diploma projects') }} --}}
            @lang('System for organization and management of course and diploma projects')
            </h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center">
                            <h1>@lang('home.professor.title')</h1>
                        </div>
                        <div class="panel-body">
                            <ul>

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center">
                            <h1>@lang('home.student.title')</h1>
                        </div>
                        <div class="panel-body">
                            <ul>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection