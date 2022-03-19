@extends('layouts.admin')

@php($isAppDeveloper = false)
@php($isAppOwner = true)
@php($isClientApp = $app->package_name == 'com.quick.quickappstore')

@if ($isClientApp)
    @php($updateAppRoute = 'admin.client.update')
@else
    @php($updateAppRoute = 'admin.app.update')
@endif

@section('breadcrump')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
        @if ($isClientApp)
            <li class="breadcrumb-item"><a href="{{ route('admin.client.show') }}">Client App</a></li>
        @else
            <li class="breadcrumb-item"><a href="{{ route('admin.app.index') }}">Apps</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.app.show', $app->id) }}">{{ $app->name }}</a></li>
        @endif
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
@endsection

@section('content')
    @include('apps.base.edit')
@endsection
