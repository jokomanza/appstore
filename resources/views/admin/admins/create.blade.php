@extends('admin.layouts.admin')

@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Create New Admin</h3>
                    <p class="text-subtitle text-muted">Create a new admin account.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.admin.index') }}">Admins</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create Admin</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="col-12">
                <div class="card">
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

                                <br/>

                                <form action="{{ route('admin.admin.store') }}" method="post"
                                      enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    <div class="form-group col-md-5">
                                        <label class="mt-3 mb-2" for="registration_number">Registration Number</label>
                                        <input class="form-control" type="text" name="registration_number"
                                               value="{{ old('registration_number') }}" placeholder="F0001" required
                                               autofocus>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label class="mt-3 mb-2" for="name">Name</label>
                                        <input class="form-control" type="text" name="name" value="{{ old('name') }}"
                                               placeholder="User" required autofocus>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label class="mt-3 mb-2" for="email">Email</label>
                                        <input class="form-control" type="email" name="email" value="{{ old('email') }}"
                                               placeholder="user@quick.com" required autofocus>
                                    </div>
                                    <div class="form-group mt-3">
                                        <input class="btn btn-primary" type="submit" value="Create">
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
