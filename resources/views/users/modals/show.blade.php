<div class="modal fade" id="modalShowUser{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">User Details: {{ $user->name }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" style="max-height: calc(100vh - 210px); overflow-y: auto;">
                <div class="form-group mb-2">
                    <label class="font-weight-bold">Name:</label>
                    <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                </div>

                <div class="form-group mb-2">
                    <label class="font-weight-bold">Email:</label>
                    <input type="text" class="form-control" value="{{ $user->email }}" readonly>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="font-weight-bold mb-0">Assigned Roles:</label>
                </div>
                <hr class="mt-1">

                <div class="row">
                    @forelse ($user->getRoleNames() as $roleName)
                        <div class="col-md-6 mb-3">
                            <div class="custom-control custom-checkbox text-capitalize mb-2">
                                <input type="checkbox" class="custom-control-input"
                                    id="showUserRole{{ $user->id }}{{ \Illuminate\Support\Str::slug($roleName, '-') }}" checked disabled>
                                <label class="custom-control-label"
                                    for="showUserRole{{ $user->id }}{{ \Illuminate\Support\Str::slug($roleName, '-') }}">
                                    {{ $roleName }}
                                </label>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-muted mb-0">No roles assigned.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
