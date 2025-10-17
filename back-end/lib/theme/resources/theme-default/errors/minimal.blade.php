<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Newnet">
    <title>@yield('title')</title>

    <link rel="shortcut icon" href="{{ asset('vendor/newnet-admin/img/icon/favicon.ico') }}">

    <link href="{{ asset('vendor/newnet-admin/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/newnet-admin/dist/css/style.css') }}" rel="stylesheet">
</head>
<body class="bg-white">
<section class="page_505 d-flex align-items-center justify-content-center text-center h-100vh">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="four_zero_four_bg">
                    <h1 class="font-weight-bold text-monospace">@yield('code')</h1>
                </div>
                <div class="contant_box_505">
                    <h3 class="h2">@yield('message')</h3>
                    <a href="{{ url('/') }}" class="btn btn-success mt-3">Go Home</a>
                </div>
            </div>
        </div>
    </div>
</section>

@yield('footer')
</body>
</html>
