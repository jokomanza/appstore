@push('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap5.datatables.css') }}"/>
    <script type="text/javascript" src="{{ asset('js/datatables.js') }}"></script>
@endpush

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>All Apps</h3>
                <p class="text-subtitle text-muted">Displays all applications in the quick app store.</p>
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
            @include('base.components.alerts.success')

            @include('base.components.alerts.errors')
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table" id="apps">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Icon</th>
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

@push('script')
    <script>
        $(document).ready(function () {
            $("#apps").dataTable().fnDestroy();

            $('#apps').DataTable({
                "processing": true,
                "serverSide": true,
                "autoWidth": false,
                "ajax": {
                    "url": "{{ route($appsDataTablesRoute) }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {
                        _token: "{{ csrf_token() }}"
                    }
                },
                "columns": [{
                    "data": "id"
                }, {
                    "data": "icon"
                }, {
                    "data": "name"
                }, {
                    "data": "package_name"
                }, {
                    "data": "updated_at"
                }, {
                    "data": "options"
                }]

            });
        });
    </script>
@endpush
