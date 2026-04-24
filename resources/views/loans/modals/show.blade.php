@extends('layouts.admin')

@section('title', 'Detail Peminjaman')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="text-dark mb-0">Detail Peminjaman</h4>
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

    <div class="row">
        {{-- LEFT: Loan Header Info --}}
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0"><i class="fas fa-receipt mr-2"></i>Informasi Transaksi</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th width="130px">Kode Pinjam</th>
                            <td>:</td>
                            <td><span class="badge badge-primary">{{ $loan->loan_code }}</span></td>
                        </tr>
                        <tr>
                            <th>Anggota</th>
                            <td>:</td>
                            <td>{{ $loan->member->name ?? '-' }}<br>
                                <small class="text-muted">{{ $loan->member->member_code ?? '' }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Petugas</th>
                            <td>:</td>
                            <td>{{ $loan->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tgl Pinjam</th>
                            <td>:</td>
                            <td>{{ $loan->loaned_at->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tenggat</th>
                            <td>:</td>
                            <td>
                                {{ $loan->due_date->format('d M Y') }}
                                @if ($loan->status === 'BORROWED' && \Carbon\Carbon::today()->gt($loan->due_date))
                                    @php $lateDays = \Carbon\Carbon::today()->diffInDays($loan->due_date); @endphp
                                    <span class="badge badge-danger ml-1">Terlambat {{ $lateDays }} hari</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>:</td>
                            <td>
                                @if ($loan->status === 'BORROWED')
                                    @if (\Carbon\Carbon::today()->gt($loan->due_date))
                                        <span class="badge badge-danger">TERLAMBAT</span>
                                    @else
                                        <span class="badge badge-warning">DIPINJAM</span>
                                    @endif
                                @else
                                    <span class="badge badge-success">DIKEMBALIKAN</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status Persetujuan</th>
                            <td>:</td>
                            <td>
                                @if ($loan->approval_status === 'PENDING')
                                    <span class="badge badge-warning">MENUNGGU PERSETUJUAN</span>
                                @elseif ($loan->approval_status === 'APPROVED')
                                    <span class="badge badge-success">DISETUJUI</span><br>
                                    <small class="text-muted">Oleh: {{ $loan->approvedBy->name ?? '-' }}</small><br>
                                    <small class="text-muted">{{ $loan->approved_at?->format('d M Y H:i') ?? '' }}</small>
                                @else
                                    <span class="badge badge-secondary">DITOLAK</span>
                                @endif
                                @if ($loan->approval_note)
                                    <div class="text-muted small mt-1" style="background:#f5f5f5; padding:5px;">
                                        <strong>Catatan:</strong> {{ $loan->approval_note }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @if ($loan->status === 'RETURNED')
                            <tr>
                                <th>Tgl Kembali</th>
                                <td>:</td>
                                <td>{{ $loan->returned_at?->format('d M Y') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Denda</th>
                                <td>:</td>
                                <td>
                                    @if ($loan->fine_total > 0)
                                        <span class="text-danger font-weight-bold">
                                            Rp {{ number_format($loan->fine_total, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-success">Tidak ada denda</span>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    </table>

                    {{-- Actions --}}
                    @if (auth()->user()->hasRole('admin') && $loan->approval_status === 'PENDING')
                        <hr>
                        <form action="{{ route('book-loans.approve', $loan->id) }}" method="POST" class="mb-2">
                            @csrf
                            <textarea name="approval_note" class="form-control form-control-sm" rows="2"
                                placeholder="Catatan approval (opsional)..."></textarea>
                            <div class="text-right mt-2">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check mr-1"></i> Setujui
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                    data-target="#rejectModal">
                                    <i class="fas fa-times mr-1"></i> Tolak
                                </button>
                            </div>
                        </form>
                    @elseif ($loan->status === 'BORROWED' && $loan->approval_status === 'APPROVED')
                        <hr>
                        <form action="{{ route('book-loans.return', $loan->id) }}" method="POST" id="returnForm"
                            class="mb-0">
                            @csrf
                            <button type="button" class="btn btn-success btn-block" id="btnReturn">
                                <i class="fas fa-undo-alt mr-1"></i> Kembalikan Buku
                            </button>
                        </form>
                        @if (\App\Models\LoanExtension::canRequestExtension($loan->id))
                            <a href="{{ route('loan-extensions.create', $loan->id) }}" class="btn btn-warning btn-block mt-2">
                                <i class="fas fa-hourglass-half mr-1"></i> Perpanjang
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT: Loan Items --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0"><i class="fas fa-book mr-2"></i>Buku yang Dipinjam</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center" width="40px">No</th>
                                    <th>Judul Buku</th>
                                    <th class="text-center">Kategori</th>
                                    <th class="text-center">Pengarang</th>
                                    <th class="text-center" width="70px">Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($loan->loanItems as $i => $item)
                                    <tr>
                                        <td class="text-center">{{ $i + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($item->book->cover_path)
                                                    <img src="{{ asset('storage/' . $item->book->cover_path) }}"
                                                        alt="cover" style="width:36px;height:48px;object-fit:cover;margin-right:8px;">
                                                @endif
                                                {{ $item->book->title }}
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">{{ $item->book->category->name ?? '-' }}</td>
                                        <td class="text-center align-middle">{{ $item->book->author }}</td>
                                        <td class="text-center align-middle">{{ $item->qty }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Extension History --}}
    @if ($loan->extensions->count() > 0)
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-info text-white py-2">
                <h6 class="mb-0"><i class="fas fa-hourglass-half mr-2"></i>Histori Perpanjangan</h6>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Permintaan</th>
                            <th>Tenggat Awal</th>
                            <th>Tenggat Baru</th>
                            <th class="text-center">Hari</th>
                            <th>Status</th>
                            <th>Alasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loan->extensions->sortByDesc('created_at') as $ext)
                            <tr>
                                <td class="small" title="{{ $ext->created_at->format('d M Y H:i') }}">
                                    {{ $ext->created_at->diffForHumans() }}
                                </td>
                                <td class="small">
                                    {{ \Carbon\Carbon::parse($ext->loan->due_date)->subDays((int) $ext->extension_days)->format('d M Y') }}
                                </td>
                                <td class="small"><strong>{{ $ext->new_due_date->format('d M Y') }}</strong></td>
                                <td class="text-center small">
                                    <span class="badge badge-light">+{{ $ext->extension_days }}</span>
                                </td>
                                <td class="small">
                                    @if ($ext->status === 'PENDING')
                                        <span class="badge badge-warning">MENUNGGU</span>
                                    @elseif ($ext->status === 'APPROVED')
                                        <span class="badge badge-success">DISETUJUI</span>
                                    @else
                                        <span class="badge badge-secondary">DITOLAK</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ \Illuminate\Support\Str::limit($ext->reason, 25) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Reject Modal --}}
    @if (auth()->user()->hasRole('admin') && $loan->approval_status === 'PENDING')
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('loans.reject', $loan->id) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h6 class="modal-title mb-0">Tolak Peminjaman</h6>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <label class="small font-weight-bold">Catatan Penolakan</label>
                            <textarea name="approval_note" class="form-control form-control-sm" rows="3"
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
    @endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @php
        $today = \Carbon\Carbon::today();
        $dueDate = \Carbon\Carbon::parse($loan->due_date);
        $lateDays = $today->gt($dueDate) ? (int) $today->diffInDays($dueDate) : 0;
        $setting = \App\Models\SettingApp::first();
        $fpd = $setting?->fine_per_day ?? 1000;
        $fine = $lateDays * $fpd;
    @endphp

    document.getElementById('btnReturn')?.addEventListener('click', function() {
        const lateDays = {{ $lateDays }};
        const fine = {{ $fine }};
        const fineStr = 'Rp ' + fine.toLocaleString('id-ID');
        const msg = lateDays > 0 ?
            `Terlambat <strong>${lateDays} hari</strong>. Denda: <strong>${fineStr}</strong>` :
            'Pengembalian tepat waktu. <strong>Tidak ada denda.</strong>';

        Swal.fire({
            title: 'Konfirmasi Pengembalian',
            html: msg,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kembalikan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745',
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById('returnForm').submit();
            }
        });
    });
</script>
@endpush
