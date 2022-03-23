@extends('admin.layouts.admin')

@php($updateUserRoute = 'admin.profile.update')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.profile.show') }}">My Profile</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
@endsection

@section('content')
    @include('base.profile.edit')
@endsection