<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ trim($__env->yieldContent('title')) ? trim($__env->yieldContent('title')) . ' - ' : '' }}{{ $setting->name_app ?? config('app.name') }}
    </title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- ✅ FIX 1: SB Admin 2 CSS WAJIB diaktifkan — jangan di-comment -->
    <!-- Ini yang menyebabkan tampilan polos sebelumnya -->
    <link href="{{ asset('asset/css/sb-admin-2.css') }}" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- ✅ FIX 2: Custom CSS SETELAH SB Admin 2, supaya bisa override -->
    <!-- Hapus css yang tidak diperlukan karena sudah di-cover SB Admin 2 -->
    <link rel="stylesheet" href="{{ asset('css/layout/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout/main-content.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive/media-queries.css') }}">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/191fb31aee.js" crossorigin="anonymous"></script>

    <!-- Page Specific Styles -->
    @stack('styles')
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                @include('layouts.partials.topbar')

                <!-- Page Content -->
                <div class="container-fluid px-0">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Overlay untuk Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Scripts — urutan wajib: jQuery → Bootstrap → Easing → SB Admin 2 → Custom -->
    <script src="{{ asset('asset/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('asset/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('asset/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ✅ FIX 3: SB Admin 2 JS tetap dipertahankan untuk accordion & toggle desktop -->
    <script src="{{ asset('asset/js/sb-admin-2.min.js') }}"></script>

    <!-- Custom App JS -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Page Specific Scripts -->
    @stack('scripts')
</body>

</html>
