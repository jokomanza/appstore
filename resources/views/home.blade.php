@extends('layouts.app')

@section('content')
    <div class="container">
        <ul>
            <li>
                <a href="{{ route('app.index') }}">Apps</a>
            </li>
            <li> 
                <a href="{{ route('developer.index') }}">Developers</a>
            </li>
        </ul>
    </div>
@endsection
