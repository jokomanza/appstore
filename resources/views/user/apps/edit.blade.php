@extends('user.layouts.user')

@php($isClientApp = $app->package_name == 'com.quick.quickappstore')

@if ($isClientApp)
    @php($updateAppRoute = 'user.client.update')
@else
    @php($updateAppRoute = 'user.app.update')
@endif

@section('breadcrump')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Dashboard</a></li>
        @if ($isClientApp)
            <li class="breadcrumb-item"><a href="{{ route('user.client.show') }}">Client App</a></li>
        @else
            <li class="breadcrumb-item"><a href="{{ route('user.app.index') }}">Apps</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.app.show', $app->package_name) }}">{{ $app->name }}</a></li>
        @endif
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
@endsection

@section('content')
    @include('base.apps.edit')
@endsection
