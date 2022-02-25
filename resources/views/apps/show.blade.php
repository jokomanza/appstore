@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>App #{{ $data->id }}</h1>
        <div class="row">
            <div>
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
            <p>List of version for this app</p>
        </div>
    </div>
@endsection
