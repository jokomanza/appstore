<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    {{-- <link href="{{ asset('css/app.css?v=2') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    <link href="{{ asset('select2-4.0.13/css/select2.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap5.datatables.css') }}" />

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/autosize.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('select2-4.0.13/js/select2.full.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/datatables.js') }}"></script>

</head>

<body>
    <div id="app">

        @if (!Auth::guest())
            <div id="sidebar" class="active">
                <div class="sidebar-wrapper active">
                    <div class="sidebar-header">
                        <div class="d-flex justify-content-between">
                            <div class="logo">
                                <a href="{{ route('home') }}"><img src="assets/images/logo/logo.png" alt="App Store"
                                        srcset=""></a>
                            </div>
                            <div class="toggler">
                                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-menu">
                        <ul class="menu">
                            <li class="sidebar-title">Menu</li>

                            <li class="sidebar-item {{ Route::is('home') ? 'active' : '' }} ">
                                <a href="{{ route('home') }}" class='sidebar-link'>
                                    <i class="bi bi-grid-fill"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            <li class="sidebar-item  has-sub {{ Route::is('app.*') ? 'active' : '' }}">
                                <a href="#" class='sidebar-link'>
                                    <i class="bi bi-stack"></i>
                                    <span>Apps</span>
                                </a>
                                <ul class="submenu {{ Route::is('app.*') ? 'active' : '' }}">
                                    <li class="submenu-item {{ Route::is('app.index') ? 'active' : '' }}">
                                        <a href="{{ route('app.index') }}">All</a>
                                    </li>
                                    <li class="submenu-item {{ Route::is('app.create') ? 'active' : '' }}">
                                        <a href="{{ route('app.create') }}">Create</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-item  has-sub  {{ Route::is('developer.*') ? 'active' : '' }}">
                                <a href="#" class='sidebar-link'>
                                    <i class="bi bi-collection-fill"></i>
                                    <span>Developer</span>
                                </a>
                                <ul class="submenu {{ Route::is('developer.*') ? 'active' : '' }}">
                                    <li class="submenu-item {{ Route::is('developer.index') ? 'active' : '' }}">
                                        <a href="{{ route('developer.index') }}">All</a>
                                    </li>
                                    <li class="submenu-item  {{ Route::is('developer.create') ? 'active' : '' }}">
                                        <a href="{{ route('developer.create') }}">Create</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-item {{ Route::is('client.index') ? 'active' : '' }} ">
                                <a href="{{ route('client.index') }}" class='sidebar-link'>
                                    <i class="bi bi-grid-fill"></i>
                                    <span>Client Application</span>
                                </a>
                            </li>

                            <li class="sidebar-item  has-sub  {{ Route::is('developer.*') ? 'active' : '' }}">
                                <a href="#" class='sidebar-link'>
                                    <i class="bi bi-collection-fill"></i>
                                    <span>Crash Report</span>
                                </a>
                                <ul class="submenu {{ Route::is('developer.*') ? 'active' : '' }}">
                                    <li class="submenu-item {{ Route::is('developer.index') ? 'active' : '' }}">
                                        <a href="{{ route('developer.index') }}">All</a>
                                    </li>
                                    <li class="submenu-item  {{ Route::is('developer.create') ? 'active' : '' }}">
                                        <a href="{{ route('developer.create') }}">Create</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-title">Other</li>

                            <li class="sidebar-item  ">
                                <a href="{{ route('docs') }}" class='sidebar-link'>
                                    <i class="fa fa-book"></i>
                                    <span>Documentation</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                    <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
                </div>
            </div>
        @endif

        <div id="{{ Auth::guest() ? '' : 'main' }}">

            @if (!Auth::guest())
                <header class='mb-3'>
                    <nav class="navbar navbar-expand navbar-light ">
                        <div class="container-fluid">
                            {{-- <a href="#" class="burger-btn d-block">
                            <i class="bi bi-justify fs-3"></i>
                        </a> --}}

                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                                    <li class="nav-item dropdown me-1">
                                        <a class="nav-link active dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class='bi bi-envelope bi-sub fs-4 text-gray-600'></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end"
                                            aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <h6 class="dropdown-header">Mail</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#">No new mail</a></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item dropdown me-3">
                                        <a class="nav-link active dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class='bi bi-bell bi-sub fs-4 text-gray-600'></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end"
                                            aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <h6 class="dropdown-header">Notifications</h6>
                                            </li>
                                            <li><a class="dropdown-item">No notification available</a></li>
                                        </ul>
                                    </li>
                                </ul>
                                <div class="dropdown">
                                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="user-menu d-flex">
                                            <div class="user-name text-end me-3">
                                                <h6 class="mb-0 text-gray-600">{{ Auth::user()->name }}</h6>
                                                <p class="mb-0 text-sm text-gray-600">Level
                                                    {{ Auth::user()->access_level }}</p>
                                            </div>
                                            <div class="user-img d-flex align-items-center">
                                                <div class="avatar avatar-md">
                                                    <img src="{{ asset('images/user1.png') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                                        style="min-width: 11rem;">
                                        <li>
                                            <h6 class="dropdown-header">Hello, John!</h6>
                                        </li>
                                        <li><a class="dropdown-item" href="#"><i
                                                    class="icon-mid bi bi-person me-2"></i>
                                                My
                                                Profile</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="icon-mid bi bi-gear me-2"></i>
                                                Settings</a></li>
                                        <li><a class="dropdown-item" href="#"><i
                                                    class="icon-mid bi bi-wallet me-2"></i>
                                                Wallet</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
        document.getElementById('logout-form').submit();"><i class="icon-mid bi bi-box-arrow-left me-2"></i>
                                                Logout</a></li>


                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none;">
                                            {{ csrf_field() }}
                                        </form>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </nav>
                </header>
            @endif


            <div id="page-heading">
                @yield('content')
            </div>

            @if (!Auth::guest())
                <footer>
                    <div class="footer clearfix mb-0 text-muted">
                        <div class="float-start">
                            <p>2022 &copy; CV. Karya Hidup Sentosa</p>
                        </div>
                        <div class="float-end">
                            <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a
                                    href="http://ahmadsaugi.com">A. Saugi</a></p>
                        </div>
                    </div>
                </footer>
            @endif
        </div>
    </div>


    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('js/mazer.js') }}"></script>
    @yield('script')
</body>

</html>
