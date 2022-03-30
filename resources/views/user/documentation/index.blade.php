@extends('user.layouts.user')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Documentations</li>
    </ol>
@endsection

@section('content')
    @include('base.documentation.index')
@endsection
