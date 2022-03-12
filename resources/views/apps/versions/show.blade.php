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
                <h3>{{ $app->name }} v{{ $version->version_name }}</h3>
                <p class="text-subtitle text-muted">{{ $app->name }}'s version detail.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('app.index') }}">Apps</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('app.show', $app->id) }}">{{ $app->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Version {{ $version->version_name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row" id="basic-table">
            <div class="col-md-8">
                <div class="card">
                    {{-- <div class="card-header">
                            <h4 class="card-title">Detail App</h4>
                        </div> --}}
                    <div class="card-content">
                        <div class="card-body">

                            <div class="mb-4">
                                <img src="{{ str_contains($version->icon_url, 'http') ? $version->icon_url : asset("storage/$version->icon_url") }}" width="100" height="100">
                            </div>
                            <p class="text">Version Code : {{ $version->version_code }}</p>
                            <p>Version Name : {{ $version->version_name }}</p>
                            <p>Min SDK Level : {{ $version->min_sdk_level }}</p>
                            <p>Target SDK Level : {{ $version->target_sdk_level }}</p>
                            <p>Description : {!! nl2br(e($version->description)) !!}</p>
                            <p>Downloads : {{ $version->downloads }}</p>
                            <p>Installs : {{ $version->installs }}</p>
                            <br>
                            <p>File : <a href="{{ asset("storage/$version->apk_file_url") }}">Download</a> {{ "($version->apk_file_size KB)" }}</p>
                            <p>Released {{ (new \Carbon\Carbon($version->created_at))->diffForHumans() }}
                                {{ $version->created_at == $version->updated_at? '': ' and updated ' . (new \Carbon\Carbon($version->updated_at))->diffForHumans() }}
                            </p>

                            <br><br>

                            <div class="buttons">
                                <a class="btn btn-primary" href="{{ route('version.edit', [$app->id, $version->id]) }}">Edit</a>
                                <form class="col-1" method="POST" action="{{ route('version.destroy', [$app->id, $version->id]) }}">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}

                                    <input type="submit" class="delete-version btn btn-danger" value="Delete">
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('.delete-version').click(function(e) {
            e.preventDefault() // Don't post the form, unless confirmed

            swal({
                    title: "Are you sure?"
                    , text: "Once deleted, you will not be able to recover this version!"
                    , icon: "warning"
                    , buttons: true
                    , dangerMode: true
                , })
                .then((willDelete) => {
                    if (willDelete) {
                        $(e.target).closest('form').submit() // Post the surrounding form
                    } else {

                    }
                });
        });
    })

</script>
@endsection
