@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">Report for</div>

                <div class="panel-body">
                    <p> <strong>Package Name :</strong> {{ isset($package_name) ? $package_name : '' }}</p>
                    <p> <strong>Version Code :</strong> {{ isset($app_version_code) ? $app_version_code : '' }}</p>
                    <p> <strong>Brand : </strong> {{ isset($brand) ? $brand : '' }}</p>
                    <p> <strong>Model : </strong> {{ isset($phone_model) ? $phone_model : '' }}</p>
                </div>

            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">Reports</div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-responsive table-bordered table-hover mb-0" style="overflow-x: auto;">
                            <thead>
                                <th>Report Id</th>
                                <th>Installation Id</th>
                                <th>Reported On</th>
                            </thead>
                            <tbody>
                                @if ($data->count() == 0)
                                    <tr>
                                        <td colspan="5">No products to display.</td>
                                    </tr>
                                @endif

                                @foreach ($data as $report)
                                    <tr>
                                        <td>
                                            <a class="text-primary"
                                                href="{{ url('/report/detail?report_id=' . $report->report_id) }}">{{ $report->report_id }}</a>
                                        </td>
                                        <td>{{ $report->installation_id }}</td>
                                        <td>{{ $report->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
