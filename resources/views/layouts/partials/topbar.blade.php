<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar shadow">

     <button id="sidebarToggle" class="btn btn-link rounded-circle mr-3 d-none d-md-inline-block">
        <i class="fa fa-bars" style="color:#858796;"></i>
    </button>

    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <h4 class="text-dark font-weight-bold">{{ $setting->name_app ?? config('app.name') }}</h4>

    <ul class="navbar-nav ml-auto">
        @auth
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                    <img src="{{ Auth::user()->profile_photo_url }}"
                         class="img-profile rounded-circle"
                         style="width: 2rem; height: 2rem; object-fit: cover;">
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalEditProfile">
                        <i class="fas fa-user-edit fa-sm fa-fw mr-2 text-gray-400"></i>
                        Edit Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item border-0 bg-transparent text-left w-100">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </li>
        @endauth
    </ul>
</nav>

{{-- Modal Edit Profile --}}
@auth
<div class="modal fade" id="modalEditProfile" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-edit mr-2"></i>Edit Profile</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif

                {{-- Preview Foto --}}
                <div class="text-center mb-3">
                    <img src="{{ Auth::user()->profile_photo_url }}"
                         id="previewPhoto"
                         class="rounded-circle border"
                         style="width:90px; height:90px; object-fit:cover;">
                    <div class="mt-1 text-muted small">{{ Auth::user()->name }}</div>
                </div>

                {{-- Form Upload Foto --}}
                <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="font-weight-bold">Foto Profile</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="profile_photo"
                                   id="inputFotoProfile" accept="image/*" onchange="previewProfileImage(this)">
                            <label class="custom-file-label" for="inputFotoProfile">Pilih foto...</label>
                        </div>
                        <small class="text-muted">Format: JPG, PNG, WEBP. Maks 2MB.</small>
                    </div>
                    <button type="submit" class="btn btn-info btn-sm btn-block mb-3">
                        <i class="fas fa-camera mr-1"></i> Simpan Foto
                    </button>
                </form>

                <hr>

                {{-- Form Edit Nama & Email --}}
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="font-weight-bold">Nama</label>
                        <input type="text" class="form-control" name="name"
                               value="{{ Auth::user()->name }}" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Email</label>
                        <input type="email" class="form-control" name="email"
                               value="{{ Auth::user()->email }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm btn-block">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
function previewProfileImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewPhoto').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
        input.nextElementSibling.innerHTML = input.files[0].name;
    }
}
</script>
@endauth
