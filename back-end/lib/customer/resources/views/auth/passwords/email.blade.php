@extends('master')

@section('meta_title', __('Reset Password'))

@section('content')
    <div class="auth">
        <div class="container-w auth__content">
            <form class="auth__form" method="POST" action="{{ route('customer.web.password.email') }}">
                @csrf
                <div class="title">Forgot password?</div>
                <div class="sub__title">
                    No worries, we'll send you reset instructions.
                    @if (session('status'))
                        <div style="color: #00d95a">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
                <div class="input__wrap">
                    <label for="email" class="label">Email</label>
                    <input
                        autocomplete="off"
                        id="email"
                        name="email"
                        class="input @error('email') is-invalid @enderror"
                        type="text"
                        placeholder="Email"
                    />

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <button type="submit" class="btn__login">Send</button>
                <a href="{{ route('customer.web.customer.login') }}" class="btn__forgot" style="color: #989cb3">
                    <img src="{{ theme_url('images/i-back.svg') }}" alt="" /> Back to log in
                </a>
            </form>
            <div class="auth__bg">
                <img src="{{ theme_url('images/auth-forgot.png') }}" alt="" />
            </div>
        </div>
    </div>
@stop
