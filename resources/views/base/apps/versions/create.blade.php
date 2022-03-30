<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Release New Version</h3>
                <p class="text-subtitle text-muted">Release new version
                    for {{ $isClientApp ? 'Client App' : $app->name }} app.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    @yield('breadcrumb')
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row" id="basic-table">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">

                            <div class="row">

                                <div class="row">
                                    @include('base.components.alerts.success')

                                    @include('base.components.alerts.errors')
                                </div>

                                <form action="{{ route($storeVersionRoute, $app->package_name) }}" method="post"
                                      enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    <div class="form-group">
                                        <label for="icon_file">Icon</label>
                                        <input class="form-control" type="file" accept=".jpg, .png, .jpeg"
                                               name="icon_file">
                                    </div>
                                    <div class="form-group">
                                        <label for="apk_file">APK</label>
                                        <input class="form-control" type="file" accept=".apk" name="apk_file" required
                                               autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" type="text" name="description" required
                                                  autofocus>{{ old('description') }}</textarea>
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


@push('script')
    <script>
        autosize(document.querySelector('textarea'))
    </script>
@endpush
