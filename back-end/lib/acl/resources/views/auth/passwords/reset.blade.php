@extends('acl::auth.master')

@section('meta_title', __('acl::login.meta_title'))

@section('content')
    <div class="panel">
        <div class="panel-header text-center mb-3">
            <h3 class="fs-24">{{ __('acl::login.reset-password') }}</h3>
            <p class="text-muted text-center mb-0">{{ __('acl::login.title-reset') }}</p>
        </div>

        <form class="register-form" action="{{ route('admin.password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <input id="email" type="email"
                       placeholder="{{ __('acl::login.form.email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ $email ?? old('email') }}" required
                       autocomplete="email" autofocus>

                @error('email')
                <span class="invalid-feedback text-left">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <input type="password" name="password" required
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="{{ __('acl::login.form.password') }}">
                @error('password')
                <span class="invalid-feedback text-left">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input id="password-confirm" type="password"
                       class="form-control" name="password_confirmation"
                       placeholder="{{ __('acl::login.form.confirm-password') }}"
                       required autocomplete="new-password">
            </div>


            <button type="submit" class="btn btn-primary">
                {{ __('acl::login.reset-password') }}
            </button>
        </form>
    </div>

@stop
