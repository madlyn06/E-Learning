<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Newnet">
    <meta name="referrer" content="no-referrer-when-downgrade" />
    <title>@yield('meta_title', setting('seo_meta_title') ?: setting('site_title', config('app.name')))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <link rel="shortcut icon" href="{{ get_setting_media_url('favicon', '', asset('vendor/newnet-admin/img/icon/favicon.ico')) }}">

    @yield('meta')
    @stack('metas')

    @include('path.head')

    {!! Asset::styles() !!}

    @include('path.style')

    @stack('styles')

    <script>
        const locale = '{{ app()->getLocale() }}';
    </script>

    {!! theme_setting('code_head') !!}
</head>
<body class="@yield('body-class')">

@include('path.header')

@section('main-content')
    <main class="main" id="app">
        @yield('content')
    </main>
@show

@include('path.footer')

{!! Asset::container('footer')->styles() !!}

{!! Asset::scripts() !!}

@include('path.script')

@stack('scripts')

{!! theme_setting('code_footer') !!}

</body>
</html>
