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
    <title>Login</title>

    <link rel="stylesheet" href="{{ asset('asset/vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <h3 class="font-weight-bold mb-3">{{ $appName }}</h3>
                                <p class="mb-0" style="opacity:.9;">Panel manajemen aplikasi yang aman, cepat, dan
                                    mudah digunakan.</p>
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

                                        <h4 class="font-weight-bold text-dark mb-1">Selamat Datang</h4>
                                        <p class="text-muted mb-0">Masuk ke akun {{ $appName }}</p>
                                    </div>

                                    @if (session()->has('loginError'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ session('loginError') }}
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif

                                    @if ($errors->any())
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            {{ $errors->first() }}
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif

                                    <form action="{{ route('login') }}" method="POST" class="mb-2">
                                        @csrf

                                        <div class="form-group">
                                            <label for="email" class="font-weight-600">Email</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white"><i
                                                            class="fas fa-envelope text-muted"></i></span>
                                                </div>
                                                <input type="email" name="email" id="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    placeholder="nama@email.com" value="{{ old('email') }}" required
                                                    autofocus>
                                            </div>
                                            @error('email')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="password" class="font-weight-600">Password</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white"><i
                                                            class="fas fa-lock text-muted"></i></span>
                                                </div>
                                                <input type="password" name="password" id="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    placeholder="Masukkan password" required>
                                            </div>
                                            @error('password')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary  btn-block btn-login mt-3 mb-1">
                                            Login
                                        </button>
                                        <div class="mt-4 text-center">
                                            <p class="mb-0">belum punya akun?
                                                <a href="{{ route('register') }}"
                                                    class="text-primary font-weight-bold">register</a>
                                            </p>
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
