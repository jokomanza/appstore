@extends('base.layouts.app')

@auth('user')

    @section('sidebar')
        @include('user.layouts.sidebar')
    @endsection

    @section('main')
        <div id="main">

            @include('user.layouts.header')

            <div id="page-heading">
                <header class="mb-3">
                    <a href="#" class="burger-btn d-block d-xl-none">
                        <i class="bi bi-justify fs-3"></i>
                    </a>
                </header>

                @yield('content')

            </div>

            @include('base.layouts.footer')
        </div>
    @endsection
@endauth

@guest('user')
    @yield('content')
@endguest
