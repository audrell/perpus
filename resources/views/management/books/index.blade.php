@extends('layouts.admin')
@section('title', 'Books Management')

@push('styles')
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css" rel="stylesheet">
    <style>
        .badge-stock {
            font-size: 0.85rem;
        }
    </style>
@endpush

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <h4 class="text-dark">Manajemen Buku</h4>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-md-8 d-flex flex-wrap mb-2 mb-md-0" style="gap:.4rem;">
            <a href="{{ route('books.export') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-file-excel mr-1"></i>
                <span class="d-none d-sm-inline">Export Excel</span>
                <span class="d-sm-none">Excel</span>
            </a>
            <a href="{{ route('books.import.template') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-download mr-1"></i>
                <span class="d-none d-sm-inline">Download Template Import</span>
                <span class="d-sm-none">Template</span>
            </a>
            <button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal"
                data-target="#modalImportBook">
                <i class="fas fa-file-upload mr-1"></i>
                <span class="d-none d-sm-inline">Upload Import</span>
                <span class="d-sm-none">Upload</span>
            </button>
        </div>
        <div class="col-12 col-md-4 text-md-right">
            <button type="button" class="btn btn-primary btn-sm btn-block d-md-inline-block" data-toggle="modal"
                data-target="#modalCreateBook">
                <i class="fas fa-plus mr-1"></i> Create New Book
            </button>
        </div>
    </div>


    <div class="modal fade" id="modalImportBook" tabindex="-1" role="dialog" aria-labelledby="modalImportBookLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImportBookLabel">IMPORT DATA BUKU</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('books.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bold">PILIH FILE EXCEL</label>
                            <input type="file" name="import_file" class="form-control" accept=".xlsx, .xls" required>
                            <small class="text-muted">Format file: .xlsx atau .xls</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
                        <button type="submit" class="btn btn-success">MULAI IMPORT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('layouts.partials.alert')
    @include('management.books.modals.create')

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="data-books" width="100%">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th>No</th>
                            <th>Cover Path</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th>Year</th>
                            <th>Lokasi Rak</th>
                            <th>Kategori</th>
                            <th>Stok total</th>
                            <th>Stok available</th>
                            <th>ISBN</th>
                            <th width="100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @foreach ($books as $book)
        @include('management.books.modals.show', ['book' => $book])
        @include('management.books.modals.edit', [
            'book' => $book,
            'categories' => $categories,
        ])
    @endforeach
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#data-books').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: "{{ route('books.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'cover',
                        name: 'cover',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'author',
                        name: 'author'
                    },
                    {
                        data: 'publisher',
                        name: 'publisher'
                    },
                    {
                        data: 'year',
                        name: 'year'
                    },
                    {
                        data: 'rack_location',
                        name: 'rack_location'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'quantity_total',
                        name: 'quantity_total'
                    },
                    {
                        data: 'quantity_available',
                        name: 'quantity_available'
                    },
                    {
                        data: 'isbn',
                        name: 'isbn'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });

        // SweetAlert Delete Confirmation
        $(document).on('click', '.show_confirm', function(event) {
            event.preventDefault();
            var id = $(this).data('id'); // ← ambil data-id
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Data buku ini akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/books/' + id,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            $('#data-books').DataTable().ajax.reload();
                            Swal.fire('Terhapus!', 'Buku berhasil dihapus.', 'success');
                        }
                    });
                }
            });
        });
    </script>
@endpush
