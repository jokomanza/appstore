@extends('admin.layouts.admin')

@php($storeVersionRoute = 'admin.version.store')

@php($isClientApp = $app->package_name == 'com.quick.quickappstore')

@if ($isClientApp)
    @php($updateVersionRoute = 'admin.client.version.update')
@else
    @php($updateVersionRoute = 'admin.version.update')
@endif

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.app.index') }}">Apps</a></li>
        <li class="breadcrumb-item"><a
                    href="{{ route('admin.app.show', $app->package_name) }}">{{ $app->name }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Version
            {{ $version->version_name }}</li>
    </ol>
@endsection

@section('content')
    @include('base.apps.versions.edit')
@endsection
