@extends('admin.layouts.admin')

@php($destroyVersionRoute = 'admin.version.destroy')
@php($storeVersionRoute = 'admin.version.store')

@php($isClientApp = $app->package_name == 'com.quick.quickappstore')

@if ($isClientApp)
    @php($editVersionRoute = 'admin.client.version.edit')
@else
    @php($editVersionRoute = 'admin.version.edit')
@endif

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
        @if ($isClientApp)
            <li class="breadcrumb-item"><a href="{{ route('admin.client.show') }}">Client App</a></li>
        @else
            <li class="breadcrumb-item"><a href="{{ route('admin.app.index') }}">Apps</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.app.show', $app->package_name) }}">{{ $app->name }}</a>
            </li>
        @endif
        <li class="breadcrumb-item active" aria-current="page">Version {{ $version->version_name }}</li>
    </ol>
@endsection

@section('content')
    @include('base.apps.versions.show')
@endsection
