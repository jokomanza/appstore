@extends('user.layouts.user')

@php($storeVersionRoute = 'user.version.store')

@php($isClientApp = $app->package_name == 'com.quick.quickappstore')

@if ($isClientApp)
    @php($updateVersionRoute = 'user.client.version.update')
@else
    @php($updateVersionRoute = 'user.version.update')
@endif

@section('content')
    @include('base.apps.versions.edit')
@endsection
