@extends('admin.layouts.admin')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">User Manual</li>
    </ol>
@endsection

@section('content')
    @include('base.manual.index')
@endsection
