@extends('master')

@section('meta_title', __('Reset Password'))

@section('content')
    <div class="auth">
        <div class="container-w auth__content">
            <form class="auth__form" method="POST" action="{{ route('customer.web.password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="logo">
                    <img src="{{ theme_url('images/logo.svg') }}" alt="" />
                </div>
                <div class="title">{{ __('Reset Password') }}</div>

                <div class="input__wrap">
                    <label for="email" class="label">Email</label>
                    <input
                        id="email"
                        name="email"
                        class="input @error('email') is-invalid @enderror"
                        type="text"
                        placeholder="Email"
                        value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                    />
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                    @enderror
                </div>
                <div class="input__wrap">
                    <label for="password" class="label">Password</label>
                    <input
                        autocomplete="off"
                        id="password"
                        name="password"
                        class="input @error('password') is-invalid @enderror"
                        type="password"
                        placeholder="Password"
                        required
                    />
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="input__wrap">
                    <label for="password_confirmation" class="label">Confirm Password</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        class="input @error('password') is-invalid @enderror"
                        type="password"
                        placeholder="Confirm Password"
                        required
                    />
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <button type="submit" class="btn__login">{{ __('Reset Password') }}</button>
                <a href="{{ route('customer.web.customer.login') }}" class="btn__forgot" style="color: #989cb3">
                    <img src="{{ theme_url('images/i-back.svg') }}" alt="" /> Back to log in
                </a>
            </form>
            <div class="auth__bg">
                <img src="{{ theme_url('images/auth-register.png') }}" alt="" />
            </div>
        </div>
    </div>
@stop
