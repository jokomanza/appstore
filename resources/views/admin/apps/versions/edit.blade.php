@extends('admin.layouts.admin')

@php($storeVersionRoute = 'admin.version.store')

@section('content')
    @include('base.apps.versions.edit')
@endsection
