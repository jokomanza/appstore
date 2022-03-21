@extends('user.layouts.user')

@php($appsDataTablesRoute = 'user.app.datatables')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Apps</li>
    </ol>
@endsection

@section('content')
    @include('base.apps.index')
@endsection
