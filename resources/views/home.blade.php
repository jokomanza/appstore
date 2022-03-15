@extends('layouts.app')

@section('content')
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <h3>Quick App Store Statistics</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-9">
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card" onclick="location.href='{{ route('app.index') }}';"
                            style="cursor: pointer;">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon purple">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Apps</h6>
                                        <h6 class="font-extrabold mb-0">{{ $appsCount }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card" onclick="location.href='{{ route('user.index') }}';"
                            style="cursor: pointer;">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon blue">
                                            <i class="iconly-boldProfile"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Users</h6>
                                        <h6 class="font-extrabold mb-0">{{ $developersCount }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="iconly-boldAdd-User"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Admins</h6>
                                        <h6 class="font-extrabold mb-0">{{ $adminsCount }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon red">
                                            <i class="iconly-boldBookmark"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Error Reports</h6>
                                        <h6 class="font-extrabold mb-0">{{ $errorsCount }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card">
                    <div class="card-body py-4 px-5">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl">
                                {{-- <img src="assets/images/faces/1.jpg" alt="Face 1"> --}}
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="ms-3 name">
                                <h5 class="font-bold">{{ Auth::user()->name }}</h5>
                                <h6 class="text-muted mb-0">{{ Auth::user()->registration_number }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Recent Apps</h4>
                    </div>
                    <div class="card-content pb-4">

                        @foreach ($recentApps as $app)
                            <div onclick="location.href='{{ route('app.show', $app->id) }}';" style="cursor: pointer;"
                                class="recent-message d-flex px-4 py-3 ripple">
                                <div class="avatar avatar-lg">
                                    <img
                                        src="{{ str_contains($app->icon_url, 'http') ? $app->icon_url : asset("storage/$app->icon_url") }}">
                                </div>
                                <div class="name ms-4">
                                    <h5 class="mb-1">{{ $app->name }}</h5>
                                    <h6 class="text-muted mb-0">{{ $app->type }}</h6>
                                </div>
                            </div>
                        @endforeach

                        <div class="px-4">
                            <a href="{{ route('app.index') }}"
                                class='btn btn-block btn-xl btn-light-primary font-bold mt-3'>
                                Show more</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
