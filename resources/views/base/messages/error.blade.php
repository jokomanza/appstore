@extends('base.layouts.app')

@section('error')

    <div class="error-page container vh-100">
        <div class="row col-md-8 col-12 offset-md-2 h-100 align-items-center">
            <div class="text-center">
                <h1 class="error-title">System Error</h1>
                <p class="fs-5 text-gray-600">{{ isset($message) ? $message : "The website is currently unavailable. Try again later or contact the
                    developer."}}</p>

                <a href="{{ route('welcome')}}" class="btn btn-lg btn-outline-primary mt-3">Go Home</a>
            </div>
        </div>
    </div>

@endsection