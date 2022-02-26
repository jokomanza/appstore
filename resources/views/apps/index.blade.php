@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Apps Menu</h1>
        <a class="btn btn-secondary" href="{{ route('app.create') }}">Create new</a>
        @foreach ($data->items() as $row)
            <div class="row">
                <div>
                    <img src="{{ str_contains($row->icon_url, 'http') ? $row->icon_url : asset("storage/$row->icon_url") }}" width="50" height="50">
                </div>
                <p>Name : {{ $row->name }}</p>
                <p>Package Name : {{ $row->package_name }}</p>
                <div>
                    <a class="btn btn-primary" href="{{ url("app/$row->id/") }}">View</a>
                </div>
                <br>
                <br>
            </div>
        @endforeach

        <div class="d-flex justify-content-center">
            {!! $data->links() !!}
        </div>
    </div>
@endsection
