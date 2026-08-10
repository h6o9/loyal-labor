@extends('website.layouts.app')

@section('title')
    {{ __('Flash Deals') }} - {{ $setting->app_name }}
@endsection

@section('content')
    <!--============================ BREADCRUMBS START ==============================-->
    <section class="wsus__breadcrumbs" style="background: url({{ asset($setting->breadcrumb_image) }});">
        <div class="wsus__breadcrumbs_overly">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>{{ __('Flash Deals') }}</h1>
                            <ul>
                                <li>
                                    <a href="{{ route('website.home') }}"><i
                                            class="fas fa-home-lg"></i>{{ __('Home') }}</a>
                                </li>
                                <li>{{ __('Flash Deals') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================ BREADCRUMBS END ==============================-->

    <section class="wsus__flash_deals wsus__flash_deal_page pt_120 xs_pt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row mb_20">
                <div class="col-xl-3 wow fadeInUp">
                    <div class="wsus__section_heading">
                        <h5>{{ 'Best Deals' }}</h5>
                        <h2>{{ __('Flash') }}<span>{{ __('Deals') }}</span></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @forelse ($products as $product)
                    <div class="col-sm-6 col-lg-4 col-xl-3 wow fadeInUp">
                        <div class="wsus__product_item">
                            <div class="img">
                                <img class="img-fluid w-100"
                                    src="{{ asset($product->flash_deal_image ? $product->flash_deal_image : $product->thumbnail_image) }}"
                                    alt="product">
                                <div class="simply-countdown flash-deal-countdown"
                                    data-start-date="{{ $product->flash_deal_start }}"
                                    data-end-date="{{ $product->flash_deal_end }}">
                                </div>
                            </div>
                            <div class="text">
                                <span>
                                    @php
                                        $averageRating = round($product->reviews->avg('rating'), 1);
                                        $fullStars = floor($averageRating);
                                        $hasHalfStar = $averageRating - $fullStars >= 0.5;
                                    @endphp

                                    @if ($product->reviews->count() > 0)
                                        {{-- Full stars --}}
                                        @for ($i = 0; $i < $fullStars; $i++)
                                            <i class="fas fa-star" aria-hidden="true"></i>
                                        @endfor

                                        {{-- Half star if needed --}}
                                        @if ($hasHalfStar)
                                            <i class="fas fa-star-half-alt" aria-hidden="true"></i>
                                        @endif

                                        {{-- Empty stars to make total 5 --}}
                                        @for ($i = 0; $i < 5 - $fullStars - ($hasHalfStar ? 1 : 0); $i++)
                                            <i class="far fa-star" aria-hidden="true"></i>
                                        @endfor
                                    @endif
                                    <b>[{{ $product->reviews->count() ?? 0 }} {{ __('Reviews') }}]</b>
                                </span>
                                <a class="title" href="{{ route('website.product', ['product' => $product->slug]) }}"
                                    tabindex="0">{{ $product->name }}</a>
                                <h5>
                                    @if ($product->discounted_price->is_discounted)
                                        {{ currency($product->discounted_price->discounted_price) }}
                                        <del>{{ currency($product->discounted_price->price) }}</del>
                                    @else
                                        {{ currency($product->discounted_price->price ?? $product->price) }}
                                    @endif
                                </h5>
                                <ul>
                                    <li>
                                        <x-add-to-wishlist-button :product="$product" />
                                    </li>
                                    <li>
                                        <x-add-to-cart-button :product="$product" />
                                    </li>
                                    <li>
                                        <x-view-product-model-button :product="$product" />
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-sm-12">{{ __('No Product Found!') }}</div>
                @endforelse
            </div>
            <div class="row">
                <div class="col-12 wow fadeInUp">
                    {{ $products->links('components::pagination') }}
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    @include('components::product-modal')
    <!-- Modal -->
@endsection

@push('scripts')
    <script>
        "use strict";

        $(window).on('load', function() {
            $('.flash-deal-countdown').each(function() {
                var endDateStr = $(this).data('endDate');
                var parts = endDateStr.split('T')[0].split('-');
                var year = parseInt(parts[0], 10);
                var month = parseInt(parts[1], 10);
                var day = parseInt(parts[2], 10);

                simplyCountdown(this, {
                    year: year,
                    month: month,
                    day: day,
                    enableUtc: true
                });
            });
        });
    </script>
@endpush
