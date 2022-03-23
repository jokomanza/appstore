@extends('admin.layouts.admin')

@php($editUserRoute = 'admin.profile.edit')
@php($changeUserPasswordRoute = 'admin.profile.password.edit')
@php($destroyUserRoute = 'admin.profile.destroy')
@php($showAppRoute = 'admin.app.show')
@php($showClientAppRoute = 'admin.client.show')
@php($isUser = false)

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">My Profile</li>
    </ol>
@endsection

@section('content')
    @include('base.profile.show')
@endsection