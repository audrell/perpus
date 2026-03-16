
<div class="modal fade" id="modalCreateUser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Create New User</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="modal-body" style="max-height: calc(100vh - 210px); overflow-y: auto;">
                    <div class="form-group mb-2">
                        <label class="font-weight-bold">Name:</label>
                        <input type="text" name="name" placeholder="Full Name" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="font-weight-bold">Email:</label>
                        <input type="email" name="email" placeholder="Email Address" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="font-weight-bold">Password:</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="font-weight-bold">Confirm Password:</label>
                        <input type="password" name="confirm-password" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="font-weight-bold mb-0">Assign Roles:</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="checkAllCreateRoles">
                            <label class="custom-control-label text-primary font-weight-bold" for="checkAllCreateRoles"
                                style="cursor:pointer">
                                Pilih Semua
                            </label>
                        </div>
                    </div>
                    <hr class="mt-1">

                    <div class="border rounded p-3">
                        <div class="row">
                            @foreach ($roles as $value => $label)
                                <div class="col-md-6 mb-2">
                                    <div class="custom-control custom-checkbox text-capitalize">
                                        <input class="custom-control-input role-check" type="checkbox" name="roles[]"
                                            id="createRole{{ \Illuminate\Support\Str::slug($value, '-') }}"
                                            value="{{ $value }}">
                                        <label class="custom-control-label"
                                            for="createRole{{ \Illuminate\Support\Str::slug($value, '-') }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">*Pilih minimal satu role</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary px-4 shadow">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
