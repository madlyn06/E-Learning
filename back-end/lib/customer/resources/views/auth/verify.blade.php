@extends('master')

@section('content')
    <div class="auth" style="background: unset">
        <div class="container-w auth__content">
            <div class="auth__form">
                <div class="logo">
                    <img src="{{ theme_url('images/logo.svg') }}" alt="" />
                </div>
                <div class="title">{{ __('Verify Your Email Address') }}</div>
                @if (session('resent'))
                    <a class="btn__forgot" style="color: #989cb3">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </a>
                @endif
                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
            </div>
            <div class="auth__bg">
                <img src="{{ theme_url('images/auth-login.png') }}" alt="" />
            </div>
        </div>
    </div>
@endsection
