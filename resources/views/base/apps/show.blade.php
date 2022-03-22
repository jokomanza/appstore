<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{ $isClientApp ? 'Client App' : "App #$app->id" }}</h3>
                <p class="text-subtitle text-muted">{{ $isClientApp ? 'Client App' : $app->name }} detail.</p>
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
                        <h4 class="card-title">Detail</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">

                            <div class="mb-4">
                                <img src="{{ str_contains($app->icon_url, 'http') ? $app->icon_url : asset("storage/$app->icon_url") }}"
                                     width="100" height="100">
                            </div>
                            <div class="col">
                                <p class="text">Name : {{ $app->name }}</p>
                            </div>
                            <p>Package Name : {{ $app->package_name }}</p>
                            <p>Type : {{ $app->type }}</p>
                            <p>Description : {{ $app->description }}</p>
                            @if ($isAppDeveloper || $isAppOwner)
                                <p>Api Token :
                                <pre>{{ $app->api_token }}</pre>
                                </p>
                            @endif

                            <div class="row">
                                @if ($app->repository_url)
                                    <div class="mb-3">
                                        <a href="{{ $app->repository_url }}">Git Repository</a>
                                    </div>
                                @endif
                                @if ($app->user_documentation_url)
                                    <div class="mb-3">
                                        <a href="{{ asset('/storage/' . $app->user_documentation_url) }}">User
                                            Documentation</a>
                                    </div>
                                @endif
                                @if ($app->developer_documentation_url)
                                    <div class="mb-3">
                                        <a href="{{ asset('/storage/' . $app->developer_documentation_url) }}">Developer
                                            Documentation</a>
                                    </div>
                                @endif
                            </div>
                            <p>Created {{ (new \Carbon\Carbon($app->created_at))->diffForHumans() }}
                                {{ $app->created_at == $app->updated_at? '': ' and updated ' . (new \Carbon\Carbon($app->updated_at))->diffForHumans() }}
                            </p>

                            @if($isAppDeveloper || $isAppOwner)
                                <p>Your position in this project is as an
                                    app {{ $isAppDeveloper ? "Developer" : "Owner" }}</p>
                            @endif

                            <br><br>

                            <div class="buttons">
                                @if (($isClientApp && $isAppOwner) || $isAppDeveloper || $isAppOwner)
                                    <a clas="col-1"
                                       href="{{ $isClientApp ? route($editClientAppRoute) : route($editAppRoute, $app->package_name) }}"
                                       class="btn btn-primary">Edit</a>
                                @endif

                                @if ($isAppOwner && !$isClientApp)
                                    <form class="col-1" method="POST"
                                          action="{{ route($destroyAppRoute, $app->package_name) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <input type="submit" class="delete-application btn btn-danger" value="Delete">
                                    </form>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Versions</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-lg">
                                    <thead>
                                    <tr>
                                        <th>Icon</th>
                                        <th>Version</th>
                                        <th>Size</th>
                                        <th>Released</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if ($app->app_versions->count() == 0)
                                        <tr>
                                            <td colspan="5">
                                                <p class="text-center">No version available yet</p>
                                            </td>
                                        </tr>
                                    @endif
                                    @foreach ($app->app_versions as $key => $value)
                                        <tr>
                                            <td>
                                                <img src="{{ str_contains($value->icon_url, 'http') ? $value->icon_url : asset("storage/$value->icon_url") }}"
                                                     width="50" height="50"></td>
                                            <td class="text-bold-500">{{ $value->version_name }}</td>
                                            <td>{{ $value->apk_file_size }}</td>
                                            <td>{{ $value->updated_at }}</td>
                                            <td class="text-bold-500">
                                                <div class="buttons">
                                                    <a class="btn btn-success"
                                                       href="{{ asset("storage/$value->apk_file_url") }}">Download</a>
                                                    @if($isClientApp)
                                                        @if($isAppOwner || $isAppDeveloper)
                                                            <a class="btn btn-primary"
                                                               href="{{ route($showClientVersionRoute, [$value->version_name]) }}">View</a>
                                                        @endif
                                                    @else
                                                        <a class="btn btn-primary"
                                                           href="{{ route($showVersionRoute, [$app->package_name, $value->version_name]) }}">View</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @if ($isAppDeveloper || $isAppOwner)
                                    <a class="btn btn-success"
                                       href="{{ $isClientApp ? route($createClientVersionRoute) : route($createVersionRoute, $app->package_name) }}">Create
                                        new
                                        version</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

@if($isAppDeveloper || $isAppOwner)
    <div class="card" id="card-report">
        <div class="card-header">
            <h4 class="card-title">Crash Report</h4>
        </div>
        <div class="card-content">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-lg" id="reports">
                        <thead>
                        <tr>
                            <th>Date</th>
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
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Persons</h4>
            </div>
            <div class="card-content">
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-lg">
                            <thead>
                            <tr>
                                <th>Registration Number</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($permissions as $key => $value)
                                <tr>
                                    <td>{{ $value->user_registration_number }}</td>
                                    <td class="text-bold-500">{{ $value->user->name }}</td>
                                    <td>{{ $value->user->email }}</td>
                                    <td>{{ $value->type }}</td>
                                    <td class="text-bold-500">
                                        @if ($isAppOwner)
                                            <form
                                                    action="{{ route($destroyPermissionRoute, [$app->id, $value->user_registration_number]) }}"
                                                    method="post">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}

                                                <div class="buttons">
                                                    <input type="submit" class="btn btn-danger" value="Delete">
                                                </div>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($isAppOwner)
                        <h5>Add Person</h5>

                        <form action="{{ route($storePermissionRoute, [$app->package_name]) }}" method="post"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <input class="form-control" type="text" name="app_id" value="{{ $app->id }}" hidden>
                            <div class="form-group col-md-5">
                                <label class="mt-3 mb-2" for="user_registration_number">Registration
                                    Number</label>
                                {{ Form::select('user_registration_number', $allowedPersons, old('user_registration_number'), ['class' => 'form-select','name' => 'user_registration_number']) }}


                                <div class="form-group col-md-5">
                                    <label class="mt-3 mb-2" for="type">Type</label>
                                    {{ Form::select('type', ['developer' => 'developer', 'owner' => 'owner'], old('type'), ['class' => 'form-select','name' => 'type']) }}
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <input class="btn btn-primary" type="submit" value="Add">
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(document).ready(function () {
            $("#reports").dataTable().fnDestroy();

            $('.delete-application').click(function (e) {
                e.preventDefault() // Don't post the form, unless confirmed

                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this app and all of those versions!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            $(e.target).closest('form').submit() // Post the surrounding form
                        } else {

                        }
                    });
            });
            $('.form-select').select2()

            $('#reports').DataTable({
                "processing": true,
                "serverSide": true,
                "autoWidth": false,
                "ajax": {
                    "url": "{{ route($reportDataTablesRoute, [$app->package_name]) }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {
                        _token: "{{ csrf_token() }}"
                    }
                },
                "columns": [{
                    "data": "date"
                }, {
                    "data": "app_version"
                }, {
                    "data": "android_version"
                }, {
                    "data": "device"
                }, {
                    "data": "exception"
                }, {
                    "data": "options"
                }]

            });
        })
    </script>
@endpush
