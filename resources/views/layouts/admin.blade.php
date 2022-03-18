@extends('layouts.base.app')


@auth('admin')
    @section('sidebar')
        @include('layouts.sidebars.admin')
    @endsection

    @section('main')
        <div id="main">

            @include('layouts.headers.admin')

            <div id="page-heading">
                <header class="mb-3">
                    <a href="#" class="burger-btn d-block d-xl-none">
                        <i class="bi bi-justify fs-3"></i>
                    </a>
                </header>

                @yield('content')

            </div>

            @include('layouts.footers.footer')
        </div>
    @endsection
@endauth

@guest('admin')
    @yield('content')
@endguest


@push('script')
    @stack('script')
@endpush
