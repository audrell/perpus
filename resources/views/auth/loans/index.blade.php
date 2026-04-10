@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h3 class="h3 mb-0 text-gray-800">Loans Management</h3>
        <a href="{{ route('loans.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Create New Loan
        </a>
    </div>

    <div class="mb-3">
        <div class="btn-group" role="group" aria-label="Filter Status">
            <button type="button" class="btn btn-outline-primary filter-btn active" data-filter="active">Sedang Dipinjam</button>
            <button type="button" class="btn btn-outline-success filter-btn" data-filter="returned">Sudah Kembali</button>
            <button type="button" class="btn btn-outline-danger filter-btn" data-filter="rejected">Ditolak</button>
            <button type="button" class="btn btn-outline-secondary filter-btn" data-filter="all">Semua Riwayat</button>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered w-100" id="data-loans">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th width="5%" class="text-center">No</th>
                            <th>Loan Code</th>
                            <th>Member</th>
                            <th>Loan Date</th>
                            <th>Due Date</th>
                            <th class="text-center">Status</th>
                            <th width="10%" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail --}}
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Peminjaman</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalBodyDetail">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            let currentFilter = 'active';

            let table = $('#data-loans').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('loans.index') }}",
                    data: function (d) {
                        d.status_filter = currentFilter;
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'loan_code', name: 'loan_code' },
                    { data: 'member_name', name: 'member_name' },
                    {
                        data: 'loaned_at',
                        name: 'loaned_at',
                        render: function(data) { return data ? data.substring(0, 10) : '-'; }
                    },
                    {
                        data: 'due_date',
                        name: 'due_date',
                        render: function(data) { return data ? data.substring(0, 10) : '-'; }
                    },
                    { data: 'approval_status', name: 'approval_status', className: 'text-center' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ]
            });

            $('.filter-btn').on('click', function() {
                $('.filter-btn').removeClass('active');
                $(this).addClass('active');
                currentFilter = $(this).data('filter');
                table.ajax.reload();
            });

            $(document).on('click', '.approve-btn', function() {
                handleAction($(this).data('id'), "{{ route('book-loans.approve', ':id') }}", 'Setujui Peminjaman?', 'warning', 'Ya, Setujui!');
            });

            $(document).on('click', '.reject-btn', function() {
                handleAction($(this).data('id'), "{{ route('book-loans.reject', ':id') }}", 'Tolak Peminjaman?', 'error', 'Ya, Tolak!');
            });

            $(document).on('click', '.return-btn', function() {
                handleAction($(this).data('id'), "{{ route('book-loans.return', ':id') }}", 'Proses Pengembalian?', 'question', 'Ya, Kembalikan!');
            });

            function handleAction(id, urlTemplate, title, icon, confirmText) {
                let url = urlTemplate.replace(':id', id);
                Swal.fire({
                    title: title,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post(url, { _token: '{{ csrf_token() }}' }, function(response) {
                            Swal.fire('Berhasil!', response.success, 'success');
                            table.ajax.reload();
                        }).fail(function() {
                            Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
                        });
                    }
                });
            }

            $(document).on('click', '.show-btn', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let url = "{{ route('loans.show', ':id') }}".replace(':id', id);

                $('#modalBodyDetail').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
                $('#modalDetail').modal('show');

                $.get(url, function(data) {
                    $('#modalBodyDetail').html(data);
                }).fail(function() {
                    $('#modalBodyDetail').html('<div class="alert alert-danger">Gagal mengambil data.</div>');
                });
            });
        });
    </script>
@endpush
