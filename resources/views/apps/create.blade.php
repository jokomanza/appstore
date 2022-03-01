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
                    <h3>Create New App</h3>
                    <p class="text-subtitle text-muted">Create a new app from here. Note that default icon, name, package
                        name, type and description are required.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('app.index') }}">Apps</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create New App</li>
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

                                <form action="{{ route('app.store') }}" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    <div class="form-group col-md-5">
                                        <label class="mt-3 mb-2" for="icon_file">Default Icon</label>
                                        <input class="form-control" type="file" accept=".jpg, .png, .jpeg"
                                            name="icon_file" value="{{ old('icon_file') }}" required autofocus>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label class="mt-3 mb-2" for="name">Name</label>
                                        <input class="form-control" type="text" name="name" value="{{ old('name') }}"
                                            placeholder="Example app" required autofocus>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label class="mt-3 mb-2" for="package_name">Package Name</label>
                                        <input class="form-control" type="text" name="package_name"
                                            value="{{ old('package_name') }}" placeholder="com.quick.example" required
                                            autofocus>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label class="mt-3 mb-2" for="type">Type</label>
                                        <input class="form-control" type="text" name="type" value="{{ old('type') }}"
                                            required autofocus>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label class="mt-3 mb-2" for="description">Description</label>
                                        <textarea class="form-control" style="height: 100px;" type="text"
                                            name="description" value="" required
                                            autofocus>{{ old('description') }}</textarea>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label class="mt-3 mb-2" for="repository_url">Repository URL</label>
                                        <input class="form-control" type="url" name="repository_url"
                                            placeholder="http://git.quick.com/example.git"
                                            value="{{ old('repository_url') }}">
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label class="mt-3 mb-2" for="user_documentation_file">User
                                            Documentation</label>
                                        <input class="form-control" type="file" name="user_documentation_file"
                                            value="{{ old('user_documentation_file') }}">
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label class="mt-3 mb-2" for="developer_documentation_file">Developer
                                            Documentation</label>
                                        <input class="form-control" type="file" name="developer_documentation_file"
                                            value="{{ old('developer_documentation_file') }}">
                                    </div>
                                    <div class="form-group mt-3">
                                        <input class="btn btn-primary" type="submit" value="Create">
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


@endsection
