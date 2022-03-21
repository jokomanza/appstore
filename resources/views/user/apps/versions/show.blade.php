@extends('user.layouts.user')

@php($destroyVersionRoute = 'user.version.destroy')
@php($storeVersionRoute = 'user.version.store')

@php($isClientApp = $app->package_name == 'com.quick.quickappstore')

@if ($isClientApp)
    @php($editVersionRoute = 'user.client.version.edit')
@else
    @php($editVersionRoute = 'user.version.edit')
@endif

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Dashboard</a></li>
        @if ($isClientApp)
            <li class="breadcrumb-item active" aria-current="page"><a href="{{route('user.client.show')}}">Client
                    App</a></li>
        @else
            <li class="breadcrumb-item"><a href="{{ route('user.app.index') }}">Apps</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.app.show', $app->package_name) }}">{{$app->name}}</a>
            </li>
        @endif
        <li class="breadcrumb-item active" aria-current="page">Version {{$version->versionName}}</li>
    </ol>
@endsection
@section('content')
    @include('base.apps.versions.show')
@endsection
