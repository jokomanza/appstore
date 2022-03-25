@extends('admin.layouts.admin')

@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>All Users</h3>
                    <p class="text-subtitle text-muted">Display all users in Quick App Store.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Users</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">

            <div class="row">
                @include('base.components.alerts.success')

                @include('base.components.alerts.errors')
            </div>

            <div class="card">
                <div class="card-body">
                    <table class="table" id="users">
                        <thead>
                        <tr>
                            <th>Registration Number</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Option</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </section>
    </div>

@endsection

@push('script')
    <script>
        $(document).ready(function () {
            $('#users').dataTable().fnDestroy()

            $('#users').DataTable({
                "processing": true
                , "serverSide": true
                , "autoWidth": false
                , "ajax": {
                    "url": "{{ route('admin.user.datatables') }}"
                    , "dataType": "json"
                    , "type": "POST"
                    , "data": {
                        _token: "{{csrf_token()}}"
                    }
                }
                , "columns": [{
                    "data": "registration_number"
                }
                    , {
                        "data": "name"
                    }
                    , {
                        "data": "email"
                    }
                    , {
                        "data": "options"
                    }
                ]

            });

        });

    </script>
@endpush
