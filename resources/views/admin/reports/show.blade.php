@extends('admin.layouts.admin')

@php($fullReportRoute = 'admin.report.show.full')

@php($isClientApp = $report->app->package_name == 'com.quick.quickappstore')

@if($isClientApp)
    @php($destroyReportRoute = 'admin.client.report.destroy')
@else
    @php($destroyReportRoute = 'admin.report.destroy')
@endif

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

        @if($isClientApp)
            <li class="breadcrumb-item"><a href="{{ route('admin.client.show') }}">Client</a></li>
        @else
            <li class="breadcrumb-item"><a href="{{ route('admin.app.index') }}">Apps</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.app.show', [$report->app->package_name]) }}">{{ $report->app->name }}</a></li>
        @endif

        <li class="breadcrumb-item active" aria-current="page">Report {{ $report->id }}</li>
    </ol>
@endsection

@section('content')
    @include('base.reports.show')
@endsection