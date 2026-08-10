@extends('admin.auth.app')
@section('title')
    <title>{{ __('Reset Password') }}</title>
@endsection
@section('content')
    <section class="section">
        <div class="container mt-0">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-md-4 my-5">
                    <div class="login-brand">
                        <a href="#">
                            <img src="{{ asset('public/backend/img/admin-auth-bg.png') }}" alt="{{ $setting?->app_name }}" width="220">
                        </a>
                    </div>
                    <div class="card card-primary">
                        <div class="card-header">
                            <x-admin.form-title :text="__('Reset Password')" />
                        </div>
                        <div class="card-body">
                            <form action="{{ route('staff.password.reset-store', $token) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <x-admin.form-input id="email" name="email" type="email"
                                        value="{{ $staff->email }}" label="{{ __('Email') }}" required="true" />
                                </div>
                                <div class="form-group">
                                    <x-admin.form-input id="password" name="password" type="password"
                                        label="{{ __('Password') }}" required="true" />
                                </div>
                                <div class="form-group">
                                    <x-admin.form-input id="password_confirmation" name="password_confirmation"
                                        type="password" label="{{ __('Confirm Password') }}" required="true" />
                                </div>

                                <div class="form-group">
                                    <x-admin.button class="btn-lg btn-block" type="submit"
                                        text="{{ __('Reset Password') }}" />
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
