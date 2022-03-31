@extends('admin.layouts.admin')

@push('head')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
@endpush


@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>User #{{ $user->registration_number }}</h3>
                    <p class="text-subtitle text-muted">User detail.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Users</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $user->registration_number }}</li>
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
                            <h4 class="card-title">Detail</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">

                                <div class="mb-4">
                                    <img src="{{ asset('images/user1.png') }}"
                                         width="100" height="100">
                                </div>
                                <div class="col">
                                    <p class="text">Name : {{ $user->name }}</p>
                                </div>
                                <p>Registration Number : {{ $user->registration_number }}</p>
                                <p>Email : {{ $user->email }}</p>
                                <p>Status : {{ $user instanceof \App\Admin ? 'Admin' : 'User'}}</p>

                                <br><br>

                                <div class="buttons">
                                    <a clas="col-1"
                                       href="{{ route('admin.user.edit', $user->registration_number) }}"
                                       class="btn btn-primary">Edit</a>

                                    <form class="col-1" method="POST"
                                          action="{{ route('admin.user.password.reset', $user->registration_number) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('PUT') }}

                                        <input type="submit" class="btn btn-secondary" value="Reset Password">
                                    </form>

                                    <form class="col-1" method="POST"
                                          action="{{ route('admin.user.destroy', $user->registration_number) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <input type="submit" class="delete-user btn btn-danger" value="Delete">
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Apps</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-lg">
                                        <thead>
                                        <tr>
                                            <th>Icon</th>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Last Updated</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($apps as $key => $value)
                                            <tr>
                                                <td>
                                                    <img src="{{ asset("storage/". $value->app->icon_url) }}"
                                                         width="50" height="50"></td>
                                                <td class="text-bold-500">{{ $value->app->name }}</td>
                                                <td>{{ $value->type }}</td>
                                                <td>{{ (new \Carbon\Carbon($value->app->updated_at))->diffForHumans() }}</td>
                                                <td class="text-bold-500">
                                                    <div class="buttons">
                                                        <a class="btn btn-primary"
                                                           href="{{ isClientApp($value->app) ? route('admin.client.show') : route('admin.app.show', [$value->app->package_name]) }}">View</a>
                                                    </div>
                                                </td>
                                            </tr>

                                        @empty
                                            <tr>
                                                <td colspan="5">
                                                    <p class="text-center">This user don't have any app yet</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@push('script')

    <script>
        $('.delete-user').click(function (e) {
            e.preventDefault() // Don't post the form, unless confirmed

            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover his/her account!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        swal({
                            title: "Are you really sure?",
                            text: "Once again, you will NOT BE ABLE TO RECOVER HIS/HER ACCOUNT!",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                            .then((willDelete) => {
                                if (willDelete) {
                                    $(e.target).closest('form').submit() // Post the surrounding form
                                } else {

                                }
                            });
                    } else {

                    }
                });
        })
    </script>
@endpush
