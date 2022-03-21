@extends('user.layouts.user')

@php($storeRoute = 'user.app.store')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('user.app.index') }}">Apps</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create new App</li>
    </ol>
@endsection

@section('content')
    @include('base.apps.create')
@endsection
