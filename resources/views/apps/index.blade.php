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

    <section class="section">
        <div class="row" id="basic-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            @if ($data->isEmpty())
                            <p class="mt-4 text-warning">There no apps to show, add a new one with Create
                                menu.</p>
                            @else
                            <div class="table-responsive">
                                <table class="table table-lg">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Package Name</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data->items() as $key => $value)
                                        <tr>
                                            <td><img src="{{ str_contains($value->icon_url, 'http') ? $value->icon_url : asset("storage/$value->icon_url") }}" width="50" height="50"></td>
                                            <td class="text-bold-500">{{ $value->name }}</td>
                                            <td>{{ $value->package_name }}</td>
                                            <td class="text-bold-500"><a class="btn btn-primary" href="{{ url("app/$value->id/") }}">View</a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    {!! $data->links('pagination::bootstrap-4') !!}
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
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
