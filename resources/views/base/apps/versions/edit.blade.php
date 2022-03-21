<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Update Version</h3>
                <p class="text-subtitle text-muted">Update version data. Note that you only can edit description.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    @yield('breadcrumb')
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

                            <form action="{{ route($updateVersionRoute, $isClientApp ? $version->version_name : [$app->package_name, $version->version_name]) }}" method="post"
                                enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control"
                                        name="description">{{ old('description') ? old('description') : $version->description }}</textarea>
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


@push('script')
    <script>
        autosize(document.querySelector('textarea'))
    </script>
@endpush
