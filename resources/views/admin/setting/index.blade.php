@extends('admin.layouts.admin')

@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Quick App Store Settings</h3>
                    <p class="text-subtitle text-muted">System settings.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Settings</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">

            <div class="row">
                @include('base.components.alerts.success')

                @include('base.components.alerts.errors')
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">General</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">


                                <h5>User Manual</h5>
                                <form action="{{ route('admin.setting.manual.store') }}" method="post"
                                      enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    @if(isset($userManual))
                                        <p>Current User Manual : <a href="{{ url($userManual) }}">Open</a></p>
                                    @else
                                        <p>Currently there is no user manual</p>
                                    @endif

                                    <div class="form-group col-md-8">
                                        <label class="mt-3 mb-2" for="user_manual">User
                                            Manual</label>
                                        <input class="form-control" type="file" name="user_manual"
                                               required accept="application/pdf">
                                    </div>

                                    <div class="form-group">
                                        <input class="btn btn-primary" type="submit"
                                               value="{{ isset($userManual) ? 'Change' : 'Upload'}}">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Security</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection