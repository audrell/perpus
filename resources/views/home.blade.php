@extends('layouts.admin')

@section('content')
    <div class="card border-0 shadow mb-4 bg-primary text-white">
        <div class="card-body p-4 p-lg-5">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <span class="badge badge-light text-primary mb-3">Dashboard Overview</span>
                    <h1 class="h2 font-weight-bold mb-2">Selamat datang, {{ auth()->user()->name }}!</h1>
                    <p class="mb-4 text-white-50">
                        Kelola pengguna, role, dan permission dari satu dashboard yang rapi dan konsisten.
                    </p>
                    <div>
                        @can('users.index')
                            <a href="{{ route('users.index') }}" class="btn btn-light text-primary font-weight-bold mr-2 mb-2">
                                <i class="fas fa-users mr-2"></i>Lihat Users
                            </a>
                        @endcan
                        @can('roles.index')
                            <a href="{{ route('roles.index') }}" class="btn btn-outline-light font-weight-bold mb-2">
                                <i class="fas fa-user-shield mr-2"></i>Lihat Roles
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm text-dark">
                        <div class="card-body">
                            <div class="text-uppercase small text-muted font-weight-bold mb-3">Ringkasan Cepat</div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Total Pengguna</span>
                                <strong>{{ number_format($stats['users']) }}</strong>
                            </div>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Total Permission</span>
                                <span class="badge badge-warning">{{ number_format($stats['permissions']) }}</span>
                            </div>
                            <div class="small text-muted">Pantau hak akses sistem agar pengelolaan pengguna tetap terstruktur.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left border-primary shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase small text-primary font-weight-bold mb-2">Users</div>
                            <h3 class="mb-0 text-dark font-weight-bold">{{ number_format($stats['users']) }}</h3>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left border-success shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase small text-success font-weight-bold mb-2">Roles</div>
                            <h3 class="mb-0 text-dark font-weight-bold">{{ number_format($stats['roles']) }}</h3>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-user-shield fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left border-warning shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase small text-warning font-weight-bold mb-2">Permissions</div>
                            <h3 class="mb-0 text-dark font-weight-bold">{{ number_format($stats['permissions']) }}</h3>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-key fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body p-0">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="text-uppercase small text-muted font-weight-bold mb-0">Quick Actions</div>
                    </div>
                    <div class="list-group list-group-flush">
                        @can('users.index')
                            <a href="{{ route('users.index') }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-users mr-2 text-primary"></i>Kelola Users</span>
                                <i class="fas fa-arrow-right text-muted"></i>
                            </a>
                        @endcan

                        @can('roles.index')
                            <a href="{{ route('roles.index') }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-user-shield mr-2 text-success"></i>Kelola Roles</span>
                                <i class="fas fa-arrow-right text-muted"></i>
                            </a>
                        @endcan

                        @can('permissions.index')
                            <a href="{{ route('permissions.index') }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-key mr-2 text-warning"></i>Kelola Permissions</span>
                                <i class="fas fa-arrow-right text-muted"></i>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="text-uppercase small text-muted font-weight-bold mb-1">Recent Users</div>
                    <h5 class="mb-0 font-weight-bold text-dark">Pengguna terbaru</h5>
                </div>
                <div class="card-body p-0">
                    @if ($recentUsers->isEmpty())
                        <div class="alert alert-light border rounded m-3 mb-0 text-center">
                            <i class="fas fa-user-friends fa-2x d-block mb-3 text-muted"></i>
                            Belum ada data pengguna.
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($recentUsers as $user)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="media align-items-center">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                                                style="width: 44px; height: 44px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="media-body">
                                                <div class="font-weight-bold text-dark">{{ $user->name }}</div>
                                                <div class="small text-muted">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                        <div class="small text-muted text-right ml-3">
                                            {{ $user->created_at?->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="text-uppercase small text-muted font-weight-bold mb-1">System Notes</div>
                    <h5 class="mb-0 font-weight-bold text-dark">Informasi dashboard</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-primary mb-3">
                        <i class="fas fa-info-circle mr-2"></i>
                        Gunakan menu sidebar untuk mengakses modul utama aplikasi dengan lebih cepat.
                    </div>

                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Saat ini sistem memiliki <strong>{{ number_format($stats['permissions']) }}</strong> permission aktif.
                    </div>

                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle mr-2"></i>
                        Total data master saat ini mencakup <strong>{{ number_format($stats['users']) }}</strong> user dan
                        <strong>{{ number_format($stats['roles']) }}</strong> role.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
