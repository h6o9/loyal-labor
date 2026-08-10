@extends('website.layouts.app')

@section('title')
    {{ $data->title }} - {{ $setting->app_name }}
@endsection

@section('content')
    <section class="wsus__breadcrumbs" style="background: url({{ asset($setting->breadcrumb_image) }});">
        <div class="wsus__breadcrumbs_overly">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>{{ $data->title }}</h1>
                            <ul>
                                <li>
                                    <a href="{{ route('website.home') }}"><i class="fas fa-home-lg"></i>{{ __('Home') }}</a>
                                </li>
                                <li>{{ $data->title }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="wsus__pricacy_policy mt_95 xs_mt_75 mb_115 xs_mb_95">
        <div class="container">
            <div class="row wow fadeInUp">
                <div class="col-xl-12">
                    <div class="wsus__pricacy_policy_text">
                        {!! clean($data->description) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
