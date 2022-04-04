@extends('admin.layouts.admin')

@push('head')
    <link href="{{ asset('css/bootstrap4-toggle.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/bootstrap4-toggle.min.js') }}"></script>
@endpush

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
                                        <label class="mt-3 mb-2" for="document">User
                                            Manual</label>
                                        <input class="form-control" type="file" name="document"
                                               required accept="app/pdf">
                                    </div>

                                    <div class="form-group">
                                        <input class="btn btn-primary" type="submit"
                                               value="{{ isset($userManual) ? 'Change' : 'Upload'}}">
                                    </div>
                                </form>

                                <br>

                                <h5>Android Development Standard</h5>
                                <form action="{{ route('admin.setting.development.standard.store') }}" method="post"
                                      enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    @if(isset($devStandard))
                                        <p>Current Development Standard : <a href="{{ url($devStandard) }}">Open</a></p>
                                    @else
                                        <p>Currently there is no development standard</p>
                                    @endif

                                    <div class="form-group col-md-8">
                                        <label class="mt-3 mb-2" for="document">Development Standard</label>
                                        <input class="form-control" type="file" name="document"
                                               required accept="app/pdf">
                                    </div>

                                    <div class="form-group">
                                        <input class="btn btn-primary" type="submit"
                                               value="{{ isset($devStandard) ? 'Change' : 'Upload'}}">
                                    </div>
                                </form>

                                <br>

                                <h5>Android Development Guide</h5>
                                <form action="{{ route('admin.setting.development.guide.store') }}" method="post"
                                      enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    @if(isset($devGuide))
                                        <p>Current Development Guide : <a href="{{ url($devGuide) }}">Open</a></p>
                                    @else
                                        <p>Currently there is no development guide</p>
                                    @endif

                                    <div class="form-group col-md-8">
                                        <label class="mt-3 mb-2" for="document">Development Guide</label>
                                        <input class="form-control" type="file" name="document"
                                               required accept="app/pdf">
                                    </div>

                                    <div class="form-group">
                                        <input class="btn btn-primary" type="submit"
                                               value="{{ isset($devGuide) ? 'Change' : 'Upload'}}">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Notification</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">

                                <p>Send email notification : </p>
                                <input id="notification-toggle" type="checkbox"
                                       {{ $emailNotification ? 'checked' : '' }}
                                       data-toggle="toggle" data-onstyle="success">

                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Security</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">

                                <p>There are currently no settings for security.</p>

                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Logs</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">

                                <p>Manage all the logs in this application.</p>
                                <a class="btn btn-primary" href="{{ route('log-viewer::dashboard') }}">See Logs</a>

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
        $(document).ready(function () {
            $('#notification-toggle').change(function () {
                const value = $(this).is(':checked');

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: '{{ route('admin.setting.notification.toggle') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        value: value
                    },
                    success: function (data) {
                        console.log(data)
                    }
                });
            });
        });

    </script>

@endpush
