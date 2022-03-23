@extends('admin.layouts.admin')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Update User</h3>
                <p class="text-subtitle text-muted">Update app data. Note that default icon, name, package
                    name, type and description are required.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Users</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.user.show', $user->registration_number) }}">{{ $user->registration_number }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
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


                                <form action="{{ route('admin.user.update', $user->registration_number)  }}" method="post"
                                      enctype="multipart/form-data">
                                    {{ method_field('PUT') }}
                                    {{ csrf_field() }}

                                    <div class="form-group">
                                        <label for="icon_file">Profile Picture</label>
                                        <div>
                                            <img src="{{asset("images/user1.png") }}"
                                                 width="100" height="100">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input class="form-control" type="text" name="name"
                                               value="{{ old('name') ? old('name') : $user->name }}">
                                    </div>
                                    <div class="form-group">
                                        <label >Registration Number</label>
                                        <input class="form-control" type="text"
                                               value="{{ $user->registration_number }}" readonly="readonly">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input class="form-control" type="email" name="email"
                                               value="{{ old('email') ? old('email') : $user->email }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <input class="form-control" type="text"
                                               value="{{  $user instanceof \App\Admin ? 'Admin' : 'User' }}"
                                               readonly="readonly">
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



@endsection
