<form action="{{ route('permissions.update', $isEdit->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label><strong>Permission Name:</strong></label>
        <input type="text" name="name" value="{{ old('name', $isEdit->name) }}" class="form-control" required>
    </div>
    <div class="d-flex justify-content-end" style="gap: 8px;">
        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-success">Update</button>
    </div>
</form>
