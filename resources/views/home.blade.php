@extends('layouts.admin')

@push('styles')
    <style>
        /* Minimalist Dashboard Styles */
        .dashboard-minimalist {
            background: #f8f9fa;
            padding: 0;
        }

        .hero-minimal {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border-radius: 24px;
            padding: 3rem 2rem;
            color: white;
            margin-bottom: 2rem;
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
        }

        .hero-minimal::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .hero-minimal .greeting {
            font-size: 0.875rem;
            letter-spacing: 1px;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        .hero-minimal h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .hero-minimal p {
            opacity: 0.85;
            max-width: 600px;
        }

        .stat-minimal {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e9ecef;
        }

        .stat-minimal:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
            border-color: transparent;
        }

        .stat-minimal .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-minimal .stat-label {
            font-size: 0.875rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-minimal .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .card-minimal {
            background: white;
            border-radius: 16px;
            border: 1px solid #e9ecef;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card-minimal:hover {
            border-color: #dee2e6;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .card-minimal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .card-minimal-header h5 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
            color: #212529;
        }

        .action-link-minimal {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f1f3f5;
            text-decoration: none;
            color: #495057;
            transition: all 0.2s ease;
        }

        .action-link-minimal:last-child {
            border-bottom: none;
        }

        .action-link-minimal:hover {
            background: #f8f9fa;
            color: #212529;
        }

        .user-item-minimal {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f1f3f5;
        }

        .user-item-minimal:last-child {
            border-bottom: none;
        }

        .user-avatar-minimal {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            flex-shrink: 0;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid pt-5 px-3">
        <div class="dashboard-minimalist">
            <div class="hero-minimal">
                <div class="greeting">WELCOME BACK</div>
                <h1>Hello, {{ auth()->user()->name }} 👋</h1>
                <p>Manage your library system efficiently from a single, organized dashboard.</p>
            </div>

            <!-- Stats Grid -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="stat-minimal">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-number" style="color: #3b82f6;">{{ number_format($stats['users']) }}</div>
                                <div class="stat-label">Total Users</div>
                            </div>
                            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-minimal">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-number" style="color: #3b82f6;">{{ number_format($stats['members']) }}</div>
                                <div class="stat-label">Total Member</div>
                            </div>
                            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                                <i class="fa-solid fa-people-group"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-minimal">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-number" style="color: #3b82f6;">{{ number_format($stats['books']) }}</div>
                                <div class="stat-label">Total Books</div>
                            </div>
                            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                                <i class="fa-solid fa-book"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-minimal">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-number" style="color: #A52A2A;">{{ number_format($stats['loans']) }}</div>
                                <div class="stat-label">Total Loans</div>
                            </div>
                            <div class="stat-icon" style="background: rgba(165, 42, 42, 0.1); color: #A52A2A;">
                                <i class="fa-solid fa-swatchbook"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mt-2">
                    <div class="stat-minimal">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-number" style="color: #10b981;">{{ number_format($stats['roles']) }}</div>
                                <div class="stat-label">Total Roles</div>
                            </div>
                            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                <i class="fas fa-user-shield"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mt-2">
                    <div class="stat-minimal">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-number" style="color: #f59e0b;">{{ number_format($stats['permissions']) }}
                                </div>
                                <div class="stat-label">Permissions</div>
                            </div>
                            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                <i class="fas fa-key"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <!-- Quick Actions -->
                <div class="col-lg-4">
                    <div class="card-minimal">
                        <div class="card-minimal-header">
                            <h5><i class="fas fa-bolt me-2" style="color: #3b82f6; margin-right: 10px"></i>Quick Actions
                            </h5>
                        </div>
                        <div>
                            @can('users.index')
                                <a href="{{ route('users.index') }}" class="action-link-minimal">
                                    <span><i class="fas fa-users me-2" style="color: #3b82f6; margin-right: 10px"></i>Manage
                                        Users</span>
                                    <i class="fas fa-arrow-right" style="font-size: 0.875rem;"></i>
                                </a>
                            @endcan
                            @can('members.index')
                                <a href="{{ route('members.index') }}" class="action-link-minimal">
                                    <span><i class="fa-solid fa-people-group me-2" style="color: #3b82f6; margin-right: 10px"></i>Manage
                                        Users</span>
                                    <i class="fas fa-arrow-right" style="font-size: 0.875rem;"></i>
                                </a>
                            @endcan
                            @can('books.index')
                                <a href="{{ route('books.index') }}" class="action-link-minimal">
                                    <span><i class="fa-solid fa-book me-2" style="color: #3b82f6; margin-right: 10px"></i>Manage
                                        Books</span>
                                    <i class="fas fa-arrow-right" style="font-size: 0.875rem;"></i>
                                </a>
                            @endcan
                            @can('books.index')
                                <a href="{{ route('loans.index') }}" class="action-link-minimal">
                                    <span><i class="fa-solid fa-swatchbook me-2"
                                            style="color: #A52A2A; margin-right: 10px;"></i>Manage Loans</span>
                                    <i class="fas fa-arrow-right" style="font-size: 0.875rem;"></i>
                                </a>
                            @endcan
                            @can('roles.index')
                                <a href="{{ route('roles.index') }}" class="action-link-minimal">
                                    <span><i class="fas fa-user-shield me-2"
                                            style="color: #10b981; margin-right: 10px;"></i>Manage Roles</span>
                                    <i class="fas fa-arrow-right" style="font-size: 0.875rem;"></i>
                                </a>
                            @endcan
                            @can('permissions.index')
                                <a href="{{ route('permissions.index') }}" class="action-link-minimal">
                                    <span><i class="fas fa-key me-2" style="color: #f59e0b; margin-right: 10px;"></i>Manage
                                        Permissions</span>
                                    <i class="fas fa-arrow-right" style="font-size: 0.875rem;"></i>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="col-lg-8">
                    <div class="card-minimal">
                        <div class="card-minimal-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5><i class="fas fa-user-clock me-2" style="color: #3b82f6; margin-right: 10px"></i>Recent
                                    Users</h5>
                                <a href="{{ route('users.index') }}"
                                    style="font-size: 0.875rem; color: #3b82f6; text-decoration: none;">
                                    View All <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                        <div>
                            @forelse($recentUsers as $user)
                                <div class="user-item-minimal">
                                    <div class="user-avatar-minimal"
                                        style="background: linear-gradient(135deg, {{ ['#3b82f6', '#10b981', '#f59e0b', '#ec4899'][$loop->index % 4] }} 0%, {{ ['#1e40af', '#059669', '#d97706', '#be185d'][$loop->index % 4] }} 100%); {{ $user->profile_photo ? 'padding: 0; overflow: hidden;' : '' }}">
                                        @if ($user->profile_photo)
                                            <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                                alt="{{ $user->name }}"
                                                style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                                        @else
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div style="font-weight: 600; font-size: 0.9375rem;">{{ $user->name }}</div>
                                        <div style="font-size: 0.8125rem; color: #6c757d;">{{ $user->email }}</div>
                                    </div>
                                    <div style="font-size: 0.8125rem; color: #adb5bd;">
                                        {{ $user->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="fas fa-users fa-3x mb-3" style="color: #dee2e6;"></i>
                                    <p style="color: #6c757d;">No users yet</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

    <!-- Monthly Report -->
            <div class="row g-3 mt-3">
                <div class="col-12">
            <div class="card-minimal">
                <div class="card-minimal-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5><i class="fas fa-chart-bar me-2" style="color: #3b82f6;"></i>Laporan Peminjaman Bulanan</h5>
                        <form method="GET" action="{{ route('home') }}" class="d-flex gap-2 align-items-center">
                            <input type="month" name="report_month" value="{{ $month }}"
                                class="form-control form-control-sm" style="width: auto;">
                            <select name="report_status" class="form-select form-select-sm" style="width: auto;">
                                <option value="">Semua Approval</option>
                                <option value="approved" {{ request('report_status') == 'approved' ? 'selected' : '' }}>
                                    Approved</option>
                                <option value="pending" {{ request('report_status') == 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="rejected" {{ request('report_status') == 'rejected' ? 'selected' : '' }}>
                                    Rejected</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        </form>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row g-3 mb-3 mt-1">
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 text-center" style="background: rgba(59,130,246,0.08);">
                            <div style="font-size: 1.5rem; font-weight: 700; color: #3b82f6;">{{ $reportLoans->count() }}
                            </div>
                            <div style="font-size: 0.8rem; color: #6c757d;">Total Peminjaman</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 text-center" style="background: rgba(16,185,129,0.08);">
                            <div style="font-size: 1.5rem; font-weight: 700; color: #10b981;">{{ $totalBooksLoaned }}
                            </div>
                            <div style="font-size: 0.8rem; color: #6c757d;">Total Buku Dipinjam</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 text-center" style="background: rgba(245,158,11,0.08);">
                            <div style="font-size: 1.5rem; font-weight: 700; color: #f59e0b;">
                                {{ $loanStatusCount['terlambat'] }}</div>
                            <div style="font-size: 0.8rem; color: #6c757d;">Terlambat</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 text-center" style="background: rgba(239,68,68,0.08);">
                            <div style="font-size: 1.5rem; font-weight: 700; color: #ef4444;">
                                {{ $statusCount['rejected'] }}</div>
                            <div style="font-size: 0.8rem; color: #6c757d;">Ditolak</div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle" style="font-size: 0.875rem;">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th>Kode Pinjam</th>
                                <th>Anggota</th>
                                <th>Tgl Pinjam</th>
                                <th>Tenggat</th>
                                <th>Jml Buku</th>
                                <th>Status</th>
                                <th>Approval</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportLoans as $loan)
                                <tr>
                                    <td><span style="font-weight: 600;">{{ $loan->loan_code }}</span></td>
                                    <td>{{ $loan->member->name ?? '-' }}</td>
                                    <td>{{ $loan->loaned_at->format('d/m/Y') }}</td>
                                    <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                                    <td>{{ $loan->loanItems->sum('qty') }}</td>
                                    <td>
                                        @php
                                            $statusColor = match ($loan->status) {
                                                'dikembalikan' => '#10b981',
                                                'dipinjam' => '#3b82f6',
                                                'terlambat' => '#ef4444',
                                                'ditolak' => '#6c757d',
                                                default => '#6c757d',
                                            };
                                        @endphp
                                        <span class="badge" style="background: {{ $statusColor }};">
                                            {{ strtoupper($loan->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $approvalColor = match ($loan->approval_status) {
                                                'approved' => '#10b981',
                                                'pending' => '#f59e0b',
                                                'rejected' => '#ef4444',
                                                default => '#6c757d',
                                            };
                                        @endphp
                                        <span class="badge" style="background: {{ $approvalColor }};">
                                            {{ strtoupper($loan->approval_status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4" style="color: #6c757d;">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        Tidak ada data peminjaman bulan ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    </div>
</div>
@endsection
