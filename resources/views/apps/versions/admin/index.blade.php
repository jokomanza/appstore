@extends('layouts.admin')

@php($storeVersionRoute = 'admin.version.store')

@section('content')
    @include('apps.versions.base.index')
@endsection
