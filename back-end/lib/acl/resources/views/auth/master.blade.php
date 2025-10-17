<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="DnSoft Technology">
    <meta name="robots" content="noindex, nofollow">

    <title>@yield('meta_title') - {{ setting('site_title', config('app.name')) }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ get_setting_media_url('favicon', 'thumb', asset('vendor/newnet-admin/img/icon/favicon.ico')) }}">

    <link href="{{ asset('vendor/newnet-admin/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/newnet-admin/plugins/metisMenu/metisMenu.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/newnet-admin/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/newnet-admin/plugins/typicons/src/typicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/newnet-admin/plugins/themify-icons/themify-icons.min.css') }}" rel="stylesheet">

    @stack('styles')

    <link href="{{ asset('vendor/newnet-admin/dist/css/style.css') }}" rel="stylesheet">

    <script>
        var locale = '{{ app()->getLocale() }}';
    </script>

    <style>
        .register-logo img{
            max-height: 100px;
            width: auto;
        }
    </style>
</head>
<body class="bg-white">
<div class="d-flex align-items-center justify-content-center text-center h-100vh">
    <div class="form-wrapper m-auto">
        <div class="form-container my-4">
            <div class="register-logo text-center mb-4">
                <img src="{{ get_setting_media_url('logo_login', '', asset('vendor/newnet-admin/img/logo.png')) }}" alt="Logo">
            </div>

            @yield('content')
        </div>
    </div>
</div>

<!--Global script(used by all pages)-->
<script src="{{ asset('vendor/newnet-admin/plugins/jQuery/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('vendor/newnet-admin/dist/js/popper.min.js') }}"></script>
<script src="{{ asset('vendor/newnet-admin/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/newnet-admin/plugins/metisMenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('vendor/newnet-admin/plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"></script>

<!-- Third Party Scripts(used by this page)-->
<script src="{{ asset('vendor/newnet-admin/plugins/sweetalert/sweetalert.min.js') }}"></script>

<!--Page Active Scripts(used by this page)-->
@stack('scripts')

<!--Page Scripts(used by all page)-->
<script src="{{ asset('vendor/newnet-admin/dist/js/sidebar.js') }}"></script>
</body>
</html>
