@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create a new Version</h1>
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
            <!-- form validasi -->
            <form action="{{ route('app.store') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="icon_file">Default Icon</label>
                    <input class="form-control" type="file" name="icon_file" value="{{ old('icon_file') }}">
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" type="text" name="name" value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label for="package_name">Package Name</label>
                    <input class="form-control" type="text" name="package_name" value="{{ old('package_name') }}">
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <input class="form-control" type="text" name="type" value="{{ old('type') }}">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input class="form-control" type="text" name="description" value="{{ old('description') }}">
                </div>
                <div class="form-group">
                    <label for="repository_url">Repository URL</label>
                    <input class="form-control" type="text" name="repository_url" value="{{ old('repository_url') }}">
                </div>
                <div class="form-group">
                    <label for="user_documentation_file">User Documentation</label>
                    <input class="form-control" type="file" name="user_documentation_file" value="{{ old('user_documentation_file') }}">
                </div>
                <div class="form-group">
                    <label for="developer_documentation_file">Developer Documentation</label>
                    <input class="form-control" type="file" name="developer_documentation_file" value="{{ old('developer_documentation_file') }}">
                </div>
                <div class="form-group">
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
