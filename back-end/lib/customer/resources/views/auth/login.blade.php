@extends('master')

@section('meta_title', __('customer::customer.login.meta_title'))

@section('content')
<div class="auth">
    <div class="container-w auth__content">
        <form class="auth__form" method="POST" action="{{ route('customer.web.customer.login') }}">
            @csrf
            <div class="logo">
                <img src="{{ theme_url('images/logo.svg') }}" alt="" />
            </div>
            <div class="title">{{ __('login') }}</div>
            <div class="input__wrap">
                <label for="email" class="label">{{ __('email') }}</label>
                <input
                    autocomplete="off"
                    id="email"
                    name="email"
                    class="input @error('email') is-invalid @enderror"
                    type="text"
                    placeholder="{{ __('email') }}"
                    required
                />
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="input__wrap">
                <label for="password" class="label">{{ __('password') }}</label>
                <input
                    autocomplete="off"
                    id="password"
                    name="password"
                    class="input @error('password') is-invalid @enderror"
                    type="password"
                    placeholder="{{ __('password') }}"
                    required
                />
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
{{--                <img src="{{ theme_url('images/i-show-password.svg') }}" class="img-show" alt="" />--}}
            </div>
            <button type="submit" class="btn__login">{{ __('login') }}</button>
            <a href="{{ route('customer.web.password.request') }}" class="btn__forgot">{{ __('forgot_password') }}?</a>

            <a class="btn__forgot" href="{{ route('customer.web.customer.register') }}">
                {{ __('register_now') }}
            </a>
        </form>
        <div class="auth__bg">
            <img src="{{ theme_url('images/auth-login.png') }}" alt="" />
        </div>
    </div>
</div>
@endsection
