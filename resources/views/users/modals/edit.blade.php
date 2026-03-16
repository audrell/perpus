<div class="modal fade" id="modalEditUser{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit User: {{ $user->name }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf @method('PUT')
                <div class="modal-body" style="max-height: calc(100vh - 210px); overflow-y: auto;">
                    <div class="form-group mb-2">
                        <label class="font-weight-bold">Name:</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="font-weight-bold">Email:</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="font-weight-bold">Password:</label>
                        <input type="password" name="password" class="form-control"
                            placeholder="Kosongkan jika tidak ganti">
                    </div>
                    <div class="form-group mb-2">
                        <label class="font-weight-bold">Confirm Password:</label>
                        <input type="password" name="confirm-password" class="form-control"
                            placeholder="Kosongkan jika tidak ganti">
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="font-weight-bold mb-0">Assign Roles:</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="checkAllEditRoles{{ $user->id }}">
                            <label class="custom-control-label text-primary font-weight-bold"
                                for="checkAllEditRoles{{ $user->id }}" style="cursor:pointer">
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
                                            id="editUser{{ $user->id }}Role{{ \Illuminate\Support\Str::slug($value, '-') }}"
                                            value="{{ $value }}"
                                            {{ in_array($value, $user->roles->pluck('name')->toArray()) ? 'checked' : '' }}>
                                        <label class="custom-control-label"
                                            for="editUser{{ $user->id }}Role{{ \Illuminate\Support\Str::slug($value, '-') }}">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>
