  @extends('admin.auth.app')

@section('title')
    <title>{{ __('Forgot Password') }}</title>
@endsection

@section('content')
    <section class="section">
        <div class="container my-0">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-md-4 my-3">
                    <div class="login-brand">
                        <a href="#">
                            <img src="{{ asset('public/backend/img/admin-auth-bg.png') }}" alt="{{ $setting?->app_name }}" width="220">
                        </a>
                    </div>
                    <div class="card card-primary">
                        <div class="card-header">
                            <x-admin.form-title :text="__('Forgot Password')" />
                        </div>
                        <div class="card-body">
                            <form action="{{ route('staff.forget-password') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <x-admin.form-input id="email" name="email" type="email"
                                        value="{{ old('email') }}" label="{{ __('Email') }}" required="true" />
                                </div>
                                <div class="form-group">
                                    <x-admin.button class="btn-lg btn-block" type="submit"
                                        text="{{ __('Send Reset Link') }}" />
                                </div>
                                <div class="form-group">
                                    <div class="d-block btn btn-primary">
                                        <a href="{{ route('staff.login') }}"
                                            class="text-white">{{ __('Go to login page') }}</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
