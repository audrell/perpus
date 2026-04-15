@extends('layouts.admin')
@section('title', 'Members')

@push('styles')
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h3 class="h3 mb-0 text-gray-800">Member</h3>

    </div>

    <form action="{{ route('member.status', $member->id) }}" method="POST">
        @csrf
        @method('PUT')

        @if($member->status == 1)
            <button class="btn btn-success">Aktif</button>
        @else
            <button class="btn btn-danger">Nonaktif</button>
        @endif
    </form>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered w-100" id="data-member">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th width="5%" class="text-center">No</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Address</th>
                            <th class="text-center">Password</th>
                            <th class="text-center">Status</th>
                            <th width="15%" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $key => $m)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $m->name }}</td>

                                <td>{{ $m->user->email ?? 'Email tidak ditemukan' }}</td>

                                <td>{{ $m->phone }}</td>
                                <td>{{ $m->address }}</td>

                                <td class="text-center">
                                    <small class="text-muted">Encrypted</small>
                                </td>

                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('members.edit', $m->id) }}"
                                            class="btn btn-warning btn-sm text-white">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            // DataTable Initialization
            $('#data-members').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: "{{ route('members.index') }}",
                columns: [{
                        data: 'nomor',
                        name: 'nomor',
                        className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone',

                    },
                    {
                        data: 'address',
                        name: 'address',

                    } {
                        data: 'password',
                        name: 'password',

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

            // Check All Logic
            $(document).on('click', '#checkAllCreateRoles', function() {
                $('#modalCreateUser .role-check').prop('checked', this.checked);
            });

            $(document).on('click', '[id^="checkAllEditRoles"]', function() {
                $(this).closest('.modal-content').find('.role-check').prop('checked', this.checked);
            });

            // SweetAlert Confirm
            $(document).on('click', '.show_confirm', function(event) {
                event.preventDefault();
                var form = $(this).closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This data will be deleted!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
