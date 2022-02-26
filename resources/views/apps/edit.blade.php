@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit app</h1>
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
            <form action="{{ url("app/$data->id") }}" method="post" enctype="multipart/form-data">
                {{ method_field('PUT') }}
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="icon_file">Default Icon</label>
                    <div>
                        <img src="{{ str_contains($data->icon_url, 'http') ? $data->icon_url : asset("storage/$data->icon_url") }}"
                            width="50" height="50">
                    </div>
                    <input class="form-control" type="file" name="icon_file" value="{{ old('icon_file') }}">
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" type="text" name="name"
                        value="{{ old('name') ? old('name') : $data->name }}">
                </div>
                <div class="form-group">
                    <label for="package_name">Package Name</label>
                    <input class="form-control" type="text" name="package_name"
                        value="{{ old('package_name') ? old('package_name') : $data->package_name }}">
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <input class="form-control" type="text" name="type"
                        value="{{ old('type') ? old('type') : $data->type }}">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input class="form-control" type="text" name="description"
                        value="{{ old('description') ? old('description') : $data->description }}">
                </div>
                <div class="form-group">
                    <label for="repository_url">Repository URL</label>
                    <input class="form-control" type="text" name="repository_url"
                        value="{{ old('repository_url') ? old('repository_url') : $data->repository_url }}">
                </div>
                <div class="form-group">
                    <label for="user_documentation_file">User Documentation</label>
                    @if ($data->user_documentation_url)
                        <a
                            href="{{ str_contains($data->user_documentation_url, 'http')? $data->user_documentation_url : asset('/storage/' . $data->user_documentation_url) }}">Preveriously
                            user documentation</a>
                    @endif
                    <input class="form-control" type="file" name="user_documentation_file"
                        value="{{ old('user_documentation_file') }}">
                </div>
                <div class="form-group">
                    <label for="developer_documentation_file">Developer Documentation</label>
                    @if ($data->developer_documentation_url)
                        <a
                            href="{{ str_contains($data->developer_documentation_url, 'http')? $data->developer_documentation_url: asset('/storage/' . $data->developer_documentation_url) }}">Preveriously
                            developer documentation</a>
                    @endif
                    <input class="form-control" type="file" name="developer_documentation_file"
                        value="{{ old('developer_documentation_file') }}">
                </div>
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" value="Update">
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
