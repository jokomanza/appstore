@push('head-script')
    <link rel="stylesheet" href="{{ asset('vendor/iconly/bold.css') }}">
    <script src="{{ asset('assets/js/apexcharts.js') }}"></script>
@endpush

@section('content')
    <div class="page-heading">
        <h3>Quick App Store Statistics</h3>
    </div>
    <div class="page-content">

        <div class="row">
            @include('base.components.alerts.success')

            @include('base.components.alerts.errors')
        </div>

        <section class="row">
            <div class="col-12 col-lg-9">
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon purple">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Apps</h6>
                                        <h6 class="font-extrabold mb-0">{{ $appsCount }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon blue">
                                            <i class="iconly-boldProfile"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Users</h6>
                                        <h6 class="font-extrabold mb-0">{{ $usersCount }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="iconly-boldAdd-User"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Admins</h6>
                                        <h6 class="font-extrabold mb-0">{{ $adminsCount }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon red">
                                            <i class="iconly-boldBookmark"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Error Reports</h6>
                                        <h6 class="font-extrabold mb-0">{{ $errorsCount }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Error Reports</h4>
                            </div>
                            <div class="card-body">
                                <div id="chart-reports"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card">
                    <div class="card-body py-4 px-5">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl">
                                <img src="{{ asset('images/user1.png') }}">
                            </div>
                            <div class="ms-3 name">
                                <h5 class="font-bold">{{ Auth::user()->name }}</h5>
                                <h6 class="text-muted mb-0">{{ Auth::user()->registration_number }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                @yield('recent-apps-card')
            </div>
        </section>
        <section class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Apps</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-apps"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Versions</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-versions"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@push('script')
    <script>
        $(document).ready(function () {
            var options = {
                chart: {
                    type: 'area',
                    height: 300
                },
                series: [],
                noData: {
                    text: 'No data.'
                },
                colors: ['#dc3545'],
            }

            const appsOption = {
                ...options,
                colors: ['#008b75'],
            }
            const versionsOption = {
                ...options,
                colors: ['#008b75'],
            }

            var reportChart = new ApexCharts(document.querySelector("#chart-reports"), options);
            var appsChart = new ApexCharts(document.querySelector("#chart-apps"), appsOption);
            var versionsChart = new ApexCharts(document.querySelector("#chart-versions"), appsOption);
            reportChart.render();
            appsChart.render();
            versionsChart.render();

            var url = '{{ route('report.chart') }}';
            $.getJSON(url, function (response) {
                reportChart.updateSeries([{
                    name: 'Reports',
                    data: response,
                }])
            });

            url = '{{ route('apps.chart') }}';
            $.getJSON(url, function (response) {
                appsChart.updateSeries([{
                    name: 'Apps',
                    data: response,
                }])
            });

            url = '{{ route('versions.chart') }}';
            $.getJSON(url, function (response) {
                versionsChart.updateSeries([{
                    name: 'Versions',
                    data: response,
                }])
            });
        })

    </script>
@endpush
