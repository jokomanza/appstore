@extends('user.layouts.user')

@php($storeVersionRoute = 'user.version.store')

@php($isClientApp = $app->package_name == 'com.quick.quickappstore')

@if ($isClientApp)
    @php($updateVersionRoute = 'user.client.version.update')
@else
    @php($updateVersionRoute = 'user.version.update')
@endif

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('user.app.index') }}">Apps</a></li>
        <li class="breadcrumb-item"><a
                    href="{{ route('user.app.show', $app->package_name) }}">{{ $app->name }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Version
            {{ $version->version_name }}</li>
    </ol>
@endsection

@section('content')
    @include('base.apps.versions.edit')
@endsection
