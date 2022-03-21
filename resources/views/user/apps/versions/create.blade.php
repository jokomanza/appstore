@extends('admin.layouts.admin')

@php($storeVersionRoute = 'admin.version.store')
@php($isClientApp = $app->package_name == 'com.quick.quickappstore')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
        @if ($isClientApp)
            <li class="breadcrumb-item"><a href="{{ route('admin.client.show') }}">Client App</a></li>
        @else
            <li class="breadcrumb-item"><a href="{{ route('admin.app.index') }}">Apps</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.app.show', $app->id) }}">{{ $app->name }}</a></li>
        @endif
        <li class="breadcrumb-item active" aria-current="page">Create new Version</li>
    </ol>
@endsection

@section('content')
    @include('base.apps.versions.create')
@endsection
