@extends('admin.layouts.admin')

@php($storeRoute = 'admin.app.store')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.app.index') }}">Apps</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create new App</li>
    </ol>
@endsection

@section('content')
    @include('base.apps.create')
@endsection
