@extends('admin.layouts.admin')

@php($editAppRoute = 'admin.app.edit')
@php($destroyAppRoute = 'admin.app.destroy')
@php($showVersionRoute = 'admin.version.show')
@php($createVersionRoute = 'admin.version.create')
@php($destroyPermissionRoute = 'admin.app.permission.destroy')
@php($storePermissionRoute = 'admin.app.permission.store')
@php($reportDataTablesRoute = 'admin.app.report.datatables')

@php($isAppDeveloper = false)
@php($isAppOwner = true)
@php($isClientApp = $app->isClientApp())

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
        @if ($isClientApp)
            <li class="breadcrumb-item active" aria-current="page">Client App</li>
        @else
            <li class="breadcrumb-item"><a href="{{ route('admin.app.index') }}">Apps</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $app->name }}</li>
        @endif
    </ol>
@endsection

@section('content')
    @include('base.apps.show')
@endsection
