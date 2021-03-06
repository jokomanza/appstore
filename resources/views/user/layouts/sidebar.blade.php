<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="{{ route('welcome') }}"><img style="height: 3rem; width: 3rem;"
                                                          src="{{ asset('images/logo.png') }}" alt="App Store"
                                                          srcset="">
                        <h2><strong>Quick App Store</strong></h2>
                    </a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item {{ Route::is('user.home') ? 'active' : '' }} ">
                    <a href="{{ route('user.home') }}" class='sidebar-link'>
                        <i class="bi bi-house-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Route::is('user.app.*') ? ( isset($app) && $app instanceof \App\Models\App ? ( $app->isClientApp() ? '' : 'active') : 'active') : '' }}">
                    <a href="{{ route('user.app.index') }}" class='sidebar-link'>
                        <i class="bi bi-stack"></i>
                        <span>Apps</span>
                    </a>
                </li>

                <li class="sidebar-item {{ isset($app) && $app instanceof \App\Models\App ? ( $app->isClientApp() ? 'active' : '') : '' }} ">
                    <a href="{{ route('user.app.show', config('app.client_package_name')) }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Client Application</span>
                    </a>
                </li>

                <li class="sidebar-title">Other</li>

                <li class="sidebar-item  {{ Route::is('user.manual') ? 'active' : '' }}">
                    <a href="{{ route('user.manual') }}" class='sidebar-link'>
                        <i class="bi bi-book-fill"></i>
                        <span>User Manual</span>
                    </a>
                </li>

                <li class="sidebar-item  {{ Route::is('user.documentation') ? 'active' : '' }}">
                    <a href="{{ route('user.documentation') }}" class='sidebar-link'>
                        <i class="bi bi-book-fill"></i>
                        <span>Documentations</span>
                    </a>
                </li>

                <li class="sidebar-item  {{ Route::is('user.profile.show') ? 'active' : '' }}">
                    <a href="{{ route('user.profile.show') }}" class='sidebar-link'>
                        <i class="bi bi-person-fill"></i>
                        <span>Profile</span>
                    </a>
                </li>

            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
