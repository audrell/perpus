<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('code') - @yield('title') | {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('asset/vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/error.css') }}">
</head>

<body>
    <div class="container error-wrap d-flex align-items-center justify-content-center py-4">
        <div class="row w-100 justify-content-center">
            <div class="col-lg-12 col-xl-12">
                <div class="card error-card">
                    <div class="row no-gutters">
                        <div class="col-md-5 error-side p-4 p-md-5 d-flex flex-column justify-content-between">
                            <div>
                                <div class="icon-badge mb-4">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="error-code">@yield('code')</div>
                                <h4 class="font-weight-bold mt-3 mb-2">@yield('title')</h4>
                                <p class="mb-0" style="opacity:.9;">@yield('cause')</p>
                            </div>
                            <small style="opacity:.85;">{{ config('app.name') }}</small>
                        </div>

                        <div class="col-md-7 bg-white p-4 p-md-5 d-flex flex-column justify-content-center">
                            <h5 class="font-weight-bold text-dark mb-3">@yield('title')</h5>
                            <p class="text-muted mb-4">@yield('cause')</p>

                            <div class="d-flex flex-wrap">
                                @yield('actions')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>