<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Change Password</h3>
                <p class="text-subtitle text-muted">Update your password here.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    @yield('breadcrumb')
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="col-6">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">

                        <div class="row">

                            <div class="row">
                                @include('base.components.alerts.success')

                                @include('base.components.alerts.errors')
                            </div>

                            <form action="{{ route($updateUserPasswordRoute)  }}" method="post"
                                  enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label for="old-password">Old Password</label>
                                    <input class="form-control" type="password" name="old-password" required autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="password">New Password</label>
                                    <input class="form-control" type="password" name="password" required autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmation</label>
                                    <input class="form-control" type="password" name="password_confirmation" required
                                           autofocus>
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
