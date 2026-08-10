@extends('website.layouts.app')

@section('title')
    {{ __('Shops') }} - {{ $setting->app_name }}
@endsection

@section('content')
    <section class="wsus__breadcrumbs" style="background: url({{ asset($setting->breadcrumb_image) }});">
        <div class="wsus__breadcrumbs_overly">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>{{ __('Shops') }}</h1>
                            <ul>
                                <li>
                                    <a href="{{ route('website.home')  }}"><i class="fas fa-home-lg"></i>{{ __('Home') }}</a>
                                </li>
                                <li>{{ __('Shops') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="wsus__vendor mt_95 xs_mt_75 mb_120 xs_mb_100">
        <div class="container">
            <div class="row">
                @forelse($shops as $shop)
                    <div class="col-lg-4 col-md-6">
                        <div class="wsus__single_vendor">
                            <div class="img">
                                <img class="img-fluid w-100"
                                    src="{{ $shop->banner_image ? asset($shop->banner_image) : asset($setting->default_avatar) }}"
                                    alt="{{ $shop->shop_name }}">
                            </div>
                            <div class="text">
                                <a class="title"
                                    href="{{ route('website.shop', $shop->shop_slug) }}">{{ $shop->shop_name }}</a>
                                @php
                                    $averageRating = round($shop->reviews_avg_rating ?? 0, 1);
                                    $fullStars = floor($averageRating);
                                    $hasHalfStar = $averageRating - $fullStars >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                @endphp

                                <p class="rating">
                                    {{-- Full stars --}}
                                    @for ($i = 0; $i < $fullStars; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor

                                    {{-- Half star --}}
                                    @if ($hasHalfStar)
                                        <i class="fas fa-star-half-alt"></i>
                                    @endif

                                    {{-- Empty stars --}}
                                    @for ($i = 0; $i < $emptyStars; $i++)
                                        <i class="far fa-star"></i>
                                    @endfor

                                    {{-- Total count --}}
                                    <span>
                                        ({{ $shop->reviews_count ?? 0 }} {{ __('Review') }})
                                    </span>
                                </p>
                                @if ($shop->phone)
                                    <a class="phone" href="callto:{{ $shop->phone }}">
                                        <span><i class="fal fa-phone-alt"></i></span>
                                        {{ $shop->phone }}
                                    </a>
                                @endif
                                <a class="email" href="mailto:{{ $shop->email }}">
                                    <span><i class="fal fa-envelope"></i></span>
                                    {{ $shop->email }}
                                </a>
                                <a class="common_btn"
                                    href="{{ route('website.shop', $shop->shop_slug) }}">{{ __('Visit Store') }}</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-lg-12">
                        <div class="wsus__single_vendor">
                            <div class="text">
                                <h3 class="text-center">{{ __('No Shops Found') }}!</h3>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="row">
                <div class="col-12 wow fadeInUp">
                    {{ $shops->links('components::pagination') }}
                </div>
            </div>
        </div>
    </section>
@endsection
