
<div class="modal fade" id="modalShowRole{{ $role->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Role Details: {{ $role->name }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="max-height: calc(100vh - 210px); overflow-y: auto;">
                <div class="form-group mb-4">
                    <label class="font-weight-bold">Role Name:</label>
                    <input type="text" class="form-control" value="{{ $role->name }}" readonly>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="font-weight-bold mb-0">Assign Permissions:</label>
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
                                    <input type="checkbox" class="custom-control-input"
                                        id="perm_show_{{ $role->id }}_{{ $value->id }}"
                                        {{ in_array($value->id, $rolePermissionIds) ? 'checked' : '' }} disabled>
                                    <label class="custom-control-label" for="perm_show_{{ $role->id }}_{{ $value->id }}">
                                        {{ str_replace('-', ' ', explode('.', $value->name)[1] ?? $value->name) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                @if ($role->permissions->count() === 0)
                    <p class="text-muted mb-0">No permissions assigned.</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
