@extends('user.layouts.user')

@php($storeVersionRoute = 'user.version.store')

@section('content')
    @include('base.apps.versions.index')
@endsection
