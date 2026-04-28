@extends('layouts.admin')

@section('title', 'Request Perpanjangan Pinjaman')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="text-dark mb-0">Permohonan Perpanjangan Saya</h4>
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

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 font-weight-bold text-dark">
                <i class="fas fa-hourglass-half mr-2 text-warning"></i>Daftar Permohonan
            </h6>
        </div>
        <div class="card-body p-0">
            @forelse ($extensions as $ext)
                <div class="card mb-3 border shadow-none mx-3 mt-3">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="mb-2">
                                    <span class="font-weight-bold text-primary">{{ $ext->loan->loan_code }}</span>
                                    @if ($ext->status === 'PENDING')
                                        <span class="badge badge-warning ml-2">Menunggu</span>
                                    @elseif ($ext->status === 'APPROVED')
                                        <span class="badge badge-success ml-2">Disetujui</span>
                                    @else
                                        <span class="badge badge-secondary ml-2">Ditolak</span>
                                    @endif
                                </div>
                                <div class="small mb-1">
                                    {{-- <strong>Tenggat:</strong> {{ $ext->loan->due_date->format('d M Y') }} --}}
                                    {{-- <i class="fas fa-arrow-right mx-2"></i>
                                    {{ $ext->new_due_date->format('d M Y') }}
                                    (+{{ $ext->extension_days }} hari) --}}

                                    @php
                                        $originalDate = \Carbon\Carbon::parse($ext->new_due_date)->copy()->subDays($ext->extension_days);
                                    @endphp

                                    {{$originalDate->format('d M Y')}}

                                    <i class="fa fa-arrow-right mx-2"></i>

                                    {{ \Carbon\Carbon::parse($ext->new_due_date)->format('d M Y') }}

                                   <span class="text-success ml-1">(+{{ $ext->extension_days }} hari)</span>
                                </div>
                                <div class="small mb-1">
                                    <strong>Alasan:</strong> {{ $ext->reason }}
                                </div>
                                @if ($ext->admin_note)
                                    <div class="small">
                                        <strong>Catatan Admin:</strong> {{ $ext->admin_note }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="small text-muted">
                                    Diajukan: {{ $ext->created_at->diffForHumans() }}
                                </div>
                                @if ($ext->approved_at)
                                    <div class="small text-muted">
                                        {{ $ext->status === 'APPROVED' ? 'Disetujui' : 'Ditolak' }}: {{ $ext->approved_at->diffForHumans() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="fas fa-inbox fa-3x mb-3 mx-auto d-block text-secondary"></i>
                    Belum ada permohonan.
                </div>
            @endforelse
        </div>
    </div>

    @if ($extensions->hasPages())
        <div class="mt-3">
            {{ $extensions->links('pagination::bootstrap-4') }}
        </div>
    @endif

@endsection
