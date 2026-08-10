@extends('seller.layouts.master')

@section('title')
    <title>{{ __('Change Password') }}</title>
@endsection

@section('seller-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Change Password') }}" :list="[
                __('Dashboard') => route('seller.dashboard'),
                __('Change Password') => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-sm-4">
                    <div class="col-12">
                        <div class="card profile-widget">
                            <div class="profile-widget-description">
                                <form action="{{ route('seller.password-update') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">

                                        <div class="form-group col-12">
                                            <label class="control-label"
                                                for="current_password">{{ __('Current Password') }}</label>
                                            <input class="form-control" id="current_password" name="current_password"
                                                type="password">
                                        </div>

                                        <div class="form-group col-12">
                                            <label class="control-label" for="password">{{ __('New Password') }}</label>
                                            <input class="form-control" id="password" name="password" type="password">
                                        </div>

                                        <div class="form-group col-12">
                                            <label class="control-label"
                                                for="password_confirmation">{{ __('Confirm Password') }}</label>
                                            <input class="form-control" id="password_confirmation"
                                                name="password_confirmation" type="password">
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary">{{ __('Change Password') }}</button>
                                        </div>
                                    </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
