@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12 col-lg-12 mb-4 mb-lg-0">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Category Management</h1>
                <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#createCategoryModal">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Kategori
                </button>
            </div>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data-categories" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th width="150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>

        @include('auth.management.categories.modals.create')
    @endsection

    @push('scripts')
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

        <script>
            $(document).ready(function() {

                $('#data-categories').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{!! route('categories.index') !!}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ]
                });


                $(document).on('click', '.show_confirm', function(event) {
                    event.preventDefault();
                    var form = $(this).closest("form");

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Kategori dan buku di dalamnya akan terhapus!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
