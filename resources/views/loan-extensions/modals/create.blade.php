@extends('layouts.admin')

@section('title', 'Request Perpanjangan')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="text-dark mb-0">Form Request Perpanjangan</h4>
        <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        {{-- LEFT: Info Pinjaman --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0"><i class="fas fa-receipt mr-2"></i>Informasi Pinjaman</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th width="100px">Kode</th>
                            <td>:<span class="badge badge-primary ml-2">{{ $loan->loan_code }}</span></td>
                        </tr>
                        <tr>
                            <th>Anggota</th>
                            <td>: {{ $loan->member->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tgl Pinjam</th>
                            <td>: {{ $loan->loaned_at->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tenggat</th>
                            <td>: <span class="text-danger font-weight-bold">{{ $loan->due_date->format('d M Y') }}</span></td>
                        </tr>
                        <tr>
                            <th>Sisa Hari</th>
                            <td>:
                                @php $daysLeft = \Carbon\Carbon::today()->diffInDays($loan->due_date, false); @endphp
                                <small class="{{ $daysLeft < 0 ? 'text-danger' : 'text-success' }} font-weight-bold">
                                    {{ $daysLeft < 0 ? 'Terlambat ' . abs($daysLeft) . ' hari' : $daysLeft . ' hari' }}
                                </small>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Books --}}
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0"><i class="fas fa-book mr-2"></i>Buku Dipinjam</h6>
                </div>
                <div class="card-body" style="max-height:300px; overflow-y:auto;">
                    @foreach ($loan->loanItems as $item)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex">
                                @if ($item->book->cover_path)
                                    <img src="{{ asset('storage/' . $item->book->cover_path) }}"
                                        class="img-thumbnail mr-2" style="width:40px;height:54px;object-fit:cover;">
                                @endif
                                <div>
                                    <div class="small font-weight-bold">{{ Str::limit($item->book->title, 30) }}</div>
                                    <div class="small text-muted">×{{ $item->qty }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- RIGHT: Form --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0"><i class="fas fa-edit mr-2"></i>Form Request Perpanjangan</h6>
                </div>
                <form action="{{ route('loan-extensions.store', $loan->id) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle mr-2"></i>
                            Perpanjangan maksimal <strong>{{ $extensionDays }} hari</strong>,
                            limit 2 kali per peminjaman.
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Berapa hari perpanjang? <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="extension_days" class="form-control"
                                    value="{{ old('extension_days', $extensionDays) }}" min="1" max="{{ $extensionDays }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">hari</span>
                                </div>
                            </div>
                            <small class="text-muted">
                                Tenggat: <strong>{{ $loan->due_date->format('d M Y') }}</strong>
                                <i class="fas fa-arrow-right mx-2"></i>
                                <strong id="newDueDate">{{ \Carbon\Carbon::parse($loan->due_date)->addDays((int)$extensionDays)->format('d M Y') }}</strong>
                            </small>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label class="font-weight-bold">Alasan <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control" rows="4"
                                placeholder="Jelaskan alasan perpanjangan...">{{ old('reason') }}</textarea>
                            <small class="text-muted">Max 500 karakter</small>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check mr-2"></i>Ajukan Perpanjangan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.querySelector('input[name="extension_days"]').addEventListener('change', function() {
        const daysToAdd = parseInt(this.value);
        const currentDue = new Date('{{ $loan->due_date->toDateString() }}');
        currentDue.setDate(currentDue.getDate() + daysToAdd);

        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        const formatted = currentDue.toLocaleDateString('id-ID', options);
        document.getElementById('newDueDate').textContent = formatted.charAt(0).toUpperCase() + formatted.slice(1);
    });
</script>
@endpush
