@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Apps Menu</h1>
        <a href="{{ route('app.create') }}">Create new</a>
        @foreach ($data->items() as $row)
            <div class="row">
                <div>
                    <img src="{{ $row->icon_url }}" width="50" height="50">
                </div>
                <p>Name : {{ $row->name }}</p>
                <p>Package Name : {{ $row->package_name }}</p>
                <div>
                    <a href="{{ url("app/$row->id/") }}">View</a>
                </div>
                <br>
                <br>
            </div>
        @endforeach
        {{ $data }}
    </div>
@endsection
