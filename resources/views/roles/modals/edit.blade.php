<div class="modal fade" id="modalEditRole{{ $role->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Role: {{ $role->name }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form method="POST" action="{{ route('roles.update', $role->id) }}">
                @csrf @method('PUT')
                <div class="modal-body" style="max-height: calc(100vh - 210px); overflow-y: auto;">
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Role Name:</label>
                        <input type="text" name="name" value="{{ $role->name }}" class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="font-weight-bold mb-0">Assign Permissions:</label>
                        {{-- Checkbox Select All --}}
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="checkAllEdit{{ $role->id }}">
                            <label class="custom-control-label text-primary font-weight-bold"
                                for="checkAllEdit{{ $role->id }}" style="cursor:pointer">
                                Pilih Semua
                            </label>
                        </div>
                    </div>
                    <hr class="mt-1">

                    <div class="row">
                        @php
                            $groupedPermissions = $permission->groupBy(function ($item) {
                                return explode('.', $item->name)[0] ?? $item->name;
                            });
                            $rolePermissionIds = $role->permissions->pluck('id')->toArray();
                        @endphp

                        @foreach ($groupedPermissions as $group => $permissions)
                            <div class="col-md-3 mb-3">
                                <div class="font-weight-bold text-primary text-uppercase mb-2">{{ $group }}</div>

                                @foreach ($permissions as $value)
                                    <div class="custom-control custom-checkbox text-capitalize mb-2">
                                        <input type="checkbox" name="permission[{{ $value->id }}]"
                                            value="{{ $value->id }}" class="custom-control-input perm-check"
                                            id="perm_edit_{{ $role->id }}_{{ $value->id }}"
                                            {{ in_array($value->id, $rolePermissionIds) ? 'checked' : '' }}>
                                        <label class="custom-control-label"
                                            for="perm_edit_{{ $role->id }}_{{ $value->id }}">
                                            {{ str_replace('-', ' ', explode('.', $value->name)[1] ?? $value->name) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow">Update Role</button>
                </div>
            </form>
        </div>
    </div>
</div>
