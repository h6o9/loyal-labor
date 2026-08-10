@extends('user.layout.app')

@section('title')
    {{ __('Wishlist') }} || {{ $setting->app_name }}
@endsection

@section('user-breadcrumb')
    @include('components::breadcrumb', ['title' => __('Wishlist'), 'image' => 'wishlist'])
@endsection

@section('user-content')
    <div class="wsus__dashboard_contant">
        <div class="wsus__dashboard_contant_top">
            <div class="wsus__dashboard_heading">
                <h5>{{ __('Wishlist') }}</h5>
            </div>
        </div>
        <div class="p_20 pt_0">
            <div class="row">
                @forelse ($wishlist as $product)
                    <div class="col-sm-6 col-md-12 col-lg-6 col-xl-4">
                        @php
                            $product = $product->load(['reviews', 'labels.translation', 'variants', 'manageStocks']);
                        @endphp

                        <div class="wsus__product_item">
                            <div class="img">
                                <img class="img-fluid w-100" src="{{ asset($product->thumbnail_image) }}" alt="product">
                            </div>
                            <span class="new remove_wishlist"><a
                                    href="{{ route('website.wishlist.remove', ['slug' => $product->slug]) }}"
                                    title="{{ __('Remove From Wishlist') }}">
                                    <i class="fas fa-times text-white"></i>
                                </a></span>
                            <div class="text">
                                <span>
                                    <span class="rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $product->reviews->count() ? '' : 'text-secondary' }}"
                                                aria-hidden="true"></i>
                                        @endfor
                                        <b>({{ $product->reviews->count() ?? 0 }} {{ __('Reviews') }})</b>
                                    </span>
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
                    <div class="col-12 mt-3 text-center">
                        <h3>
                            {{ __('No products in wishlist') }}!
                        </h3>
                    </div>
                @endforelse
            </div>

            <div class="row">
                <div class="col-12 wow fadeInUp">
                    {{ $wishlist->links('components::pagination') }}
                </div>
            </div>
        </div>
    </div>

    @include('components::product-modal')
@endsection

@push('styles')
    <style>
        .remove_wishlist {
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    <script>
        "use strict";
        // remove_wishlist on click generate a click in nearest a tag
        $(document).on('click', '.remove_wishlist', function(e) {
            $(this).closest('a').click();
        });
    </script>
@endpush
