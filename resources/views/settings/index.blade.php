@extends('layouts.admin')
@section('title', 'Setting')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h4 class="text-dark">Setting</h4>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 font-weight-bold text-dark">
                {{ $setting ? 'Edit Setting' : 'Buat Setting' }}
            </h5>
        </div>
        <div class="card-body">
            @if ($setting)
                <form action="{{ route('settings.update', $setting->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                @else
                    <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name_app" class="form-control @error('name_app') is-invalid @enderror"
                            value="{{ old('name_app', $setting->name_app ?? '') }}" placeholder="Contoh: MyApp Admin">
                        @error('name_app')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Singkatan / Shortcut <span class="text-danger">*</span></label>
                        <input type="text" name="short_cut_app"
                            class="form-control @error('short_cut_app') is-invalid @enderror"
                            value="{{ old('short_cut_app', $setting->short_cut_app ?? '') }}" placeholder="Contoh: MA">
                        @error('short_cut_app')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Tampil di sidebar sebagai brand icon.</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Logo</label>
                <div class="custom-file">
                    <input type="file" name="image" class="custom-file-input @error('image') is-invalid @enderror"
                        id="imageInput" accept="image/*">
                    <label class="custom-file-label" for="imageInput">Pilih gambar...</label>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <small class="text-muted">Format: JPG, JPEG, PNG, WEBP. Maks 2MB. Kosongkan jika tidak ingin mengubah
                    logo.</small>
            </div>

            @if ($setting && $setting->image)
                <div class="form-group">
                    <label class="font-weight-bold">Logo Saat Ini</label><br>
                    <img src="{{ asset('storage/uploads/logos/' . $setting->image) }}" alt="Logo" class="img-thumbnail"
                        style="max-height: 100px;">
                </div>
            @endif

            <hr>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-2"></i>{{ $setting ? 'Perbarui Setting' : 'Simpan Setting' }}
            </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Update custom file label saat file dipilih
        document.getElementById('imageInput').addEventListener('change', function() {
            var fileName = this.files[0] ? this.files[0].name : 'Pilih gambar...';
            this.nextElementSibling.textContent = fileName;
        });
    </script>
@endpush
