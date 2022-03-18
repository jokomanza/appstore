@extends('layouts.admin')

@php($appsDataDatlesRoute = 'admin.app.datatables')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Apps</li>
    </ol>
@endsection

@section('content')
    @include('apps.base.index')
@endsection
