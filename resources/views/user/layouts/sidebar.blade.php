<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="{{ route('user.home') }}"><img src="{{ asset('images/logo.png') }}" alt="App Store"
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
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item  has-sub {{ Route::is('user.app.*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-stack"></i>
                        <span>Apps</span>
                    </a>
                    <ul class="submenu {{ Route::is('user.app.*') ? 'active' : '' }}">
                        <li class="submenu-item {{ Route::is('user.app.index') ? 'active' : '' }}">
                            <a href="{{ route('user.app.index') }}">All</a>
                        </li>
                        <li class="submenu-item {{ Route::is('app.create') ? 'active' : '' }}">
                            <a href="{{ route('user.app.create') }}">Create</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item {{ Route::is('client.show') ? 'active' : '' }} ">
                    <a href="{{ route('user.client.show') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Client Application</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Route::is('report.index') ? 'active' : '' }} ">
                    <a href="{{ route('user.report.index') }}" class='sidebar-link'>
                        <i class="bi bi-collection-fill"></i>
                        <span>Crash Report</span>
                    </a>
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
