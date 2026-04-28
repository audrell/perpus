@extends('layouts.admin')

@section('title', 'Peminjaman')

@push('styles')
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">
            <div class="mb-2 mb-lg-0">
                <h4 class="text-dark">Data Peminjaman</h4>
            </div>
            <div class="d-flex flex-wrap align-items-center" style="gap:.4rem;">
                {{-- Export PDF with optional status filter --}}
                <select id="exportStatus" class="form-control form-control-sm" style="width:145px;">
                    <option value="">Semua Status</option>
                    <option value="BORROWED">Dipinjam</option>
                    <option value="RETURNED">Dikembalikan</option>
                </select>
                <select id="exportApprovalStatus" class="form-control form-control-sm" style="width:175px;">
                    <option value="">Semua Approval</option>
                    <option value="PENDING">Pending</option>
                    <option value="APPROVED">Approved</option>
                    <option value="REJECTED">Rejected</option>
                </select>
                <input type="date" id="exportStartDate" class="form-control form-control-sm" style="width:150px;" title="Tanggal pinjam awal">
                <input type="date" id="exportEndDate" class="form-control form-control-sm" style="width:150px;" title="Tanggal pinjam akhir">
                <a id="btnExportPdf" href="{{ route('loans.export.pdf') }}"
                   class="btn btn-danger btn-sm" target="_blank">
                    <i class="fas fa-file-pdf mr-1"></i> Export PDF
                </a>

                @if (auth()->user()->hasRole('member'))
                    <a href="{{ route('loans.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Buat Peminjaman Baru
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="data-loans">
                    <thead>
                        <tr class="bg-primary">
                            <th width="1px" class="text-center text-white">No</th>
                            <th class="text-center text-white">Kode Pinjam</th>
                            <th class="text-center text-white">Anggota</th>
                            <th class="text-center text-white">Tgl Pinjam</th>
                            <th class="text-center text-white">Tenggat</th>
                            <th class="text-center text-white">Status</th>
                            <th class="text-center text-white">Denda</th>
                            <th width="180px" class="text-center text-white" style="white-space: nowrap;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            // ✅ Inisialisasi DataTable dengan data() untuk kirim filter ke server
            var table = $('#data-loans').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                order: [[0, 'desc']],
                ajax: {
                    url: "{{ route('loans.index') }}",
                    data: function(d) {
                        // ✅ Kirim nilai filter ke controller
                        d.status        = $('#exportStatus').val();
                        d.approval_status = $('#exportApprovalStatus').val();
                        d.start_date    = $('#exportStartDate').val();
                        d.end_date      = $('#exportEndDate').val();
                    }
                },
                columns: [
                    { data: 'nomor',       name: 'nomor',       orderable: false, searchable: false, className: 'text-center' },
                    { data: 'loan_code',   name: 'loan_code',   className: 'text-center font-weight-bold' },
                    { data: 'member_name', name: 'member_name' },
                    { data: 'loaned_at',   name: 'loaned_at',   className: 'text-center' },
                    { data: 'due_date',    name: 'due_date',    className: 'text-center' },
                    { data: 'status',      name: 'status',      className: 'text-center', orderable: false, searchable: false },
                    { data: 'fine_total',  name: 'fine_total',  className: 'text-center', orderable: false, searchable: false },
                    { data: 'action',      name: 'action',      className: 'text-center', orderable: false, searchable: false },
                ],
            });

            // ✅ Reload tabel saat filter berubah
            $('#exportStatus, #exportApprovalStatus, #exportStartDate, #exportEndDate').on('change', function() {
                table.ajax.reload();
                updateExportUrl();
            });

            $(document).on('click', '.show_confirm', function(event) {
                event.preventDefault();
                var form = $(this).closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Data ini akan dihapus permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) { form.submit(); }
                });
            });
        });

        // Update export PDF link
        const baseUrl = "{{ route('loans.export.pdf') }}";
        function updateExportUrl() {
            const params = new URLSearchParams();
            const s  = document.getElementById('exportStatus').value;
            const as = document.getElementById('exportApprovalStatus').value;
            const sd = document.getElementById('exportStartDate').value;
            const ed = document.getElementById('exportEndDate').value;

            if (s)  params.append('status', s);
            if (as) params.append('approval_status', as);
            if (sd) params.append('start_date', sd);
            if (ed) params.append('end_date', ed);

            const query = params.toString();
            document.getElementById('btnExportPdf').href = query ? `${baseUrl}?${query}` : baseUrl;
        }
    </script>
@endpush
