@extends('master')

@section('meta_title', __('Register'))

@section('content')
<div class="auth">
    <div class="container-w auth__content">
        <form class="auth__form" method="POST" action="{{ route('customer.web.customer.register') }}">
            @csrf
            <div class="logo">
                <img src="{{ theme_url('images/logo.svg') }}" alt="" />
            </div>
            <div class="title">{{ __('register') }}</div>
            <div class="input__wrap">
                <label for="name" class="label">{{ __('name') }}</label>
                <input
                    autocomplete="off"
                    id="name"
                    name="name"
                    class="input @error('name') is-invalid @enderror"
                    type="text"
                    placeholder="{{ __('name') }}"
                    required
                />
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

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
            <button type="submit" class="btn__login">{{ __('register') }}</button>
            <a href="{{ route('customer.web.customer.login') }}" class="btn__forgot" style="color: #989cb3">
                <img src="{{ theme_url('images/i-back.svg') }}" alt="" /> {{ __('back_login') }}
            </a>
        </form>
        <div class="auth__bg">
            <img src="{{ theme_url('images/auth-register.png') }}" alt="" />
        </div>
    </div>
</div>
@endsection
