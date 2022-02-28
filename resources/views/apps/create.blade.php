@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-5 mb-3">Create a new app</h1>
        <p>Create a new app from here. Note that default icon, name, package name, type and description are required.</p>
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
                    <input class="form-control" type="file" accept=".jpg, .png, .jpeg" name="icon_file" value="{{ old('icon_file') }}" required
                        autofocus>
                </div>
                <div class="form-group col-md-5">
                    <label class="mt-3 mb-2" for="name">Name</label>
                    <input class="form-control" type="text" name="name" value="{{ old('name') }}"
                        placeholder="Example app" required autofocus>
                </div>
                <div class="form-group col-md-5">
                    <label class="mt-3 mb-2" for="package_name">Package Name</label>
                    <input class="form-control" type="text" name="package_name" value="{{ old('package_name') }}"
                        placeholder="com.quick.example" required autofocus>
                </div>
                <div class="form-group col-md-5">
                    <label class="mt-3 mb-2" for="type">Type</label>
                    <input class="form-control" type="text" name="type" value="{{ old('type') }}" required autofocus>
                </div>
                <div class="form-group col-md-5">
                    <label class="mt-3 mb-2" for="description">Description</label>
                    <textarea class="form-control" style="height: 100px;" type="text" name="description"
                        value="" required autofocus>{{ old('description') }}</textarea>
                </div>
                <div class="form-group col-md-5">
                    <label class="mt-3 mb-2" for="repository_url">Repository URL</label>
                    <input class="form-control" type="url" name="repository_url" placeholder="http://git.quick.com/example.git" value="{{ old('repository_url') }}">
                </div>
                <div class="form-group col-md-5">
                    <label class="mt-3 mb-2" for="user_documentation_file">User Documentation</label>
                    <input class="form-control" type="file" name="user_documentation_file"
                        value="{{ old('user_documentation_file') }}">
                </div>
                <div class="form-group col-md-5">
                    <label class="mt-3 mb-2" for="developer_documentation_file">Developer Documentation</label>
                    <input class="form-control" type="file" name="developer_documentation_file"
                        value="{{ old('developer_documentation_file') }}">
                </div>
                <div class="form-group mt-3">
                    <input class="btn btn-primary" type="submit" value="Create">
                </div>
            </form>

            {{-- <div>
                <img src="{{ $data->icon_url }}" width="50" height="50">
            </div>
            <p>Name : {{ $data->name }}</p>
            <p>Package Name : {{ $data->package_name }}</p>
            <p>Type : {{ $data->type }}</p>
            <p>Description : {{ $data->description }}</p>
            <a href="{{ $data->repository_url }}">Git Repository</a>
            <a href="{{ $data->user_documentation_url }}">User Documentation</a>
            <a href="{{ $data->developer_documentation_url }}">Developer Documentation</a>
            <p>Created {{ (new \Carbon\Carbon($data->created_at))->diffForHumans() }}
                {{ $data->created_at == $data->updated_at? '': ' and updated ' . (new \Carbon\Carbon($data->updated_at))->diffForHumans() }}
            </p>

            <h2>Versions</h2>
            <p>List of version for this app</p> --}}
        </div>
    </div>
@endsection
