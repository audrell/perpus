<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar shadow">
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <h4 class="mt-2 text-dark font-weight-bold">{{ $setting->name_app ?? config('app.name') }}</h4>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        @auth
            <li class="nav-item dropdown no-arrow">
                @php
                    $initials = collect(explode(' ', trim(Auth::user()->name)))
                        ->filter()
                        ->take(2)
                        ->map(function ($word) {
                            return \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($word, 0, 1));
                        })
                        ->implode('');
                @endphp
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                    <span
                        class="img-profile rounded-circle d-inline-flex align-items-center justify-content-center bg-primary text-white font-weight-bold"
                        style="width: 2rem; height: 2rem; font-size: 0.75rem;">
                        {{ $initials }}
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item border-0 bg-transparent text-left w-100">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </li>
        @endauth


    </ul>
</nav>
