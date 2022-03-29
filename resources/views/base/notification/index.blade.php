<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>All Notifications</h3>
                <p class="text-subtitle text-muted">Displays all notifications for you.</p>
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

        <div class="card">
            <div class="card-body">

                @forelse($reportNotifications as $notification)
                    <div class="alert alert-danger">
                        <p>{{ $notification['message'] }}</p>
                        <p>Click <strong><a href="{{ $notification['link'] }}">here</a></strong> for more details.</p>
                    </div>
                @empty
                    <div class="alert alert-success">
                        <p>There are currently no notifications for you.</p>
                    </div>
                @endforelse

            </div>
        </div>

    </section>
</div>

@push('script')
    <script>
        $(document).ready(function () {

        });
    </script>
@endpush
