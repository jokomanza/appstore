@extends('user.layouts.user')

@php($editUserRoute = 'user.profile.edit')
@php($changeUserPasswordRoute = 'user.profile.password.edit')
@php($destroyUserRoute = 'user.profile.destroy')
@php($showAppRoute = 'user.app.show')

@php($isUser = true)

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">My Profile</li>
    </ol>
@endsection

@section('content')
    @include('base.profile.show')
@endsection