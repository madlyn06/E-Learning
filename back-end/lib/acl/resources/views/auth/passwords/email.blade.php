@extends('acl::auth.master')

@section('meta_title', __('acl::login.meta_title'))

@section('content')
    <div class="panel">
        <div class="panel-header text-center mb-3">
            <h3 class="fs-24">{{ __('acl::login.reset-password') }}</h3>
            <p class="text-muted text-center mb-0">{{ __('acl::login.title-reset') }}</p>
        </div>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <form class="register-form" action="{{ route('admin.password.email') }}" method="POST">
            @csrf

            <div class="form-group">
                <input name="email" required type="email"
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

            <button type="submit" class="btn btn-success">
                {{ __('acl::login.button.send-link') }}
            </button>
        </form>
    </div>

    <div class="bottom-text text-center my-3">
        <a href="{{route('admin.login')}}" class="font-weight-500">
            {{ __('acl::login.link.sign-in') }}
        </a>
    </div>
@stop
