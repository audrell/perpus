@extends('layouts.admin')

@section('title', 'Buat Peminjaman')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css" rel="stylesheet">
    <style>
        .book-row { transition: background .15s; }
        .book-row:hover { background: #f8f9fc; }
        .stock-info { font-size: 0.78rem; }
    </style>
@endpush

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="text-dark mb-0">Buat Peminjaman Baru</h4>
        <a href="{{ route('loans.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1 pl-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <form action="{{ route('loans.store') }}" method="POST" id="loanForm">
        @csrf

        {{-- Informasi Peminjaman --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-2">
                <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Informasi Peminjaman</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Anggota</label>
                            <input type="text" class="form-control"
                                value="[{{ $member->member_code }}] {{ $member->name }} ({{ ucfirst($member->type) }})" readonly>
                            <small class="text-muted">Anggota otomatis berdasarkan akun yang login.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Tenggat Pengembalian <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" id="due_date"
                                class="form-control @error('due_date') is-invalid @enderror"
                                value="{{ old('due_date', \Carbon\Carbon::today()->addDays(7)->format('Y-m-d')) }}"
                                min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Buku --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-2 d-flex align-items-center justify-content-between">
                <h6 class="mb-0"><i class="fas fa-book mr-2"></i>Buku yang Dipinjam</h6>
                <button type="button" id="btnAddBook" class="btn btn-light btn-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Buku
                </button>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0" id="bookTable">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" width="40px">No</th>
                            <th>Judul Buku</th>
                            <th class="text-center" width="110px">Stok Tersedia</th>
                            <th class="text-center" width="120px">Jumlah Pinjam</th>
                            <th class="text-center" width="60px">Hapus</th>
                        </tr>
                    </thead>
                    <tbody id="bookRows">
                        {{-- one empty row on load --}}
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save mr-1"></i> Simpan Peminjaman
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Book data as JS map: id -> {title, available}
        const booksData = {
            @foreach ($books as $book)
            {{ $book->id }}: {
                title: @json($book->title),
                available: {{ $book->quantity_available }},
            },
            @endforeach
        };

        let rowCounter = 0;

        function buildRow(index, oldBookId, oldQty) {
            const id = 'row_' + index;
            let options = '<option value="">-- Pilih Buku --</option>';
            @foreach ($books as $book)
            options += `<option value="{{ $book->id }}" ${oldBookId == {{ $book->id }} ? 'selected' : ''}>{{ addslashes($book->title) }} — ({{ $book->author }})</option>`;
            @endforeach

            return `
            <tr class="book-row" id="${id}">
                <td class="text-center align-middle row-num">${index}</td>
                <td>
                    <select name="books[${index}][book_id]" class="form-control book-select" style="width:100%">
                        ${options}
                    </select>
                </td>
                <td class="text-center align-middle">
                    <span class="badge badge-info stock-display stock-info">-</span>
                </td>
                <td class="text-center align-middle">
                    <input type="number" name="books[${index}][qty]" class="form-control text-center qty-input"
                        value="${oldQty || 1}" min="1" max="99">
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-danger btn-sm btn-remove-row">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>`;
        }

        function addRow(oldBookId, oldQty) {
            rowCounter++;
            $('#bookRows').append(buildRow(rowCounter, oldBookId, oldQty));
            const newSelect = $('#bookRows tr:last .book-select');
            newSelect.select2({ theme: 'bootstrap4', width: '100%' });
            newSelect.trigger('change');
            reNumberRows();
        }

        function reNumberRows() {
            $('#bookRows tr').each(function (i) {
                $(this).find('.row-num').text(i + 1);
            });
        }

        $(document).ready(function () {
            // Old input restoration
            @if (old('books'))
                @foreach (old('books', []) as $i => $item)
                addRow('{{ $item['book_id'] ?? '' }}', '{{ $item['qty'] ?? 1 }}');
                @endforeach
            @elseif (!empty($preselectedBookId))
                addRow('{{ $preselectedBookId }}', 1);
            @else
                addRow(); // default 1 empty row
            @endif

            // Add book row
            $('#btnAddBook').on('click', function () { addRow(); });

            // Remove row
            $(document).on('click', '.btn-remove-row', function () {
                if ($('#bookRows tr').length <= 1) {
                    Swal.fire('Peringatan', 'Minimal harus ada 1 buku yang dipinjam.', 'warning');
                    return;
                }
                $(this).closest('tr').remove();
                reNumberRows();
            });

            // Update stock display on book change
            $(document).on('change', '.book-select', function () {
                const bookId  = $(this).val();
                const $row    = $(this).closest('tr');
                const $stock  = $row.find('.stock-display');
                const $qty    = $row.find('.qty-input');

                if (bookId && booksData[bookId] !== undefined) {
                    const avail = booksData[bookId].available;
                    $stock.text(avail).removeClass('badge-secondary').addClass('badge-info');
                    $qty.attr('max', avail);
                } else {
                    $stock.text('-').removeClass('badge-info').addClass('badge-secondary');
                    $qty.attr('max', 99);
                }
            });

            // Trigger change on all existing rows to init stock display
            $('.book-select').trigger('change');
        });
    </script>
@endpush
