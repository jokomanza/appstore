@extends('admin.layouts.admin')

@php($isAppDeveloper = false)
@php($isAppOwner = true)
@php($isClientApp = $app->isClientApp())

@php($updateAppRoute = 'admin.app.update')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
        @if ($isClientApp)
            <li class="breadcrumb-item"><a href="{{ route('admin.app.show', config('app.client_package_name')) }}">Client
                    App</a></li>
        @else
            <li class="breadcrumb-item"><a href="{{ route('admin.app.index') }}">Apps</a></li>
            <li class="breadcrumb-item"><a
                        href="{{ route('admin.app.show', $app->package_name) }}">{{ $app->name }}</a>
            </li>
        @endif
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
@endsection

@section('content')
    @include('base.apps.edit')
@endsection
