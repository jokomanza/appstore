<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Report #{{ $report->id }}</h3>
                <p class="text-subtitle text-muted">Report detail.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    @yield('breadcrumb')
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row">

            <div class="col-md-12">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">App Information</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">

                            <p>App Name : {{ $report->app->name }}</p>
                            <p>Description : {{ $report->app->description }}</p>
                            <p>Package Name : {{ $report->app->package_name }}</p>
                            <p>Error Type : {{ $report->is_silent ? 'Silent Error' : 'Crash' }}</p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Version Information</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">

                            <p>Version Code : {{ $report->app_version_code }}</p>
                            <p>Version Name : {{ $report->app_version_name }}</p>
                            <p>Debug : {{ $report->build_config['debug'] }}</p>
                            <p>Build Type : {{ $report->build_config['build_type'] }}</p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Device Information</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">

                            <p>Brand : {{ $report->brand }}</p>
                            <p>Model : {{ $report->phone_model }}</p>
                            <p>Android : {{ $report->android_version }}</p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Miscellaneous Information</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">

                            <p>Files Path : {{ $report->file_path }}</p>
                            <p>App Start Date : {{ $report->user_app_start_date }}</p>
                            <p>App Crash Date : {{ $report->user_crash_date }}</p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Full Report</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <a href="{{ route($fullReportRoute, $report->report_id) }}" target="__blank">See full report</a>

                        </div>
                    </div>
                </div>
            </div>

            @if(Auth::user()->access_level != 1)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">More</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">

                                <div class="buttons">
                                    <form class="col-1" method="POST" action="{{ $isClientApp ? route($destroyReportRoute, $report->id) :  route($destroyReportRoute, [$report->app->package_name, $report->id]) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <input type="submit" class="delete-report btn btn-danger" value="Delete">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Stack Trace</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <pre>{{ $report->stack_trace }}</pre>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Log Cat</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <pre>{{ $report->logcat }}</pre>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('.delete-report').click(function(e) {
            e.preventDefault() // Don't post the form, unless confirmed

            swal({
                    title: "Are you sure?"
                    , text: "Once deleted, you will not be able to recover this report!"
                    , icon: "warning"
                    , buttons: true
                    , dangerMode: true
                , })
                .then((willDelete) => {
                    if (willDelete) {
                        $(e.target).closest('form').submit() // Post the surrounding form
                    } else {

                    }
                });
        });
    })

</script>