@extends('user.layouts.user')

@php($editAppRoute = 'user.app.edit')
@php($destroyAppRoute = 'user.app.destroy')
@php($showVersionRoute = 'user.version.show')
@php($createVersionRoute = 'user.version.create')
@php($destroyPermissionRoute = 'user.app.permission.destroy')
@php($storePermissionRoute = 'user.app.permission.store')
@php($reportDataTablesRoute = 'user.app.report.datatables')

@php($isClientApp = $app->isClientApp())

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
