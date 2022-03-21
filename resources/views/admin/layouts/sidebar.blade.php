<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="{{ route('admin.home') }}"><img src="{{ asset('images/logo.png') }}" alt="App Store"
                            srcset="">
                        <h2><strong>Quick App Store (ADMIN)</strong></h2>
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

                <li class="sidebar-item {{ Route::is('admin.home') ? 'active' : '' }} ">
                    <a href="{{ route('admin.home') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item  has-sub {{ Route::is('admin.app.*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-stack"></i>
                        <span>Apps</span>
                    </a>
                    <ul class="submenu {{ Route::is('admin.app.*') ? 'active' : '' }}">
                        <li class="submenu-item {{ Route::is('admin.app.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.app.index') }}">All</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.app.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.app.create') }}">Create</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item  has-sub  {{ Route::is('admin.user.*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-collection-fill"></i>
                        <span>Users</span>
                    </a>
                    <ul class="submenu {{ Route::is('admin.user.*') ? 'active' : '' }}">
                        <li class="submenu-item {{ Route::is('admin.user.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.user.index') }}">All</a>
                        </li>
                        <li class="submenu-item  {{ Route::is('admin.user.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.user.create') }}">Create</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item  has-sub  {{ Route::is('admin.admin.*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-collection-fill"></i>
                        <span>Admins</span>
                    </a>
                    <ul class="submenu {{ Route::is('admin.admin.*') ? 'active' : '' }}">
                        <li class="submenu-item {{ Route::is('admin.admin.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.admin.index') }}">All</a>
                        </li>
                        <li class="submenu-item  {{ Route::is('admin.admin.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.admin.create') }}">Create</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item {{ Route::is('admin.client.show') ? 'active' : '' }} ">
                    <a href="{{ route('admin.client.show') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Client Application</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Route::is('admin.report.index') ? 'active' : '' }} ">
                    <a href="{{ route('admin.report.index') }}" class='sidebar-link'>
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
