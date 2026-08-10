@extends('user.layout.app')

@section('title')
    {{ __('Change Password') }} || {{ $setting->app_name }}
@endsection

@section('user-breadcrumb')
    @include('components::breadcrumb', ['title' => __('Change Password'), 'image' => 'change_password'])
@endsection

@section('user-content')
    <div class="wsus__dashboard_contant">
        <div class="wsus__dashboard_contant_top d-flex flex-wrap justify-content-between">
            <div class="wsus__dashboard_heading">
                <h5>{{ __('Change Password') }}</h5>
            </div>
        </div>

        <div class="wsus__dashboard_password_change">
            <form action="{{ route('website.user.update.password') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-xl-12">
                        <div class="wsus__dashboard_password_change_input">
                            <label>{{ __('Current Password') }}</label>
                            <input name="current_password" type="password" placeholder="{{ __('Enter Current Password') }}">
                        </div>
                    </div>

                    <div class="col-xl-12">
                        <div class="wsus__dashboard_password_change_input">
                            <label>{{ __('New Password') }}</label>
                            <input name="password" type="password" placeholder="{{ __('Enter New Password') }}">
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="wsus__dashboard_password_change_input">
                            <label>{{ __('Confirm Password') }}</label>
                            <input name="password_confirmation" type="password"
                                placeholder="{{ __('Confirm New Password') }}">
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="wsus__dashboard_password_change_btn">
                            <button class="common_btn" type="submit">{{ __('Save Password') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
