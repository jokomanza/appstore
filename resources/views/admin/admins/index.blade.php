@extends('admin.layouts.admin')

@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>All Admins</h3>
                    <p class="text-subtitle text-muted">Display all admins in Quick App Store. Note that you cannot modify another admin account, the only person who can change an admin account is the owner himself</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Admins</li>
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
                    <table class="table" id="admins">
                        <thead>
                        <tr>
                            <th>Registration Number</th>
                            <th>Name</th>
                            <th>Email</th>
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
            $("#admins").dataTable().fnDestroy();

            $('#admins').DataTable({
                "processing": true
                , "serverSide": true
                , "autoWidth": false
                , "ajax": {
                    "url": "{{ route('admin.admin.datatables') }}"
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
                ]

            });

        });

    </script>
@endpush
