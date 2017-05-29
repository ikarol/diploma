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
                            <h1>@lang('Professors')</h1>
                        </div>
                        <div class="panel-body">
                            <ul>
                                <li>@lang('Placement of tasks for implementation, indicating the topic of the project , a brief description of the work, expected development tools')</li>
                                <li>@lang('Confirmation and rejection of applications of specific students for work execution')</li>
                                <li>@lang('Setting deadlines of the project stages execution')</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center">
                            <h1>@lang('Students')</h1>
                        </div>
                        <div class="panel-body">
                            <ul>
                                <li>@lang('Viewing a list of available themes')</li>
                                <li>@lang('Applying for the selected theme')</li>
                                <li>@lang('Review schedules of consultations for the project')</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
