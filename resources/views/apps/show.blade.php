@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-5">App #{{ $data->id }}</h1>

        <div class="row">
            <div class="card shadow-2-strong col-md-4" style="border-radius: 15px;">
                <div>
                    <img src="{{ str_contains($data->icon_url, 'http') ? $data->icon_url : asset("storage/$data->icon_url") }}"
                        width="50" height="50">
                </div>
                <p>Name : {{ $data->name }}</p>
                <p>Package Name : {{ $data->package_name }}</p>
                <p>Type : {{ $data->type }}</p>
                <p>Description : {{ $data->description }}</p>
                <a href="{{ $data->repository_url }}">Git Repository</a>
                <a href="{{ asset('/storage/' . $data->user_documentation_url) }}">User Documentation</a>
                <a href="{{ asset('/storage/' . $data->developer_documentation_url) }}">Developer Documentation</a>
                <p>Created {{ (new \Carbon\Carbon($data->created_at))->diffForHumans() }}
                    {{ $data->created_at == $data->updated_at? '': ' and updated ' . (new \Carbon\Carbon($data->updated_at))->diffForHumans() }}
                </p>

                <br><br>
                <div>
                    <a class="btn btn-primary" href="{{ url("app/$data->id/edit") }}">Update</a>
                </div>
                <form method="POST" action="{{ url("app/$data->id") }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <input type="submit" class="btn btn-danger" value="Delete">
                </form>


            </div>
            <div class="col-md-8">
                <h2>Versions</h2>
                <p>List of version for this app</p>
            </div>
        </div>


    </div>

    <script>
        $('.delete-application').click(function(e) {
            // e.preventDefault() // Don't post the form, unless confirmed

            bootbox.confirm({
                size: "small",
                message: "Are you sure?",
                callback: function(result) {
                    /* result is a boolean; true = OK, false = Cancel*/
                }
            })

            // if (confirm('Are you sure?')) {
            //     // Post the form
            //     $(e.target).closest('form').submit() // Post the surrounding form
            // }
        });
    </script>
@endsection
