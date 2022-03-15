@extends('layouts.app')

@section('content')

<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Reports</h3>
                <p class="text-subtitle text-muted">All report.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reports</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Reports</h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-lg" id="reports">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Application</th>
                                <th>App Version</th>
                                <th>Android Version</th>
                                <th>Device</th>
                                <th>Exception</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#reports').DataTable({
            "processing": true
            , "serverSide": true
            , "autoWidth": false
            , "ajax": {
                "url": "{{ route('report.datatables') }}"
                , "dataType": "json"
                , "type": "POST"
                , "data": {
                    _token: "{{csrf_token()}}"
                }
            }
            , "columns": [{
                    "data": "date"
                }
                , {
                    "data": "application"
                }
                , {
                    "data": "app_version"
                }
                , {
                    "data": "android_version"
                }
                , {
                    "data": "device"
                }
                , {
                    "data": "exception"
                }
                , {
                    "data": "options"
                }
            ]

        });
    })

</script>

@endsection
