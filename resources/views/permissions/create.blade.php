<form action="{{ route('permissions.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="name"><strong>Permission Name:</strong></label>
        <input type="text" name="name" class="form-control" placeholder="roles.index" value="{{ old('name') }}" required autofocus>
        <small class="text-muted">Gunakan format: modul.aksi (contoh: roles.index)</small>
    </div>
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
