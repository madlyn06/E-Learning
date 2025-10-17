@extends('acl::auth.master')

@section('meta_title', __('acl::login.meta_title'))

@section('content')
    <div class="panel">
        <div class="panel-header text-center mb-3">
            <h3 class="fs-24">{{ __('acl::login.page_title') }}</h3>
            <p class="text-muted text-center mb-0">{{ __('acl::login.page_subtitle') }}</p>
        </div>
        <form class="register-form" action="{{ route('admin.login') }}" method="POST">
            @csrf

            <div class="form-group">
                <input name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="{{ __('acl::login.form.email') }}"
                       value="{{ old('email') }}"
                >
                @error('email')
                <span class="invalid-feedback text-left">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="{{ __('acl::login.form.password') }}">
                @error('password')
                <span class="invalid-feedback text-left">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="custom-control custom-checkbox mb-3">
                <input type="checkbox" class="custom-control-input" id="checkboxRemember" name="remember" value="1" checked>
                <label class="custom-control-label" for="checkboxRemember">{{ __('acl::login.form.remember') }}</label>
            </div>

            <button type="submit" class="btn btn-success btn-block">
                {{ __('acl::login.button.login') }}
            </button>
        </form>
    </div>

    <div class="bottom-text text-center my-3">
        <a href="{{route('admin.password.request')}}" class="font-weight-500">
            {{ __('acl::login.link.forgot_password') }}
        </a>
    </div>
@stop
