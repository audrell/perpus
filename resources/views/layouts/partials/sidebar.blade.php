<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-start" href="{{ url('/home') }}">
        @if (!empty($setting->image))
            <div class="sidebar-brand-icon">
                <img src="{{ asset('storage/uploads/logos/' . $setting->image) }}" alt="logo"
                    style="height: 36px; width: 36px; object-fit: cover; border-radius: 6px;">
            </div>
        @endif
        <div class="sidebar-brand-text mx-2">{{ $setting->short_cut_app ?? config('app.name') }}</div>
    </a>

    <hr class="sidebar-divider my-0 mb-2">

    <li class="nav-item {{ request()->is('home') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/home') }}">
            <i class="fas fa-fw fa-home"></i>
            <span>Home</span></a>
    </li>


    @canany(['users.index', 'roles.index', 'permissions.index'])
        <div class="sidebar-heading">
            User Permissions
        </div>
        <li
            class="nav-item {{ request()->is('users*') || request()->is('roles*') || request()->is('permissions*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->is('users*') || request()->is('roles*') || request()->is('permissions*') ? '' : 'collapsed' }}"
                href="#" data-toggle="collapse" data-target="#collapseUserManagement" aria-expanded="true"
                aria-controls="collapseUserManagement">
                <i class="fas fa-fw fa-users-cog"></i>
                <span>User Management</span>
            </a>

            <div id="collapseUserManagement"
                class="collapse {{ request()->is('users*') || request()->is('roles*') || request()->is('permissions*') ? 'show' : '' }}"
                aria-labelledby="headingUserManagement" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    @can('users.index')
                        <a class="collapse-item {{ request()->is('users*') ? 'active' : '' }}"
                            href="{{ route('users.index') }}">
                            User
                        </a>
                    @endcan

                    @can('roles.index')
                        <a class="collapse-item {{ request()->is('roles*') ? 'active' : '' }}"
                            href="{{ route('roles.index') }}">
                            Role
                        </a>
                    @endcan

                    @can('permissions.index')
                        <a class="collapse-item {{ request()->is('permissions*') ? 'active' : '' }}"
                            href="{{ route('permissions.index') }}">
                            Permissions
                        </a>
                    @endcan
                </div>
            </div>
        </li>
    @endcanany



    <li class="nav-item {{ request()->is('categories*') || request()->is('books*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->is('categories*') || request()->is('books*') ? '' : 'collapsed' }}"
            href="#" data-toggle="collapse" data-target="#collapseDataMaster" aria-expanded="true"
            aria-controls="collapseDataMaster">
            <i class="fas fa-fw fa-database"></i>
            <span>data master</span>
        </a>
        <div id="collapseDataMaster" class="collapse {{ request()->is('categories*') || request()->is('books*') ? 'show' : '' }}"
            aria-labelledby="headingDataMaster" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('categories*') ? 'active' : '' }}" href="{{ route('categories.index') }}">kategori</a>
                <a class="collapse-item {{ request()->is('books*') ? 'active' : '' }}" href="{{ route('books.index') }}">buku</a>
            </div>
        </div>
    </li>
    @canany(['settings.index'])
        @can('settings.index')
            <li class="nav-item {{ request()->is('settings*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('settings.index') }}">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Setting </span>
                </a>
            </li>
        @endcan
    @endcanany

</ul>
