@extends('layouts.admin')

@section('title', 'Role')

@push('styles')
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')

    <div class="container-fluid pt-4">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h3 class="h3 mb-0 text-gray-800">Role Management</h3>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalCreateRole">Create
                New Role
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif


    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2 pl-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @include('roles.modals.create', ['permission' => $permission])


    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="data-roles">
                    <thead>
                        <tr class="bg-primary">
                            <th width='1px' class="text-center text-white">No</th>
                            <th class="text-center text-white">Name</th>
                            <th class="text-center text-white">permission</th>
                            <th width="150px" class="text-center text-white">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    @foreach ($roles as $role)
        @include('roles.modals.show', ['role' => $role, 'permission' => $permission])

        @include('roles.modals.edit', ['role' => $role, 'permission' => $permission])
    @endforeach

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Fungsi universal untuk Select All
        // Cukup tambahkan id="checkAll" pada checkbox 'Pilih Semua' di tiap modal
        $(document).on('click', '#checkAllCreate', function() {
            $('#modalCreateRole .perm-check').prop('checked', this.checked);
        });

        // Untuk Select All di Modal Edit (karena ID-nya dinamis)
        // Kita gunakan class saja agar lebih mudah
        $(document).on('click', '[id^="checkAllEdit"]', function() {
            $(this).closest('.modal-content').find('.perm-check').prop('checked', this.checked);
        });
    });
</script>

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#data-roles').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: "{!! route('roles.index') !!}",
                columns: [{
                        data: 'nomor',
                        name: 'nomor'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'permission',
                        name: 'permission'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        able: false,
                        orderable: false,
                        className: 'text-center'
                    }
                ]
            });

            // Konfirmasi sebelum hapus
            $(document).on('click', '.show_confirm', function(event) {
                event.preventDefault(); // Mencegah submit default
                var form = $(this).closest("form");
                var id = $(this).data('id'); // Ambil ID data yang ingin dihapus

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This data will be deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, deleted!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Kirim form untuk menghapus data
                    }
                });
            });
        });

        document.querySelectorAll('.select-all').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const targetClass = this.getAttribute('data-target');
                const targetCheckboxes = document.querySelectorAll(`.${targetClass}`);
                targetCheckboxes.forEach(cb => cb.checked = this.checked);
            });
        });

        document.getElementById('rolesForm').addEventListener('submit', function() {
            document.querySelector('.btn-submit').classList.add('d-none');
            document.querySelector('.btn-reset').classList.add('d-none');
            document.querySelector('.btn-loading').classList.remove('d-none');
        });
    </script>
@endpush
