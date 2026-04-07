@extends('layouts.admin')
@section('title', 'Data Peminjaman')

@push('styles')
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h3 class="h3 mb-0 text-gray-800">Loans Management</h3>
        <a href="{{ route('loans.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Create New Loan
        </a>
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
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#data-loans').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('loans.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'loan_code', name: 'loan_code' },
                    { data: 'member_name', name: 'member_name' },
                    { data: 'loaned_at', name: 'loaned_at' },
                    { data: 'due_date', name: 'due_date' },
                    { data: 'status', name: 'status', className: 'text-center' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ]
            });
        });
    </script>
@endpush
