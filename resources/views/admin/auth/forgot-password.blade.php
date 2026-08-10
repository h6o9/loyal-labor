@extends('admin.auth.app')

@section('title')
    <title>{{ __('Forgot Password') }}</title>
@endsection

@section('content')
    <div class="auth-bg-gradient py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card overflow-hidden">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <a href="#">
                                    <img src="{{ asset('public/backend/img/admin-auth-bg.png') }}" alt="{{ $setting?->app_name ?? 'Loyal Labor' }}" width="220">
                                </a>
                            </div>
                            <div class="card card-primary border-0 shadow-none">
                                <div class="card-header">
                                    <x-admin.form-title :text="__('Forgot Password')" />
                                </div>
                                <div class="card-body px-0">
                                    <form action="{{ route('admin.forget-password') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <x-admin.form-input id="email" name="email" type="email"
                                                value="{{ old('email') }}" label="{{ __('Email') }}" required="true" />
                                        </div>
                                        <div class="form-group">
                                            <x-admin.button class="btn-lg btn-block" type="submit"
                                                text="{{ __('Send Reset Link') }}" />
                                        </div>
                                        <div class="form-group mb-0">
                                            <a href="{{ route('admin.login') }}" class="btn btn-primary btn-lg btn-block text-white">
                                                {{ __('Go to login page') }}
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
