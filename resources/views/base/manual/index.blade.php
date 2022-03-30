<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>User Manual</h3>
                <p class="text-subtitle text-muted">User Manual for Quick App Store Systems.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    @yield('breadcrumb')
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">

                @if(isset($userManual))

                    <iframe src="{{ asset('/storage/user_manual.pdf') }}" width="100%"
                            height="900px"></iframe>

                @else
                    <p>Currently there is no user manual</p>
                @endif

            </div>
        </div>

    </section>
</div>