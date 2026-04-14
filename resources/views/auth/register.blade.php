<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $setting = \App\Models\SettingApp::first();
        $appName = $setting->name_app ?? config('app.name');
        $logo = !empty($setting->image) ? asset('storage/uploads/logos/' . $setting->image) : null;
    @endphp
    <title>Register - {{ $appName }}</title>

    <link rel="stylesheet" href="{{ asset('asset/vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/auth-sb-admin-2.css') }}">
</head>

<body>
    <div class="container login-wrapper d-flex align-items-center justify-content-center">
        <div class="row w-100 justify-content-center">
            <div class="col-lg-12 col-xl-12">
                <div class="card login-shell">
                    <div class="row no-gutters">
                        <div
                            class="col-lg-7 d-none d-lg-flex login-hero p-4 p-xl-5 flex-column justify-content-between">
                            <div class="hero-badge mb-4">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div>
                                <h3 class="font-weight-bold mb-3">{{ $appName }}</h3>
                                <p class="mb-0" style="opacity:.9;">Buat akun baru untuk mulai mengelola aplikasi
                                    dengan aman.</p>
                            </div>
                            <small style="opacity:.8;">© {{ date('Y') }} {{ $appName }}</small>
                        </div>

                        <div class="col-lg-5">
                            <div class="card login-card">
                                <div class="card-body p-4 p-md-5">
                                    <div class="text-center mb-4">
                                        @if ($logo)
                                            <img src="{{ $logo }}" alt="Logo {{ $appName }}"
                                                class="brand-logo mb-3">
                                        @else
                                            <div class="mb-3">
                                                <span class="d-inline-flex align-items-center justify-content-center"
                                                    style="width:76px;height:76px;border-radius:14px;background:#4e73df;color:#fff;">
                                                    <i class="fas fa-building fa-lg"></i>
                                                </span>
                                            </div>
                                        @endif

                                        <h4 class="font-weight-bold text-dark mb-1">Buat Akun</h4>
                                        <p class="text-muted mb-0">Daftar akun baru di {{ $appName }}</p>
                                    </div>

                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ $errors->first() }}
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('register.reg') }}" class="mb-2">
                                        @csrf

                                        <div class="form-group">
                                            <label for="name" class="font-weight-600">Nama</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white"><i
                                                            class="fas fa-user text-muted"></i></span>
                                                </div>
                                                <input id="name" type="text"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    name="name" value="{{ old('name') }}"
                                                    placeholder="Masukkan nama lengkap" required autocomplete="name"
                                                    autofocus>
                                            </div>
                                            @error('name')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                            @enderror
                                        </div>


                                        <div class="form-group">
                                            <label for="email" class="font-weight-600">Email</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white"><i
                                                            class="fas fa-envelope text-muted"></i></span>
                                                </div>
                                                <input id="email" type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    name="email" value="{{ old('email') }}"
                                                    placeholder="nama@email.com" required autocomplete="email">
                                            </div>
                                            @error('email')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="address" class="font-weight-600">Alamat</label>

                                            <div class="input-group">
                                                <div class="input-group-text bg-white">
                                                    <span class="input-group-text bg-white">
                                                        <i class="bi bi-geo-alt-fill text-muted"></i>
                                                    </span>
                                                </div>

                                                <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror"
                                                    placeholder="Masukkan alamat" required autocomplete="street-address">{{ old('address') }}</textarea>
                                            </div>

                                            @error('address')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="phone" class="font-weight-600">Phone</label>

                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white">
                                                        <i class="bi bi-telephone-fill text-muted"></i>
                                                    </span>
                                                </div>

                                                <input id="phone" type="tel"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    name="phone" placeholder="Masukkan nomor telepon"
                                                    value="{{ old('phone') }}" required autocomplete="tel"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            </div>

                                            @error('phone')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="password" class="font-weight-600">Password</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white"><i
                                                            class="fas fa-lock text-muted"></i></span>
                                                </div>
                                                <input id="password" type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" placeholder="Masukkan password" required
                                                    autocomplete="new-password">
                                            </div>
                                            @error('password')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="password-confirm" class="font-weight-600">Konfirmasi
                                                Password</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white"><i
                                                            class="fas fa-check-circle text-muted"></i></span>
                                                </div>
                                                <input id="password-confirm" type="password" class="form-control"
                                                    name="password_confirmation" placeholder="Ulangi password"
                                                    required autocomplete="new-password">
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-block btn-login mt-3 mb-2">
                                            Daftar
                                        </button>

                                        <div class="text-center mt-3">
                                            <a href="{{ route('login') }}" class="small">Sudah punya akun? Login</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('asset/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('asset/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
