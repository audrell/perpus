@extends('layouts.admin')

@section('title', 'Kelola Perpanjangan Pinjaman')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="text-dark mb-0">Manajemen Perpanjangan Pinjaman</h4>
        <a href="{{ route('loans.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    {{-- Pending Requests --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-dark py-3">
            <h6 class="mb-0 font-weight-bold">
                <i class="fas fa-hourglass-half mr-2"></i>Permohonan Menunggu ({{ $extensions->total() }})
            </h6>
        </div>
        <div class="card-body p-0">
            @forelse ($extensions as $ext)
                <div class="card mb-3 border shadow-none mx-3 mt-3">
                    <div class="card-body py-3">
                        <div class="row align-items-start">
                            <div class="col-md-7">
                                <div class="mb-2">
                                    <span class="font-weight-bold text-primary">{{ $ext->loan->loan_code }}</span>
                                    <span class="badge badge-light ml-2">{{ $ext->loan->member->member_code }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>{{ $ext->loan->member->name }}</strong>
                                    <span class="text-muted small">• Pinjam: {{ $ext->loan->loaned_at->format('d M Y') }}</span>
                                </div>
                                <div class="small mb-2">
                                    <strong>Tenggat:</strong>
                                    <span class="text-danger">{{ $ext->loan->due_date->format('d M Y') }}</span>
                                    <i class="fas fa-arrow-right mx-2"></i>
                                    <span class="text-success">{{ $ext->new_due_date->format('d M Y') }}</span>
                                    <span class="badge badge-info ml-2">+{{ $ext->extension_days }} hari</span>
                                </div>
                                <div class="small mb-1">
                                    <strong>Alasan:</strong> {{ $ext->reason }}
                                </div>
                                <div class="small text-muted">
                                    Diminta: {{ $ext->requestedBy->name }} • {{ $ext->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="col-md-5">
                                <form action="{{ route('loan-extensions.approve', $ext->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <textarea name="admin_note" class="form-control form-control-sm" rows="2"
                                        placeholder="Catatan..."></textarea>
                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check mr-1"></i>Setujui
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#rejectModal{{ $ext->id }}">
                                            <i class="fas fa-times mr-1"></i>Tolak
                                        </button>
                                    </div>
                                </form>

                                {{-- Reject Modal --}}
                                <div class="modal fade" id="rejectModal{{ $ext->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('loan-extensions.reject', $ext->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Tolak Perpanjangan</h6>
                                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="small text-muted mb-3">Pinjaman: <strong>{{ $ext->loan->loan_code }}</strong></p>
                                                    <textarea name="admin_note" class="form-control form-control-sm" rows="3"
                                                        placeholder="Jelaskan alasan penolakan..."></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger btn-sm">Ya, Tolak</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">
                    <i class="fas fa-check-circle fa-2x mb-2 d-block text-success"></i>
                    Tidak ada permohonan menunggu.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Approved Requests --}}
    @if ($approved->count() > 0)
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white py-3">
                <h6 class="mb-0 font-weight-bold">
                    <i class="fas fa-check-circle mr-2"></i>Disetujui (5 Terakhir)
                </h6>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode</th>
                            <th>Anggota</th>
                            <th>Tenggat Lama</th>
                            <th>Tenggat Baru</th>
                            <th>Disetujui Oleh</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($approved as $ext)
                            <tr>
                                <td><span class="badge badge-success">{{ $ext->loan->loan_code }}</span></td>
                                <td>{{ $ext->loan->member->name }}</td>
                                <td>{{ $ext->loan->due_date->format('d M Y') }}</td>
                                <td><strong>{{ $ext->new_due_date->format('d M Y') }}</strong></td>
                                <td>{{ $ext->approvedBy->name }}</td>
                                <td class="small text-muted">{{ $ext->approved_at?->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if ($extensions->hasPages())
        <div class="mt-3">
            {{ $extensions->links('pagination::bootstrap-4') }}
        </div>
    @endif

@endsection
