@extends('admin.layouts.admin')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Show all Users</h3>
                <p class="text-subtitle text-muted">Show all users in Quick App Store.</p>
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
        <div class="card">
            {{-- <div class="card-header">
                Datatable
            </div> --}}
            <div class="card-body">
                <table class="table" id="apps">
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

<script>
    // Jquery Datatable
    $(document).ready(function() {
        $('#apps').DataTable({
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
@endsection
