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
                    <h3>Create New Version</h3>
                    <p class="text-subtitle text-muted">Release new version for {{ $app->name }} app.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('app.index') }}">Apps</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('app.show', $app->id) }}">{{ $app->name }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create new Version</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row" id="basic-table">
                <div class="col-md-6">
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
                                    
                                    <form action="{{ route('version.store', $app->id) }}" method="post" enctype="multipart/form-data">
                                        {{ csrf_field() }}

                                        <div class="form-group">
                                            <label for="icon_file">Icon</label>
                                            <input class="form-control" type="file"  accept=".jpg, .png, .jpeg" name="icon_file" autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label for="apk_file">APK</label>
                                            <input class="form-control" type="file" accept=".apk" name="apk_file" autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" type="text" name="description"
                                            required autofocus>{{ old('description') }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <input class="btn btn-primary" type="submit" value="Create">
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


@endsection
