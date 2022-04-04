@extends('user.layouts.user')

@php($destroyVersionRoute = 'user.version.destroy')
@php($storeVersionRoute = 'user.version.store')

@php($isClientApp = $app->isClientApp())

@php($editVersionRoute = 'user.version.edit')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Dashboard</a></li>
        @if ($isClientApp)
            <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{route('user.app.show', config('app.client_package_name'))}}">Client
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
