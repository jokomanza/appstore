@extends('user.layouts.user')

@php($isClientApp = $report->app->package_name == 'com.quick.quickappstore')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Dashboard</a></li>
            @if($isClientApp)
                <li class="breadcrumb-item"><a href="{{ route('user.client.index') }}">Client</a></li>
            @else
                <li class="breadcrumb-item"><a href="{{ route('user.app.index') }}">Apps</a></li>
                <li class="breadcrumb-item"><a
                            href="{{ route('user.app.show', [$report->app->package_name]) }}">{{ $report->app->name }}</a>
                </li>
                <li class="breadcrumb-item"><a
                            href="{{ route('user.report.show', [$report->app->package_name, $report->id]) }}">Report {{ $report->id }}</a>
                </li>
            @endif

            <li class="breadcrumb-item active" aria-current="page">Full Report {{ $report->id }}</li>
        </ol>
    </nav>
@endsection

@section('content')
    @include('base.reports.full')
@endsection