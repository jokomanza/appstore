@extends('admin.layouts.admin')

@php($isClientApp = $report->app->package_name == 'com.quick.quickappstore')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
            @if($isClientApp)
                <li class="breadcrumb-item"><a href="{{ route('admin.app.show', config('app.client_package_name')) }}">Client</a>
                </li>
            @else
                <li class="breadcrumb-item"><a href="{{ route('admin.app.index') }}">Apps</a></li>
                <li class="breadcrumb-item"><a
                            href="{{ route('admin.app.show', [$report->app->package_name]) }}">{{ $report->app->name }}</a>
                </li>
                <li class="breadcrumb-item"><a
                            href="{{ route('admin.report.show', [$report->app->package_name, $report->id]) }}">Report {{ $report->id }}</a>
                </li>
            @endif

            <li class="breadcrumb-item active" aria-current="page">Full Report {{ $report->id }}</li>
        </ol>
    </nav>
@endsection

@section('content')
    @include('base.reports.full')
@endsection