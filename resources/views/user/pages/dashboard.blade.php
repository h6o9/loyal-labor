@extends('user.layout.app')

@section('title')
    {{ __('Dashboard') }} || {{ $setting->app_name }}
@endsection

@section('user-breadcrumb')
    @include('components::breadcrumb', ['title' => __('Dashboard'), 'image' => 'dashboard'])
@endsection

@section('user-content')
    <div class="wsus__dashboard_contant">
        <div class="wsus__dashboard_contant_top d-flex flex-wrap justify-content-between">
            <div class="wsus__dashboard_heading">
                <h5>{{ __('Profile Details') }}</h5>
            </div>
            <div class="wsus__dashboard_contant_btn">
                <a class="common_btn" href="{{ route('website.user.edit.profile') }}">{{ __('Edit Profile') }}</a>
            </div>
        </div>

        <div class="wsus__dashboard_profile">
            <div class="text ms-0">
                <h6>{{ __('About Me') }}</h6>
                <p>{{ $user->bio ?? '' }}</p>
            </div>
        </div>

        <ul class="wsus__dashboard_profile_info">
            <li><span>{{ __('Name') }} :</span>{{ $user->name ?? '' }}</li>
            <li><span>{{ __('Gender') }} :</span>{{ str($user->gender)->title()->__toString() ?? '' }}</li>
            <li><span>{{ __('Contact') }} :</span>{{ $user->phone ?? '' }}</li>
            <li><span>{{ __('Birthday') }} :</span>{{ formattedDate($user->birthday ?? now()) }}</li>
            <li><span>{{ __('Email') }} :</span>{{ $user->email ?? '' }}</li>
            <li><span>{{ __('Zip Code') }} : </span>{{ $user->zip_code ?? '' }}</li>
            <li><span>{{ __('City') }} :</span>{{ $user->city->name ?? '' }}</li>
            <li><span>{{ __('Country') }} :</span>{{ $user->country->name ?? '' }}</li>
            <li><span>{{ __('Present Address') }} : </span>{{ $user->address ?? '' }}</li>
        </ul>
    </div>
@endsection
