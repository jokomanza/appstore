@push('head')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
@endpush

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>My Profile</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    @yield('breadcrumb')
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
                                   href="{{ route($editUserRoute) }}"
                                   class="btn btn-primary">Edit</a>

                                <a clas="col-1"
                                   href="{{ route($changeUserPasswordRoute) }}"
                                   class="btn btn-secondary">Change Password</a>

                                <form class="col-1" method="POST"
                                      action="{{ route($destroyUserRoute) }}">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}

                                    <input type="submit" class="delete-application btn btn-danger"
                                           value="Delete Account">
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            @if($isUser)
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
                                                           href="{{ isCLientApp($value->app) ? route($showClientAppRoute) : route($showAppRoute, [$value->app->package_name]) }}">View</a>
                                                    </div>
                                                </td>
                                            </tr>

                                        @empty
                                            <tr>
                                                <td colspan="5">
                                                    <p class="text-center">You don't have any app yet</p>
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
            @endif
        </div>
    </section>
</div>

@push('script')
    <script>
        $(document).ready(function () {
            $("#reports").dataTable().fnDestroy();

            $('.delete-application').click(function (e) {
                e.preventDefault() // Don't post the form, unless confirmed

                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover your account!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            swal({
                                title: "Are you really sure?",
                                text: "Once again, you will NOT BE ABLE TO RECOVER YOUR ACCOUNT!",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                            })
                                .then((willDelete) => {
                                    if (willDelete) {
                                        $(e.target).closest('form').submit() // Post the surrounding form
                                    }
                                });
                        }
                    });
            });
            $('.form-select').select2()
        })
    </script>
@endpush
