<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Report #{{ $report->id }}</h3>
                <p class="text-subtitle text-muted">Complete Report detail.</p>
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
            <div class="card-header">
                <h4 class="card-title">Report #{{ $report->report_id }}</h4>
            </div>
            <div class="card-content">
                <div class="card-body">

                    @include('base.reports.components.recursive', ['data' => (array) $report, 'n' => 0])

                </div>
            </div>
        </div>
    </section>
</div>