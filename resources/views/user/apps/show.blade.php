@extends('user.layouts.user')

@php($editAppRoute = 'user.app.edit')
@php($destroyAppRoute = 'user.app.destroy')
@php($showVersionRoute = 'user.version.show')
@php($showClientVersionRoute = 'user.client.version.show')
@php($createVersionRoute = 'user.version.create')
@php($destroyPermissionRoute = 'user.app.developer.destroy')
@php($storePermissionRoute = 'user.app.developer.store')
@php($reportDataTablesRoute = 'user.app.report.datatables')

@php($createClientVersionRoute = 'user.client.version.create')

@php($isClientApp = $app->package_name == 'com.quick.quickappstore')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Dashboard</a></li>
        @if ($isClientApp)
            <li class="breadcrumb-item active" aria-current="page">Client App</li>
        @else
            <li class="breadcrumb-item"><a href="{{ route('user.app.index') }}">Apps</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $app->name }}</li>
        @endif
    </ol>
@endsection

@section('content')
    @include('base.apps.show')
@endsection
