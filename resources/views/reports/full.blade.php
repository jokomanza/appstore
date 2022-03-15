@extends('layouts.app')

@section('content')

<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Report #{{ $report->id }}</h3>
                <p class="text-subtitle text-muted">Complete Report detail.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('report.index') }}">Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Report {{ $report->report_id }}</li>
                    </ol>
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

                    @include('reports.components.recursive', ['data' => (array) $report, 'n' => 0])

                </div>
            </div>
        </div>
    </section>
</div>
@endsection
