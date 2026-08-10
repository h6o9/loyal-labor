@extends('website.layouts.app')

@section('title')
    {{ $product->name }} | {{ $product->sku }} - {{ $setting->app_name }}
@endsection

@section('content')
    <!--============================ BREADCRUMBS START ==============================-->
    <section class="wsus__breadcrumbs" style="background: url({{ asset($setting->breadcrumb_image) }});">
        <div class="wsus__breadcrumbs_overly">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>{{ $product->name }}</h1>
                            <ul>
                                <li>
                                    <a href="{{ route('website.home') }}"><i
                                            class="fas fa-home-lg"></i>{{ __('Home') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('website.products') }}">{{ __('Products') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- <!--============================
        PRODUCT PAGE START
    ==============================--> --}}
    <section class="wsus__product_details pt_120 xs_pt_100 pb_100 xs_pb_80">
        <div class="container">
            <div class="wsus__product_slider_border">
                <div class="row">
                    <div class="col-lg-6 wow fadeInUp">
                        <div class="row">
                            <div class="col-sm-3 col-xl-3 d-none d-sm-block">
                                <div class="row slider-navOne">
                                    <div class="col-12">
                                        <div class="wsus__product_slider_small">
                                            <img class="img-fluid w-100" src="{{ asset($product->thumbnail_image) }}"
                                                alt="product">
                                        </div>
                                    </div>
                                    @foreach ($product->gallery as $gallery)
                                        <div class="col-12">
                                            <div class="wsus__product_slider_small">
                                                <img class="img-fluid w-100" src="{{ asset($gallery->path) }}"
                                                    alt="product">
                                            </div>
                                        </div>
                                    @endforeach
                                    @foreach ($product->variantImage as $variantImage)
                                        <div class="col-12" data-attribute_id="{{ $variantImage->attribute_id }}"
                                            data-attribute_value_id="{{ $variantImage->attribute_value_id }}">
                                            <div class="wsus__product_slider_small">
                                                <img class="img-fluid w-100" src="{{ asset($variantImage->image) }}"
                                                    alt="product">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-sm-9 col-xl-9">
                                <div class="row slider-forOne">
                                    <div class="col-12">
                                        <div class="wsus__product_slider_big">
                                            <img class="img-fluid w-100" src="{{ asset($product->thumbnail_image) }}"
                                                alt="product">
                                            @if ($product->is_flash_deal_active)
                                                <p>{{ __('Flash Deal') }}</p>
                                            @elseif($product->discounted_price->is_discounted)
                                                <p class="product_discount_percent">
                                                    -{{ $product->discounted_price->discount_percent }}%</p>
                                            @else
                                                <p>{{ $product?->labels->first()->name ?? '' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @foreach ($product->gallery as $gallery)
                                        <div class="col-12">
                                            <div class="wsus__product_slider_big">
                                                <img class="img-fluid w-100" src="{{ asset($gallery->path) }}"
                                                    alt="product">
                                                @if ($product->is_flash_deal_active)
                                                    <p>{{ __('Flash Deal') }}</p>
                                                @elseif($product->discounted_price->is_discounted)
                                                    <p class="product_discount_percent">
                                                        -{{ $product->discounted_price->discount_percent }}%</p>
                                                @else
                                                    <p>{{ $product?->labels->first()->name ?? '' }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach

                                    @foreach ($product->variantImage as $variantImage)
                                        <div class="col-12" data-attribute_id="{{ $variantImage->attribute_id }}"
                                            data-attribute_value_id="{{ $variantImage->attribute_value_id }}">
                                            <div class="wsus__product_slider_big">
                                                <img class="img-fluid w-100" src="{{ asset($variantImage->image) }}"
                                                    alt="product">
                                                @if ($product->is_flash_deal_active)
                                                    <p>{{ __('Flash Deal') }}</p>
                                                @elseif($product->discounted_price->is_discounted)
                                                    <p class="product_discount_percent">
                                                        -{{ $product->discounted_price->discount_percent }}%</p>
                                                @else
                                                    <p>{{ $product?->labels->first()->name ?? '' }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 wow fadeInUp">
                        <div class="wsus__product_slider_details">
                            @if ($product->is_flash_deal_active)
                                <div class="wsus__flash_deals_count">
                                    <div class="simply-countdown flash-deal-countdown {{ \Carbon\Carbon::parse($product->flash_deal_start)->isFuture() ? 'future' : 'past' }}"
                                        data-start-date="{{ $product->flash_deal_start }}"
                                        data-end-date="{{ $product->flash_deal_end }}">

                                    </div>
                                </div>
                            @endif

                            <div class="wsus__section_heading">
                                <h5>{{ $product?->labels->pluck('name')->implode(', ') ?? '' }}@if ($product->is_flash_deal_active)
                                        , {{ __('Flash Deal') }}
                                    @endif
                                </h5>
                                <p class="title">{{ $product->name }}</p>
                            </div>

                            @php
                                $averageRating = round($product->reviews->avg('rating'), 1);
                                $fullStars = floor($averageRating);
                                $hasHalfStar = $averageRating - $fullStars >= 0.5;
                            @endphp

                            <span class="rating">
                                @for ($i = 0; $i < $fullStars; $i++)
                                    <i class="fas fa-star" aria-hidden="true"></i>
                                @endfor
                                @if ($hasHalfStar)
                                    <i class="fas fa-star-half-alt" aria-hidden="true"></i>
                                @endif
                                @for ($i = 0; $i < 5 - $fullStars - ($hasHalfStar ? 1 : 0); $i++)
                                    <i class="far fa-star" aria-hidden="true"></i>
                                @endfor

                                <b>({{ $product->reviews->count() ?? 0 }} {{ __('Reviews') }})</b>
                            </span>
                            <h6 id="show_price"
                                data-original-price="@if ($product->discounted_price->is_discounted) {{ currency($product->discounted_price->discounted_price) }}<del>{{ currency($product->discounted_price->price) }}</del>@else{{ currency($product->discounted_price->price) }} @endif">
                                @if ($product->discounted_price->is_discounted)
                                    {{ currency($product->discounted_price->discounted_price) }}
                                    <del>{{ currency($product->discounted_price->price) }}</del>
                                @else
                                    {{ currency($product->discounted_price->price) }}
                                @endif
                            </h6>
                            <p>{{ $product->short_description }}</p>

                            @if ($product->has_variant)
                                @foreach ($product->attribute_and_values as $attributes)
                                    <ul
                                        class="{{ in_array($attributes['attribute_slug'], ['color', 'colors']) ? 'details_color' : 'details_size' }} d-flex flex-wrap">
                                        @foreach ($attributes['attribute_values'] as $value)
                                            @if (in_array($attributes['attribute_slug'], ['color', 'colors']))
                                                <li class="attr{{ $value['is_default'] ? ' active selectedAttr' : '' }}"
                                                    data-id="{{ $value['id'] }}" data-image="{{ $value['image'] }}"
                                                    style="background: {{ $value['value'] }};">
                                                </li>
                                            @else
                                                <li class="attr{{ $value['is_default'] ? ' active selectedAttr' : '' }}"
                                                    data-id="{{ $value['id'] }}">
                                                    {{ $value['value'] }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endforeach
                            @endif

                            <form class="wsus__modal_filter mt_20 align-items-center gap-2 add_to_cart_form"
                                id="add_to_cart_form_p{{ $product->id }}" data-product-id="{{ $product->id }}"
                                action="javascript:;">
                                <input name="product_id" type="hidden" value="{{ $product->id }}">
                                <input id="sku" name="sku" type="hidden" value="{{ $product->sku }}">
                                <input id="modal_price" name="price" type="hidden"
                                    value="{{ $product->discounted_price->discounted_price ?? $product->price }}">
                                <input id="variant_price" name="variant_price" type="hidden"
                                    value="{{ $product->discounted_price->is_discounted ? $product->discounted_price->discounted_price : $product->discounted_price->price }}">
                                <input id="is_variant" name="is_variant" type="hidden" value="0">
                                <input id="variant_sku" name="variant_sku" type="hidden" value="{{ $product->sku }}">
                                <div class="number">
                                    <input name="qty" type="number" value="1" placeholder="1"
                                        accept="number">
                                    <ul>
                                        <li>
                                            <button class="increase-btn" type="button" type="button"><i
                                                    class="fal fa-angle-up" aria-hidden="true"></i></button>
                                        </li>
                                        <li>
                                            <button class="decrease-btn" type="button"><i class="fal fa-angle-down"
                                                    aria-hidden="true"></i></button>
                                        </li>
                                    </ul>
                                </div>
                                <h3 id="qty_price" data-original-price="">
                                    {{ $product->discounted_price->discounted_price ?? $product->price }}
                                </h3>
                            </form>

                            <div class="wsus__modal_filter mt_20">
                                <button class="details_buy_btn add_cart ml_0" data-product-id="{{ $product->id }}"
                                    type="button">
                                    {{ __('Buy Now') }}
                                </button>
                                <button class="add_cart" data-product-id="{{ $product->id }}"
                                    form="add_to_cart_form_p{{ $product->id }}" type="button">
                                    {{ __('Add To Cart') }}
                                </button>
                                <a class="wishlist add_to_wishlist" data-id="{{ $product->id }}" href="javascript:;">
                                    <img class="img-fluid w-100" src="{{ asset('website/images/react.webp') }}"
                                        alt="{{ __('Wishlist') }}" loading="lazy">
                                </a>
                            </div>

                            <ul class="ul_3 details">
                                <li>{{ __('SKU') }}: <span class="sku"> {{ $product->sku }}</span></li>
                                <li>{{ __('Categories') }}:
                                    <span>{{ $product?->categories?->pluck('name')?->implode(', ') ?? '' }}</span>
                                </li>
                                <li>{{ __('Tags') }}:
                                    <span>{{ $product?->tags?->pluck('name')?->implode(', ') ?? '' }}</span>
                                </li>
                            </ul>
                            @if ($setting->has_vendor == 1 && $product->vendor)
                                <div class="details_store_area">
                                    <h6>
                                        <span><i class="fas fa-store"></i></span>
                                        <a
                                            href="{{ route('website.shop', ['slug' => $product->vendor->shop_slug]) }}">{{ $product->vendor->shop_name }}</a>
                                    </h6>
                                    @php
                                        $averageRating = round($product->vendor->reviews_avg_rating ?? 0, 1);
                                        $fullStars = floor($averageRating);
                                        $hasHalfStar = $averageRating - $fullStars >= 0.5;
                                        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                    @endphp

                                    <p class="rating">
                                        @for ($i = 0; $i < $fullStars; $i++)
                                            <i class="fas fa-star"></i>
                                        @endfor

                                        @if ($hasHalfStar)
                                            <i class="fas fa-star-half-alt"></i>
                                        @endif

                                        @for ($i = 0; $i < $emptyStars; $i++)
                                            <i class="far fa-star"></i>
                                        @endfor

                                        <span>
                                            {{ $averageRating ?? 0 }}
                                        </span>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="wsus__product_details_area mt_115 xs_mt_95">
                        <ul class="nav nav-pills wsus__product_details_nav wow fadeInUp" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-home" type="button" role="tab"
                                    aria-controls="pills-home" aria-selected="true">{{ __('Explanation') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-profile" type="button" role="tab"
                                    aria-controls="pills-profile"
                                    aria-selected="false">{{ __('Added information') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-contact" type="button" role="tab"
                                    aria-controls="pills-contact" aria-selected="false">{{ __('Opinions') }}</button>
                            </li>
                        </ul>
                        <div class="tab-content wsus__product_details_tab_contant" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab" tabindex="0">
                                <div class="wsus__product_details_tab_explnation">
                                    {!! clean($product->description) !!}
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                aria-labelledby="pills-profile-tab" tabindex="0">
                                <div class="wsus__product_details_tab_info">
                                    <h5>{{ __('Additional information') }}</h5>
                                    <ul>
                                        <li><span>{{ __('Brands') }}:</span> {{ $product?->brand?->name ?? '' }}</li>
                                        @if ($product->hasVariant)
                                            @foreach ($product->attribute_and_values->groupBy('attribute.name') as $attributes)
                                                @foreach ($attributes as $attribute)
                                                    <li>
                                                        <span>{{ $attribute['attribute'] }}:</span>
                                                        @foreach ($attribute['attribute_values'] as $attribute_value)
                                                            {{ $attribute_value['value'] }} @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </li>
                                                @endforeach
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                aria-labelledby="pills-contact-tab" tabindex="0">
                                <div class="wsus__product_details_tab_opinion">
                                    <h5>{{ __('Opinions') }}</h5>
                                    <div class="row">
                                        <div class="col-xl-8">
                                            <div class="wsus__product_details_review">
                                                @forelse ($product?->reviews ?? [] as $review)
                                                    <div class="wsus__product_single_review">
                                                        <div class="img">
                                                            <img class="img-fluid w-100"
                                                                src="{{ $review->user->image ? asset($review->user->image) : asset($setting->default_user_image) }}"
                                                                alt="comment">
                                                        </div>
                                                        <div class="text">
                                                            <h5>{{ $review->user->name ?? 'Anonymous' }}
                                                                <span class="review_icon">
                                                                    @for ($i = 0; $i < $review->rating; $i++)
                                                                        <i class="fas fa-star" aria-hidden="true"></i>
                                                                    @endfor
                                                                    @for ($i = 0; $i < 5 - $review->rating; $i++)
                                                                        <i class="fal fa-star" aria-hidden="true"></i>
                                                                    @endfor
                                                                </span>
                                                            </h5>
                                                            <span
                                                                class="date">{{ formattedDate($review->created_at) }}</span>
                                                            <p>
                                                                {{ $review->review }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="wsus__product_single_review">
                                                        <h4>{{ __('No reviews yet') }}</h4>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <!--============================
        PRODUCT PAGE END
    ==============================--> --}}

    {{-- <!--============================
        RELATED PRODUCT START
    ==============================--> --}}
    @if ($relatedProducts->count() > 0)
        <section class="wsus__related_product pb_120 xs_pb_100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-6 wow fadeInUp">
                        <div class="wsus__section_heading heading_center mb_25">
                            <h2>{{ __('Related') }}<span> {{ __('products') }}</span></h2>
                        </div>
                    </div>
                </div>

                <div class="wsus__related_product_area">
                    <div class="row">
                        @foreach ($relatedProducts as $relatedProduct)
                            <div class="col-sm-6 col-lg-4 col-xl-3 wow fadeInUp">
                                @include('components::product-theme-' . config('services.theme'), [
                                    'product' => $relatedProduct,
                                ])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- <!--============================
        RELATED PRODUCT END
    ==============================--> --}}

    @php
        $aboutUsPage = $sections;
    @endphp

    @if (sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'benefit_status') == 'active')
        {{-- <!--============================
        BENEFITE 2 START
    ==============================--> --}}
        <section class="wsus__benefit_2 pb_120 xs_pb_100">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <ul class="wsus__benefit_2_area">
                            <li>
                                <span><img class="img-fluid w-100"
                                        src="{{ asset(sectionData($aboutUsPage, 'about_us_page', 'area_one_icon')) }}"
                                        alt="icon"></span>
                                <div class="text">
                                    <h5 class="title">
                                        {{ sectionData($aboutUsPage, 'about_us_page', 'area_one_title') }}
                                    </h5>
                                    <p>{{ sectionData($aboutUsPage, 'about_us_page', 'area_one_sub_title') }}</p>
                                </div>
                            </li>
                            <li>
                                <span><img class="img-fluid w-100"
                                        src="{{ asset(sectionData($aboutUsPage, 'about_us_page', 'area_two_icon')) }}"
                                        alt="icon"></span>
                                <div class="text">
                                    <h5 class="title">
                                        {{ sectionData($aboutUsPage, 'about_us_page', 'area_two_title') }}
                                    </h5>
                                    <p>{{ sectionData($aboutUsPage, 'about_us_page', 'area_two_sub_title') }}</p>
                                </div>
                            </li>
                            <li>
                                <span><img class="img-fluid w-100"
                                        src="{{ asset(sectionData($aboutUsPage, 'about_us_page', 'area_three_icon')) }}"
                                        alt="icon"></span>
                                <div class="text">
                                    <h5 class="title">
                                        {{ sectionData($aboutUsPage, 'about_us_page', 'area_three_title') }}</h5>
                                    <p>{{ sectionData($aboutUsPage, 'about_us_page', 'area_three_sub_title') }}</p>
                                </div>
                            </li>
                            <li>
                                <span><img class="img-fluid w-100"
                                        src="{{ asset(sectionData($aboutUsPage, 'about_us_page', 'area_four_icon')) }}"
                                        alt="icon"></span>
                                <div class="text">
                                    <h5 class="title">
                                        {{ sectionData($aboutUsPage, 'about_us_page', 'area_four_title') }}</h5>
                                    <p>{{ sectionData($aboutUsPage, 'about_us_page', 'area_four_sub_title') }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        {{-- <!--============================
        BENEFITE 2 END
    ==============================--> --}}
    @endif

    <!-- Modal -->
    @include('components::product-modal')
    <!-- Modal -->

    @include('components::preloader')
@endsection

@push('scripts')
    <script>
        "use strict";

        $(document).ready(function() {
            let attributes = @json($product?->selectable_variants ?? []);
            window.attributes = Object.values(attributes || []);
            updateVarientAttr(window.attributes);
        });

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
