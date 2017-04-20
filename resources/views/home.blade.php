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
                                <li>@lang('Setting deadlines and marking the success of the project stages execution')</li>
                                <li>@lang('Entering a schedule of student consultations and notes on attending consultations')</li>
                                <li>@lang('Performing preliminary assessment of works')</li>
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
                                <li>@lang('Submit your own themes and development tools')</li>
                                <li>@lang('Applying for the selected theme')</li>
                                <li>@lang('Notification of the scientific director on the progress of the project')</li>
                                <li>@lang('Review schedules of consultations and preliminary assessments for the project')</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
