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
                <h3>Show all Apps</h3>
                <p class="text-subtitle text-muted">Show all apps in Quick App Store.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Apps</li>
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
                            <th></th>
                            <th>Name</th>
                            <th>Package Name</th>
                            <th>Updated</th>
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
            "processing": true,
            "serverSide": true,
            "autoWidth": false,
            "ajax": {
                "url": "{{ route('api.apps.datatables') }}"
                , "dataType": "json"
                , "type": "POST"
                , "data": {
                    _token: "{{csrf_token()}}"
                }
            }
            , "columns": [{
                    "data": "id"
                }
                , {
                    "data": "name"
                }
                , {
                    "data": "package_name"
                }
                , {
                    "data": "updated_at"
                }
                , {
                    "data": "options"
                }
            ]

        });
    });

</script>
@endsection
