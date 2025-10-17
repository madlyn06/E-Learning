{{-- The header of page. Navbar, top menu, etc --}}

<nav class="navbar navbar-expand-md shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name') }}
            {{--<img src="{{ get_setting_media_url('logo', '', theme_url('img/logo.png')) }}" alt="Logo">--}}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            {{--{!! FrontendMenu::render('main-menu', 'menu.main-menu') !!}--}}

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">{{ __('Home') }}</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
