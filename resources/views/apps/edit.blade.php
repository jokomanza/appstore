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
                <h3>Edit Application</h3>
                <p class="text-subtitle text-muted">Update app data. Note that default icon, name, package
                    name, type and description are required.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('app.index') }}">Apps</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('app.show', $data->id) }}">{{$data->name}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="col-12">
            <div class="card">
                {{-- <div class="card-header">
                        <h4 class="card-title">Detail App</h4>
                    </div> --}}
                <div class="card-content">
                    <div class="card-body">

                        <div class="row">
                            @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <br />

                            <form action="{{ url("app/$data->id") }}" method="post" enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label for="icon_file">Default Icon</label>
                                    <div>
                                        <img src="{{ str_contains($data->icon_url, 'http') ? $data->icon_url : asset("storage/$data->icon_url") }}" width="50" height="50">
                                    </div>
                                    <input class="form-control" type="file" name="icon_file" value="{{ old('icon_file') }}">
                                </div>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input class="form-control" type="text" name="name" value="{{ old('name') ? old('name') : $data->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="package_name">Package Name</label>
                                    <input class="form-control" type="text" name="package_name" value="{{ old('package_name') ? old('package_name') : $data->package_name }}">
                                </div>
                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <input class="form-control" type="text" name="type" value="{{ old('type') ? old('type') : $data->type }}">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" type="text" name="description">{{ old('description') ? old('description') : $data->description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="repository_url">Repository URL</label>
                                    <input class="form-control" type="url" name="repository_url" value="{{ old('repository_url') ? old('repository_url') : $data->repository_url }}" placeholder="http://git.quick.com/example.git">
                                </div>
                                <div class="form-group">
                                    <label for="user_documentation_file">User Documentation</label>
                                    @if ($data->user_documentation_url)
                                    <a href="{{ str_contains($data->user_documentation_url, 'http')? $data->user_documentation_url : asset('/storage/' . $data->user_documentation_url) }}">Previous user documentation</a>
                                    @endif
                                    <input class="form-control" type="file" name="user_documentation_file" value="{{ old('user_documentation_file') }}">
                                </div>
                                <div class="form-group">
                                    <label for="developer_documentation_file">Developer Documentation</label>
                                    @if ($data->developer_documentation_url)
                                    <a href="{{ str_contains($data->developer_documentation_url, 'http')? $data->developer_documentation_url: asset('/storage/' . $data->developer_documentation_url) }}">Previous developer documentation</a>
                                    @endif
                                    <input class="form-control" type="file" name="developer_documentation_file" value="{{ old('developer_documentation_file') }}">
                                </div>
                                <div class="form-group">
                                    <input class="btn btn-primary" type="submit" value="Update">
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
    autosize(document.querySelector('textarea'))

</script>


@endsection
