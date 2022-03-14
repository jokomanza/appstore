@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            @if ($data == null)
                <h2 class="mt-0 font-weight-bold" style="margin-top: 0px">Report Not Found</h2>
            @else

                <div class="col-md-3">

                    <h2 class="mt-0 font-weight-bold" style="margin-top: 0px">Report #{{ $data->id }}</h2>
                    <p>Application name</p>
                    <p> {{ $data->package_name }}</p>
                    <p> {{ $data->is_silent ? 'Silent Error' : 'Crash' }}</p>

                    <h3 style="margin-top: 3rem; margin-bottom:3rem">Application Information</h3>

                    <p> <strong>Version Code :</strong> {{ $data->app_version_code }}</p>
                    <p> <strong>Version Name :</strong> {{ $data->app_version_name }}</p>
                    <p> <strong>Debug :</strong> {{ $data->build_config['debug'] }}</p>
                    <p> <strong>Build Type :</strong> {{ $data->build_config['build_type'] }}</p>

                    <h3 style="margin-top: 3rem; margin-bottom:3rem">Device Information</h3>

                    <p> <strong>Brand : </strong> {{ $data->brand }}</p>
                    <p> <strong>Model : </strong> {{ $data->phone_model }}</p>
                    <p> <strong>Android : </strong> {{ $data->android_version }}</p>

                    <h3 style="margin-top: 3rem; margin-bottom:3rem">Miscellaneous Information</h3>

                    <p> <strong>File Path : </strong> {{ $data->file_path }}</p>
                    <p> <strong>App Start Date : </strong> {{ $data->user_app_start_date }}
                    </p>
                    <p> <strong>App Crash Date : </strong> {{ $data->user_crash_date }}</p>

                    <h3 style="margin-top: 3rem; margin-bottom:3rem">Full Report</h3>

                    <a href="{{ url('/report/' . $data->report_id . '/full') }}" target="__blank">See full report</a>

                    <div style="height: 3rem"></div>

                </div>

                <div class="col-md-9">
                    <div class="table-responsive">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#tab1">Stack Trace</a></li>
                            <li><a data-toggle="tab" href="#tab2">Log Cat</a></li>
                            <li><a data-toggle="tab" href="#tab3">Tab 3</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="tab1" class="tab-pane fade in active">
                                <pre style="margin-top: 1.5rem">{{ $data->stack_trace }}</pre>
                            </div>
                            <div id="tab2" class="tab-pane fade">
                                <pre class="text-sm" style="margin-top: 1.5rem">{{ $data->logcat }}</pre>
                            </div>
                            <div id="tab3" class="tab-pane fade">
                                <h3>Tab 3</h3>
                                <p>Content for tab 3.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
