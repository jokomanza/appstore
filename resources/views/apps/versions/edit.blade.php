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
                <h3>Update Version</h3>
                <p class="text-subtitle text-muted">Update version data. Note that you only can edit description.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('app.index') }}">Apps</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('app.show', $app->id) }}">{{ $app->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Version {{ $version->version_name }}</li>
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

                            <form action="{{ route('version.update', [$app->id, $version->id]) }}" method="post" enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" name="description">{{ old('description') ? old('description') : $version->description }}</textarea>
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
