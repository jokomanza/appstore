@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <h2>Report #{{ $data->report_id }}</h2>

            {{-- <pre>{{ json_encode($data) }}</pre> --}}

            {{-- @each('report.value', $data->getAttributes(), 'data', 'data' ) --}}

            @include('report.recursive', ['data' => (array) $data, 'n' => 0])
        </div>
    </div>
@endsection
