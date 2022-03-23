@extends('admin.layouts.admin')

@php($updateUserPasswordRoute = 'admin.profile.password.update')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.profile.show') }}">My Profile</a></li>
        <li class="breadcrumb-item active" aria-current="page">Change Password</li>
    </ol>
@endsection

@section('content')
    @include('base.profile.password.edit')
@endsection