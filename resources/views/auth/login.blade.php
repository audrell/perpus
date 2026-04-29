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
    <title>Login – {{ $appName }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/vendor/fontawesome-free/css/all.min.css') }}">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue:       #4361ee;
            --blue-dark:  #3a0ca3;
            --blue-light: #4cc9f0;
            --accent:     #f72585;
            --surface:    #ffffff;
            --text:       #1a1a2e;
            --muted:      #6c757d;
            --radius:     20px;
            --shadow:     0 24px 60px rgba(67,97,238,.18);
            --transition: 0.65s cubic-bezier(.77,0,.18,1);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #e0e7ff 0%, #f0f4ff 50%, #dbeafe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            overflow-x: hidden;
        }

        /* ── Shell ── */
        .shell {
            position: relative;
            width: 860px;
            max-width: 100%;
            height: 680px;
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            display: flex;
            flex-shrink: 0;
        }

        /* ── Forms container ── */
        .forms-wrap {
            position: absolute;
            inset: 0;
            display: flex;
            width: 100%;
            height: 100%;
        }

        /* ── Individual form panels ── */
        .form-panel {
            position: absolute;
            top: 0;
            width: 50%;
            height: 100%;
            padding: 48px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 0;
            transition: transform var(--transition), opacity var(--transition);
            background: var(--surface);
        }

        .form-panel.login-panel  { left: 0;   transform: translateX(0);    opacity: 1; z-index: 2; }
        .form-panel.signup-panel { left: 50%; transform: translateX(100%); opacity: 0; z-index: 1; }

        /* ── Shifted state (showing register) ── */
        .shell.active .form-panel.login-panel  { transform: translateX(-100%); opacity: 0; z-index: 1; }
        .shell.active .form-panel.signup-panel { transform: translateX(0);     opacity: 1; z-index: 2; }

        /* ── Overlay panel (the blue side) ── */
        .overlay-wrap {
            position: absolute;
            top: 0; right: 0;
            width: 50%;
            height: 100%;
            border-radius: var(--radius);
            overflow: hidden;
            transition: transform var(--transition);
            z-index: 10;
        }

        .shell.active .overlay-wrap {
            transform: translateX(-100%);
        }

        .overlay {
            position: relative;
            width: 200%;
            height: 100%;
            display: flex;
            flex-direction: row;
            transition: transform var(--transition);
        }

        .shell.active .overlay { transform: translateX(-50%); }

        .overlay-panel {
            width: 50%;
            flex-shrink: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 18px;
            padding: 48px 36px;
            color: #fff;
            text-align: center;
            background: linear-gradient(145deg, var(--blue), var(--blue-dark));
        }

        /* decorative blobs */
        .overlay-panel::before,
        .overlay-panel::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            opacity: .18;
        }
        .overlay-panel::before {
            width: 300px; height: 300px;
            background: var(--blue-light);
            top: -80px; right: -80px;
        }
        .overlay-panel::after {
            width: 200px; height: 200px;
            background: var(--accent);
            bottom: -60px; left: -60px;
        }

        /* Logo / avatar */
        .logo-wrap {
            width: 76px; height: 76px;
            border-radius: 50%;
            overflow: hidden;
            background: rgba(255,255,255,.15);
            border: 3px solid rgba(255,255,255,.4);
            display: flex; align-items: center; justify-content: center;
            backdrop-filter: blur(6px);
            flex-shrink: 0;
        }
        .logo-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .logo-wrap i { font-size: 2rem; color: #fff; }

        .overlay-panel h2 {
            font-size: 1.65rem;
            font-weight: 800;
            letter-spacing: -.5px;
            position: relative; z-index: 1;
        }

        .overlay-panel p {
            font-size: .88rem;
            opacity: .85;
            line-height: 1.55;
            position: relative; z-index: 1;
        }

        /* Overlay CTA button */
        .btn-ghost {
            margin-top: 8px;
            padding: 11px 34px;
            border: 2px solid rgba(255,255,255,.8);
            border-radius: 50px;
            background: transparent;
            color: #fff;
            font-family: inherit;
            font-size: .9rem;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: .5px;
            transition: background .25s, color .25s, transform .2s;
            position: relative; z-index: 1;
        }
        .btn-ghost:hover { background: #fff; color: var(--blue); transform: translateY(-2px); }

        /* ── Form elements ── */
        .form-head { margin-bottom: 28px; }
        .form-head h3 {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -.5px;
        }
        .form-head p { font-size: .84rem; color: var(--muted); margin-top: 4px; }

        .field { position: relative; margin-bottom: 16px; }
        .field label {
            display: block;
            font-size: .78rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .6px;
        }
        .field .input-icon {
            position: relative;
            display: flex;
            align-items: center;
        }
        .field .input-icon i {
            position: absolute;
            left: 14px;
            color: #adb5bd;
            font-size: .85rem;
            pointer-events: none;
            transition: color .2s;
        }
        .field input {
            width: 100%;
            padding: 11px 14px 11px 38px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-family: inherit;
            font-size: .9rem;
            color: var(--text);
            background: #f8faff;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        .field input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(67,97,238,.12);
            background: #fff;
        }
        .field input:focus + i,
        .field .input-icon:focus-within i { color: var(--blue); }

        /* icon AFTER input trick: swap order in HTML */
        .field .input-icon input { order: 1; }
        .field .input-icon i    { order: 2; left: auto; right: 14px; /* reposition to right if needed */ }

        /* keep icons on the LEFT */
        .icon-left { left: 14px !important; right: auto !important; }

        .btn-primary {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 50px;
            background: linear-gradient(90deg, var(--blue), var(--blue-dark));
            color: #fff;
            font-family: inherit;
            font-size: .95rem;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: .4px;
            transition: opacity .2s, transform .2s, box-shadow .2s;
            box-shadow: 0 6px 20px rgba(67,97,238,.35);
            margin-top: 8px;
        }
        .btn-primary:hover { opacity: .9; transform: translateY(-2px); box-shadow: 0 10px 28px rgba(67,97,238,.4); }
        .btn-primary:active { transform: translateY(0); }

        /* Alert */
        .alert {
            padding: 10px 14px;
            border-radius: 10px;
            font-size: .83rem;
            margin-bottom: 14px;
            display: flex; align-items: center; gap: 8px;
        }
        .alert-danger  { background: #fff0f3; color: #c9184a; border: 1px solid #ffb3c6; }
        .alert-warning { background: #fff8e6; color: #b45309; border: 1px solid #fcd34d; }
        .alert i { flex-shrink: 0; }

        /* Copyright in overlay */
        .copy { font-size: .75rem; opacity: .6; margin-top: 12px; position: relative; z-index: 1; }

        /* ── Responsive ── */
        .signup-panel::-webkit-scrollbar { width: 4px; }
        .signup-panel::-webkit-scrollbar-track { background: transparent; }
        .signup-panel::-webkit-scrollbar-thumb { background: #c7d2fe; border-radius: 4px; }

        @media (max-width: 680px) {
            .shell { height: auto; flex-direction: column; }
            .overlay-wrap { display: none; }
            .form-panel { position: static; width: 100%; transform: none !important; opacity: 1 !important; }
            .form-panel.signup-panel { display: none; }
            .shell.active .form-panel.login-panel  { display: none; }
            .shell.active .form-panel.signup-panel { display: flex; }
            body { align-items: flex-start; padding: 16px; }
        }
    </style>
</head>

<body>

<div class="shell" id="shell">

    {{-- ── Forms ── --}}
    <div class="forms-wrap">

        {{-- LOGIN --}}
        <div class="form-panel login-panel">
            <div class="form-head">
                <h3>Selamat Datang</h3>
                <p>Masuk ke akun {{ $appName }}</p>
            </div>

            @if (session()->has('loginError'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('loginError') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="field">
                    <label for="email">Email</label>
                    <div class="input-icon">
                        <i class="fas fa-envelope icon-left"></i>
                        <input type="email" name="email" id="email"
                               placeholder="nama@email.com"
                               value="{{ old('email') }}" required autofocus>
                    </div>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-icon">
                        <i class="fas fa-lock icon-left"></i>
                        <input type="password" name="password" id="password"
                               placeholder="Masukkan password" required>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Login</button>
            </form>
        </div>

        {{-- REGISTER --}}
        <div class="form-panel signup-panel" style="overflow-y:auto; padding-top:40px; padding-bottom:40px;">
            <div class="form-head">
                <h3>Buat Akun</h3>
                <p>Daftarkan diri kamu sekarang</p>
            </div>

            <form action="{{ route('register.reg') }}" method="POST">
                @csrf
                <div class="field">
                    <label for="reg_name">Nama Lengkap</label>
                    <div class="input-icon">
                        <i class="fas fa-user icon-left"></i>
                        <input type="text" name="name" id="reg_name"
                               placeholder="Nama kamu" value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="field">
                    <label for="reg_email">Email</label>
                    <div class="input-icon">
                        <i class="fas fa-envelope icon-left"></i>
                        <input type="email" name="email" id="reg_email"
                               placeholder="nama@email.com" value="{{ old('email') }}" required>
                    </div>
                </div>
                <div class="field">
                    <label for="reg_phone">Nomor Handphone</label>
                    <div class="input-icon">
                        <i class="fas fa-phone icon-left"></i>
                        <input type="tel" name="phone" id="reg_phone"
                               placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" required>
                    </div>
                </div>
                <div class="field">
                    <label for="reg_address">Alamat</label>
                    <div class="input-icon">
                        <i class="fas fa-map-marker-alt icon-left"></i>
                        <input type="text" name="address" id="reg_address"
                               placeholder="Jl. Contoh No. 1, Kota" value="{{ old('address') }}" required>
                    </div>
                </div>
                <div class="field">
                    <label for="reg_password">Password</label>
                    <div class="input-icon">
                        <i class="fas fa-lock icon-left"></i>
                        <input type="password" name="password" id="reg_password"
                               placeholder="Min. 8 karakter" required>
                    </div>
                </div>
                <div class="field">
                    <label for="reg_password_confirmation">Konfirmasi Password</label>
                    <div class="input-icon">
                        <i class="fas fa-lock icon-left"></i>
                        <input type="password" name="password_confirmation" id="reg_password_confirmation"
                               placeholder="Ulangi password" required>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Daftar Sekarang</button>
            </form>
        </div>

    </div>{{-- /forms-wrap --}}

    {{-- ── Overlay (blue panel) ── --}}
    <div class="overlay-wrap">
        <div class="overlay">

            {{-- Shown when LOGIN is active → prompt to register --}}
            <div class="overlay-panel">
                @if ($logo)
                    <div class="logo-wrap"><img src="{{ $logo }}" alt="Logo"></div>
                @else
                    <div class="logo-wrap"><i class="fas fa-building"></i></div>
                @endif
                <h2>{{ $appName }}</h2>
                <p>Panel manajemen aplikasi yang aman,<br>cepat, dan mudah digunakan.</p>
                <p>Belum punya akun?</p>
                <button class="btn-ghost" id="toRegister">Daftar Sekarang</button>
                <span class="copy">© {{ date('Y') }} {{ $appName }}</span>
            </div>

            {{-- Shown when REGISTER is active → prompt to login --}}
            <div class="overlay-panel">
                @if ($logo)
                    <div class="logo-wrap"><img src="{{ $logo }}" alt="Logo"></div>
                @else
                    <div class="logo-wrap"><i class="fas fa-building"></i></div>
                @endif
                <h2>Sudah Punya Akun?</h2>
                <p>Masuk dan lanjutkan aktivitasmu<br>di {{ $appName }}.</p>
                <button class="btn-ghost" id="toLogin">Masuk Sekarang</button>
                <span class="copy">© {{ date('Y') }} {{ $appName }}</span>
            </div>

        </div>
    </div>{{-- /overlay-wrap --}}

</div>{{-- /shell --}}

<script src="{{ asset('asset/vendor/jquery/jquery.min.js') }}"></script>
<script>
    const shell      = document.getElementById('shell');
    const toRegister = document.getElementById('toRegister');
    const toLogin    = document.getElementById('toLogin');

    toRegister.addEventListener('click', () => shell.classList.add('active'));
    toLogin.addEventListener('click',    () => shell.classList.remove('active'));

    // Auto-open register panel if Laravel redirected here with register errors
    @if (request()->is('register') || session('openRegister'))
        shell.classList.add('active');
    @endif
</script>
</body>
</html>
