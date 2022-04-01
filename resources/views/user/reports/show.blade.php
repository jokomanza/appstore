@extends('user.layouts.user')

@php($fullReportRoute = 'user.report.show.full')

@php($isClientApp = $report->app->package_name == 'com.quick.quickappstore')

@php($destroyReportRoute = 'user.report.destroy')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Dashboard</a></li>

        @if($isClientApp)
            <li class="breadcrumb-item"><a
                        href="{{ route('user.app.show', config('app.client_package_name')) }}">Client</a>
            </li>
        @else
            <li class="breadcrumb-item"><a href="{{ route('user.app.index') }}">Apps</a></li>
            <li class="breadcrumb-item"><a
                        href="{{ route('user.app.show', [$report->app->package_name]) }}">{{ $report->app->name }}</a>
            </li>
        @endif

        <li class="breadcrumb-item active" aria-current="page">Report {{ $report->id }}</li>
    </ol>
@endsection

@section('content')
    @include('base.reports.show')
@endsection