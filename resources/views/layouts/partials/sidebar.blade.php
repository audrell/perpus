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
        <div class="sidebar-heading">User Management</div>
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
                            href="{{ route('users.index') }}"> Users
                        </a>
                    @endcan

                    @can('roles.index')
                        <a class="collapse-item {{ request()->is('roles*') ? 'active' : '' }}"
                            href="{{ route('roles.index') }}"> Roles
                        </a>
                    @endcan

                    @can('permissions.index')
                        <a class="collapse-item {{ request()->is('permissions*') ? 'active' : '' }}"
                            href="{{ route('permissions.index') }}"> Permissions
                        </a>
                    @endcan
                </div>
            </div>
        </li>
        <hr class="sidebar-divider">
    @endcanany


    @canany(['categories.index', 'books.index', 'members.index'])
        <div class="sidebar-heading">Data Master</div>
        <li class="nav-item {{ request()->is('categories*') || request()->is('books*') || request()->is('members*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->is('categories*') || request()->is('books*')  || request()->is('members*') ? '' : 'collapsed' }}"
                href="#" data-toggle="collapse" data-target="#collapseDataMaster" aria-expanded="true"
                aria-controls="collapseDataMaster">
                <i class="fas fa-fw fa-database"></i>
                <span>Data Master</span>
            </a>
            <div id="collapseDataMaster"
                class="collapse {{ request()->is('categories*') || request()->is('books*') || request()->is('members*') ? 'show' : '' }}"
                aria-labelledby="headingDataMaster" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    @can('categories.index')
                        <a class="collapse-item {{ request()->is('categories*') ? 'active' : '' }}"
                            href="{{ route('categories.index') }}">Categories
                        </a>
                    @endcan
                    @can('books.index')
                        <a class="collapse-item {{ request()->is('books*') ? 'active' : '' }}"
                            href="{{ route('books.index') }}">Books
                        </a>
                    @endcan
                    @can('members.index')
                        <a class="collapse-item {{ request() ->is('members*') ? 'active' : ''}}"
                            href="{{ route('members.index') }}">Members
                        </a>
                    @endcan
                </div>
            </div>
        </li>
    @endcanany


    @can('loans.index')
    <li class="nav-item {{ request()->is('loans*') || request()->is('loan-extensions*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->is('loans*') || request()->is('loan-extensions*') ? '' : 'collapsed' }}"
            href="#" data-toggle="collapse" data-target="#collapseLoans"
            aria-expanded="true" aria-controls="collapseLoans">
            <i class="fas fa-fw fa-exchange-alt"></i>
            <span>Loans</span>
        </a>

        <div id="collapseLoans"
            class="collapse {{ request()->is('loans*') || request()->is('loan-extensions*') ? 'show' : '' }}"
            aria-labelledby="headingLoans" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">

                {{-- User & Admin bisa lihat --}}
                <a class="collapse-item {{ request()->is('loans') ? 'active' : '' }}"
                    href="{{ route('loans.index') }}">
                    Data Peminjaman
                </a>

                {{-- Admin only --}}
                @can('loan-extensions.admin-index')
                    <a class="collapse-item {{ request()->is('loan-extensions/admin') ? 'active' : '' }}"
                        href="{{ route('loan-extensions.admin-index') }}">
                        Permohonan Perpanjangan
                    </a>
                @endcan

                {{-- User permohonan mereka --}}
                 @can('loan-extensions.index')
                    <a class="collapse-item {{ request()->is('loan-extensions') && !request()->is('loan-extensions.user-index') ? 'active' : '' }}"
                        href="{{ route('loan-extensions.user-index') }}">
                        Perpanjangan Saya
                    </a>
                @endcan 
            </div>
        </div>
    </li>
@endcan


    @can('settings.index')
        <div class="sidebar-heading">Configuration</div>
        <li class="nav-item {{ request()->is('settings*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('settings.index') }}">
                <i class="fas fa-fw fa-cog"></i>
                <span>Settings</span>
            </a>
        </li>
    @endcan

    <hr class="sidebar-divider d-none d-md-block">

</ul>
