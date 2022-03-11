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
                <h3>App #{{ $data->id }}</h3>
                <p class="text-subtitle text-muted">{{ $data->name }}'s detail.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('app.index') }}">Apps</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $data->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row" id="basic-table">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Detail App</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">

                            <div class="mb-4">
                                <img src="{{ str_contains($data->icon_url, 'http') ? $data->icon_url : asset("storage/$data->icon_url") }}" width="100" height="100">
                            </div>
                            <div class="col">
                                <p class="text">Name : {{ $data->name }}</p>
                            </div>
                            <p>Package Name : {{ $data->package_name }}</p>
                            <p>Type : {{ $data->type }}</p>
                            <p>Description : {{ $data->description }}</p>

                            <div class="row">
                                @if ($data->repository_url)
                                <div class="mb-3">
                                    <a href="{{ $data->repository_url }}">Git Repository</a>
                                </div>
                                @endif
                                @if ($data->user_documentation_url)
                                <div class="mb-3">
                                    <a href="{{ asset('/storage/' . $data->user_documentation_url) }}">User
                                        Documentation</a>
                                </div>
                                @endif
                                @if ($data->developer_documentation_url)
                                <div class="mb-3">
                                    <a href="{{ asset('/storage/' . $data->developer_documentation_url) }}">Developer
                                        Documentation</a>
                                </div>
                                @endif
                            </div>
                            <p>Created {{ (new \Carbon\Carbon($data->created_at))->diffForHumans() }}
                                {{ $data->created_at == $data->updated_at? '': ' and updated ' . (new \Carbon\Carbon($data->updated_at))->diffForHumans() }}
                            </p>

                            <br><br>

                            <div class="buttons">
                                <a clas="col-1" href="{{ url("app/$data->id/edit") }}" class="btn btn-primary">Update</a>
                                <form class="col-1" method="POST" action="{{ url("app/$data->id") }}">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}

                                    <input type="submit" class="btn btn-danger" value="Delete">
                                </form>
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
                                            <th></th>
                                            <th>Version</th>
                                            <th>Size</th>
                                            <th>Released</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data->app_versions as $key => $value)
                                        <tr>
                                            <td><img src="{{ str_contains($value->icon_url, 'http') ? $value->icon_url : asset("storage/$value->icon_url") }}" width="50" height="50"></td>
                                            <td class="text-bold-500">{{ $value->version_name }}</td>
                                            <td>{{ $value->apk_file_size }}</td>
                                            <td>{{ $value->updated_at }}</td>
                                            <td class="text-bold-500">
                                                <div class="buttons">
                                                    <a class="btn btn-success" href="{{ asset("storage/$value->apk_file_url") }}">Download</a>
                                                    <a class="btn btn-primary" href="{{ route('version.show', [$data->id, $value->id]) }}">View</a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <a class="btn btn-success" href="{{ route('version.create', $data->id) }}">Create new
                                    version</a>
                                <div class="d-flex justify-content-center">
                                    {{-- {!! $data->links() !!} --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="basic-table">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Developers</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">

                            @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-lg">
                                    <thead>
                                        <tr>
                                            <th>Registration Number</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Level</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($developers as $key => $value)
                                        <tr>
                                            <td>{{ $value->user_registration_number }}</td>
                                            <td class="text-bold-500">{{ $value->user->name }}</td>
                                            <td>{{ $value->user->email }}</td>
                                            <td>{{ $value->user->level }}</td>
                                            <td class="text-bold-500">
                                                <form action="{{ route('app.developer.destroy', [$data->id, $value->user_registration_number]) }}" method="post">
                                                    {{ method_field('DELETE') }}
                                                    {{ csrf_field() }}

                                                    <div class="buttons">
                                                        <input type="submit" class="btn btn-danger" value="Delete">
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <p>Add Developer</p>

                            <form action="{{ route('app.developer.store', [$data->id]) }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <input class="form-control" type="text" name="app_id" value="{{ $data->id }}" hidden>
                                <div class="form-group col-md-5">
                                    <label class="mt-3 mb-2" for="user_registration_number">Registration Number</label>
                                    {{ Form::select('user_registration_number', $allowedDevelopers, old('user_registration_number'), ['class' => 'form-select', 'name' => 'user_registration_number']) }}
                                </div>
                                <div class="form-group mt-3">
                                    <input class="btn btn-primary" type="submit" value="Add">
                                </div>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>





<script>
    $('.delete-application').click(function(e) {
        // e.preventDefault() // Don't post the form, unless confirmed

        bootbox.confirm({
            size: "small"
            , message: "Are you sure?"
            , callback: function(result) {
                /* result is a boolean; true = OK, false = Cancel*/
            }
        })

        // if (confirm('Are you sure?')) {
        //     // Post the form
        //     $(e.target).closest('form').submit() // Post the surrounding form
        // }
    });

    $(document).ready(function() {
        $('.form-select').select2()
    })

</script>
@endsection
