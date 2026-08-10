@if (optional($sections?->hero_section)?->status ?? false)
    <!--============================
        BANNER START
    ==============================-->
    <section class="wsus__banner">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-xl-3 wow fadeInLeft">
                    <ul class="wsus__category_menu_2">
                        @foreach (allCategories(status: true, parentOnly: true) as $category)
                            <li>
                                <a href="{{ route('website.products', ['category' => $category->slug]) }}">
                                    <span>
                                        @if (!is_null($category->icon))
                                            <img class="img-fluid w-100" src="{{ asset($category->icon) }}" alt="icon">
                                        @endif
                                        <b>{{ $category->name }}</b>
                                    </span>
                                    <p>{{ $category->products_count ?? 0 }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-12 col-xl-9 wow fadeInRight">
                    <div class="wsus__banner_area"
                        style="background: url({{ asset('website/images/banner_bg.webp') }});">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-lg-7 col-xl-6">
                                <div class="wsus__banner_text">
                                    <h6>{{ sectionData($sections, 'hero_section', 'subtitle') }}</h6>
                                    <h1>{{ sectionData($sections, 'hero_section', 'title') }}</h1>
                                    @if (sectionData($sections, 'hero_section', 'discount_price'))
                                        <h4>{{ currency(sectionData($sections, 'hero_section', 'discount_price')) }}<del>{{ currency(sectionData($sections, 'hero_section', 'price')) }}</del>
                                        </h4>
                                    @else
                                        <h4>{{ currency(sectionData($sections, 'hero_section', 'price')) }}</h4>
                                    @endif
                                    <p>{{ sectionData($sections, 'hero_section', 'details') }}</p>
                                    <a class="common_btn"
                                        href="{{ sectionData($sections, 'hero_section', 'action_button_url') }}"
                                        tabindex="0">{{ sectionData($sections, 'hero_section', 'action_button_text') }}<i
                                            class="far fa-arrow-right" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-5 col-xl-5">
                                <div class="wsus__banner_img">
                                    <img class="img-fluid w-100"
                                        src="{{ asset(sectionData($sections, 'hero_section', 'banner_image')) }}"
                                        alt="banner">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        BANNER END
    ==============================-->
@endif
@if (optional($sections?->featured_category_section)?->status ?? false)
    <!--============================
        CATEGORY START
    ==============================-->
    <section class="wsus__category pt_120 xs_pt_100 pb_85 xs_pb_65">
        <div class="container">
            <div class="row">
                <div class="col-xl-6  wow fadeInUp">
                    <div class="wsus__section_heading mb_45">
                        <h5>{{ sectionData($sections, 'featured_category_section', 'sub_title') }}</h5>
                        <h2>{!! sectionData($sections, 'featured_category_section', 'title') !!}</h2>
                    </div>
                </div>
            </div>
            <div class="row category_one">
                @foreach (allCategories(status: true, featured: true, limit: optional($sections?->featured_category_section)?->limit) as $category)
                    <div class="col-xl-3 wow fadeInUp">
                        @include('components::category-1')
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--============================
        CATEGORY END
    ==============================-->
@endif
@if (optional($sections?->featured_category_section)?->status ?? false)
    <!--============================
        QUICK SHOPING START
    ==============================-->
    <section class="wsus__quick_shop">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-xl-3 wow fadeInUp">
                    <div class="wsus__quick_shop_1"
                        style="background: url({{ asset('website/images/quick_shop_bg_1.webp') }});">
                        <h2>{{ sectionData($sections, 'quick_shopping_section', 'left_product_title') }}</h2>
                        <h6>{{ sectionData($sections, 'quick_shopping_section', 'left_product_price_text') }}
                            <span>{{ currency(sectionData($sections, 'quick_shopping_section', 'left_product_price')) }}</span>
                        </h6>
                        <a class="common_btn"
                            href="{{ sectionData($sections, 'quick_shopping_section', 'left_product_action_url') }}"
                            tabindex="0">{{ sectionData($sections, 'quick_shopping_section', 'left_product_action_text') }}<i
                                class="far fa-arrow-right" aria-hidden="true"></i></a>
                        <div class="img">
                            <img class="img-fluid w-100"
                                src="{{ asset(sectionData($sections, 'quick_shopping_section', 'left_product_image')) }}"
                                alt="product">
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-xl-6 wow fadeInUp">
                    <div class="wsus__quick_shop_2"
                        style="background: url({{ asset('website/images/quick_shop_bg_2.webp') }});">
                        <div class="wsus__quick_shop_2_text">
                            <h5>{{ sectionData($sections, 'quick_shopping_section', 'center_product_subtitle') }}</h5>
                            <h2>{{ sectionData($sections, 'quick_shopping_section', 'center_product_title') }}</h2>
                            <h6>{{ sectionData($sections, 'quick_shopping_section', 'center_product_price_text') }}<span>{{ currency(sectionData($sections, 'quick_shopping_section', 'center_product_price')) }}</span>
                            </h6>
                            <a class="common_btn"
                                href="{{ sectionData($sections, 'quick_shopping_section', 'center_product_action_url') }}"
                                tabindex="0">{{ sectionData($sections, 'quick_shopping_section', 'center_product_action_text') }}<i
                                    class="far fa-arrow-right" aria-hidden="true"></i></a>
                        </div>
                        <div class="img">
                            <img class="img-fluid w-100"
                                src="{{ asset(sectionData($sections, 'quick_shopping_section', 'center_product_image')) }}"
                                alt="product">
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-xl-3 wow fadeInUp">
                    <div class="wsus__quick_shop_1"
                        style="background: url({{ asset('website/images/quick_shop_bg_3.webp') }});">
                        <h2>{{ sectionData($sections, 'quick_shopping_section', 'right_product_title') }}</h2>
                        <h6>{{ sectionData($sections, 'quick_shopping_section', 'right_product_price_text') }}<span>{{ currency(sectionData($sections, 'quick_shopping_section', 'right_product_price')) }}</span>
                        </h6>
                        <a class="common_btn"
                            href="{{ sectionData($sections, 'quick_shopping_section', 'right_product_action_url') }}"
                            tabindex="0">{{ sectionData($sections, 'quick_shopping_section', 'right_product_action_text') }}<i
                                class="far fa-arrow-right" aria-hidden="true"></i></a>
                        <div class="img">
                            <img class="img-fluid w-100"
                                src="{{ asset(sectionData($sections, 'quick_shopping_section', 'right_product_image')) }}"
                                alt="product">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        QUICK SHOPING END
    ==============================-->
@endif
@if (optional($sections?->best_selling_section)?->status ?? false)
    <!--============================
        PRODUCTS START
    ==============================-->
    <section class="wsus__product pt_120 xs_pt_100 pb_140 xs_pb_120">
        <div class="container">
            <div class="row wow fadeInUp">
                <div class="col-xl-6">
                    <div class="wsus__section_heading mb_20">
                        <h5>{{ sectionData($sections, 'best_selling_section', 'sub_title') }}</h5>
                        <h2>{!! sectionData($sections, 'best_selling_section', 'title') !!}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="wsus__product_area">
            <div class="row product_one">
                @foreach ($bestSellingProducts as $bestSellingProduct)
                    <div class="col-xl-3 wow fadeInUp">
                        @include('components::product-theme-' . config('services.theme'), [
                            'product' => $bestSellingProduct,
                        ])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--============================
        PRODUCTS END
    ==============================-->
@endif
@if (optional($sections?->flash_product_section)?->status ?? false)

    @if ($flashDealsProduct)
        <!--============================ PRODUCT SLIDER START ==============================-->
        <section class="wsus__product_slider">
            <div class="container">
                <div class="wsus__product_slider_border">
                    <div class="row">
                        <div class="col-lg-6 wow fadeInUp">
                            <div class="row">
                                <div class="col-sm-3 col-xl-3 d-none d-sm-block">
                                    <div class="row slider-navOne">
                                        <div class="col-12">
                                            <div class="wsus__product_slider_small">
                                                <img class="img-fluid w-100"
                                                    src="{{ asset($flashDealsProduct->thumbnail_image) }}"
                                                    alt="product">
                                            </div>
                                        </div>
                                        @foreach ($flashDealsProduct->gallery as $gallery)
                                            <div class="col-12">
                                                <div class="wsus__product_slider_small">
                                                    <img class="img-fluid w-100" src="{{ asset($gallery->path) }}"
                                                        alt="product">
                                                </div>
                                            </div>
                                        @endforeach
                                        @foreach ($flashDealsProduct->variantImage as $variantImage)
                                            <div class="col-12" data-attribute_id="{{ $variantImage->attribute_id }}"
                                                data-attribute_value_id="{{ $variantImage->attribute_value_id }}">
                                                <div class="wsus__product_slider_small">
                                                    <img class="img-fluid w-100"
                                                        src="{{ asset($variantImage->image) }}" alt="product">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-sm-9 col-xl-9">
                                    <div class="row slider-forOne">
                                        <div class="col-12">
                                            <div class="wsus__product_slider_big">
                                                <img class="img-fluid w-100"
                                                    src="{{ asset($flashDealsProduct->thumbnail_image) }}"
                                                    alt="product">
                                                @if ($flashDealsProduct->discounted_price->is_discounted)
                                                    <p class="product_discount_percent">
                                                        -{{ $flashDealsProduct->discounted_price->discount_percent }}%
                                                    </p>
                                                @else
                                                    <p>{{ $flashDealsProduct?->labels->first()->name ?? '' }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        @foreach ($flashDealsProduct->gallery as $gallery)
                                            <div class="col-12">
                                                <div class="wsus__product_slider_big">
                                                    <img class="img-fluid w-100" src="{{ asset($gallery->path) }}"
                                                        alt="product">
                                                    @if ($flashDealsProduct->discounted_price->is_discounted)
                                                        <p class="product_discount_percent">
                                                            -{{ $flashDealsProduct->discounted_price->discount_percent }}%
                                                        </p>
                                                    @else
                                                        <p>{{ $flashDealsProduct?->labels->first()->name ?? '' }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach

                                        @foreach ($flashDealsProduct->variantImage as $variantImage)
                                            <div class="col-12" data-attribute_id="{{ $variantImage->attribute_id }}"
                                                data-attribute_value_id="{{ $variantImage->attribute_value_id }}">
                                                <div class="wsus__product_slider_big">
                                                    <img class="img-fluid w-100"
                                                        src="{{ asset($variantImage->image) }}" alt="product">
                                                    @if ($flashDealsProduct->discounted_price->is_discounted)
                                                        <p class="product_discount_percent">
                                                            -{{ $flashDealsProduct->discounted_price->discount_percent }}%
                                                        </p>
                                                    @else
                                                        <p>{{ $flashDealsProduct?->labels?->first()->name ?? '' }}
                                                        </p>
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
                                <div class="wsus__section_heading">
                                    <h5>{{ $flashDealsProduct?->labels->pluck('name')->implode(', ') ?? '' }}</h5>
                                    <a class="title"
                                        href="{{ route('website.product', ['product' => $flashDealsProduct->slug]) }}">{{ $flashDealsProduct->name }}</a>
                                </div>
                                <span class="rating">
                                    <span class="rating">
                                        @php
                                            $averageRating = round($flashDealsProduct?->reviews->avg('rating'), 1);
                                            $fullStars = floor($averageRating);
                                            $hasHalfStar = $averageRating - $fullStars >= 0.5;
                                        @endphp

                                        @if ($flashDealsProduct?->reviews->count() > 0)
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
                                        <b>({{ $flashDealsProduct?->reviews->count() ?? 0 }}
                                            {{ __('Reviews') }})</b>
                                    </span>
                                </span>
                                <h6>
                                    @if ($flashDealsProduct->discounted_price->is_discounted)
                                        {{ currency($flashDealsProduct->discounted_price->discounted_price) }}
                                        <del>{{ currency($flashDealsProduct->discounted_price->price) }}</del>
                                    @else
                                        {{ currency($flashDealsProduct->discounted_price->price ?? $flashDealsProduct->price) }}
                                    @endif
                                </h6>
                                <p>
                                    {{ $flashDealsProduct->short_description }}
                                </p>
                                <ul class="ul_1">
                                    <li>
                                        <x-add-to-wishlist-button :product="$flashDealsProduct" />
                                    </li>
                                    <li>
                                        <x-add-to-cart-button :product="$flashDealsProduct" />
                                    </li>
                                    <li>
                                        <x-view-product-model-button :product="$flashDealsProduct" />
                                    </li>
                                </ul>
                                <div class="wsus__product_slider_count">
                                    <img class="img-fluid w-100" src="{{ asset('website/images/clock.webp') }}"
                                        alt="icon">
                                    <div class="simply-countdown flash-deal-countdown"
                                        data-start-date="{{ $flashDealsProduct->flash_deal_start }}"
                                        data-end-date="{{ $flashDealsProduct->flash_deal_end }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--============================
        PRODUCT SLIDER END
    ==============================-->
    @endif
@endif
@if (optional($sections?->bundle_combo_section)?->status ?? false)
    <!--============================
        PRODUCT COMBO START
    ==============================-->
    <section class="wsus__product_combo pt_120 xs_pt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-xl-3 wow fadeInUp">
                    <div class="wsus__product_combo_area"
                        style="background: url({{ asset('website/images/product_combo_bg.webp') }});">
                        <div class="img_1">
                            <img class="img-fluid w-100"
                                src="{{ asset(sectionData($sections, 'bundle_combo_section', 'banner_combo_image')) }}"
                                alt="product">
                        </div>
                        <div class="img_2">
                            <img class="img-fluid w-100"
                                src="{{ asset(sectionData($sections, 'bundle_combo_section', 'banner_product_image')) }}"
                                alt="product">
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-xl-9">
                    <div class="row wow fadeInUp">
                        <div class="col-xl-6">
                            <div class="wsus__section_heading mb_20">
                                <h5>{{ sectionData($sections, 'bundle_combo_section', 'sub_title') }}</h5>
                                <h2>{!! sectionData($sections, 'bundle_combo_section', 'title') !!}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row product_combo">
                        @foreach ($bundleComboProducts as $bundleComboProduct)
                            <div class="col-xl-4 wow fadeInUp">
                                @include('components::product-theme-' . config('services.theme'), [
                                    'product' => $bundleComboProduct,
                                ])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        PRODUCT COMBO END
    ==============================-->
@endif
@if (optional($sections?->brand_section)?->status ?? false)
    <!--============================
        BRAND START
    ==============================-->
    <section class="wsus__branding wow fadeInUp">
        <div class="container">
            <div class="row brand_slide">
                @foreach ($brands as $brand)
                    <div class="col-xl-2">
                        <a class="wsus__brand_item" href="#">
                            <img class="img-fluid w-100" src="{{ asset($brand->image) }}"
                                alt="{{ $brand->name }}">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--============================
        BRAND END
    ==============================-->
@endif
@if (optional($sections?->call_to_action_section)?->status ?? false)
    <!--============================
        CTA START
    ==============================-->
    <section class="wsus__ctg mt_70">
        <div class="container">
            <div class="wsus__ctg_area" style="background: url({{ asset('website/images/cta_bg.webp') }});">
                <div class="row align-items-center">
                    <div class="col-md-6 col-lg-2 col-xl-2 wow fadeInUp">
                        <div class="wsus__ctg_text1">
                            <h6>{{ sectionData($sections, 'call_to_action_section', 'cta_one_subtitle') }}</h6>
                            <h4>{{ sectionData($sections, 'call_to_action_section', 'cta_one_title') }}</h4>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-2 col-xl-2 wow fadeInUp">
                        <div class="wsus__ctg_img1">
                            <div class="img">
                                <img class="img-fluid w-100"
                                    src="{{ asset(sectionData($sections, 'call_to_action_section', 'cta_image_one')) }}"
                                    alt="cta">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-4 wow fadeInUp">
                        <div class="wsus__ctg_text2">
                            <h4>{{ sectionData($sections, 'call_to_action_section', 'product_title') }}</h4>
                            <h6>{{ sectionData($sections, 'call_to_action_section', 'product_price_text') }}<span>{{ currency(sectionData($sections, 'call_to_action_section', 'product_price')) }}</span>
                            </h6>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-2 wow fadeInUp">
                        <div class="wsus__ctg_img2">
                            <img class="img-fluid w-100"
                                src="{{ asset(sectionData($sections, 'call_to_action_section', 'cta_image_two')) }}"
                                alt="cta">
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-2 col-xl-2 wow fadeInUp">
                        <div class="wsus__ctg_btn">
                            <a class="common_btn"
                                href="{{ sectionData($sections, 'call_to_action_section', 'action_url') }}"
                                tabindex="0">{{ sectionData($sections, 'call_to_action_section', 'action_text') }}<i
                                    class="far fa-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        CTA END
    ==============================-->
@endif
@if (optional($sections?->feature_products_section)?->status ?? false)
    <!--============================
        FEATURE PRODUCT START
    ==============================-->
    <section class="wsus__feature_product pt_120 xs_pt_100 pb_85 xs_pb_65">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 wow fadeInUp">
                    <div class="wsus__section_heading mb_45">
                        <h5>{{ sectionData($sections, 'feature_products_section', 'sub_title') }}</h5>
                        <h2>{!! sectionData($sections, 'feature_products_section', 'title') !!}</h2>
                    </div>
                </div>
            </div>
            <div class="row feture_product_slide">
                @foreach ($featuredProducts as $featuredProduct)
                    <div class="col-xl-3 wow fadeInUp">
                        <div class="wsus__feature_product_item">
                            <a class="img"
                                href="{{ route('website.product', ['product' => $featuredProduct->slug]) }}">
                                <img class="img-fluid w-100" src="{{ asset($featuredProduct->thumbnail_image) }}"
                                    alt="{{ $featuredProduct->name }}">
                            </a>
                            <div class="wsus__feature_product_text">
                                <span>
                                    @php
                                        $averageRating = round($featuredProduct->reviews->avg('rating'), 1);
                                        $fullStars = floor($averageRating);
                                        $hasHalfStar = $averageRating - $fullStars >= 0.5;
                                    @endphp

                                    @if ($featuredProduct->reviews->count() > 0)
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

                                    <b>[{{ $featuredProduct->reviews->count() ?? 0 }} {{ __('Reviews') }}]</b>
                                </span>
                                <a class="title"
                                    href="{{ route('website.product', ['product' => $featuredProduct->slug]) }}"
                                    tabindex="0">{{ $featuredProduct->name }}</a>
                                <h5>
                                    @if ($featuredProduct->discounted_price->is_discounted)
                                        {{ currency($featuredProduct->discounted_price->discounted_price) }}
                                        <del>{{ currency($featuredProduct->discounted_price->price) }}</del>
                                    @else
                                        {{ currency($featuredProduct->discounted_price->price ?? $featuredProduct->price) }}
                                    @endif
                                </h5>
                                <ul>
                                    <li>
                                        <x-add-to-wishlist-button :product="$featuredProduct" />
                                    </li>
                                    <li>
                                        <x-add-to-cart-button :product="$featuredProduct" />
                                    </li>
                                    <li>
                                        <x-view-product-model-button :product="$featuredProduct" />
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--============================
        FEATURE PRODUCT END
    ==============================-->
@endif
@if (optional($sections?->flash_deal_section)?->status ?? false)
    <!--============================
        FLASH DEALS START
    ==============================-->
    @if ($flashDeals)
        @php
            $startDate = sectionData($sections, 'flash_deal_section', 'flash_deal_start_date');
            $endDate = sectionData($sections, 'flash_deal_section', 'flash_deal_end_date');
        @endphp

        <section class="wsus__flash_deals pb_120 xs_pb_100">
            <div class="container">
                <div class="row mb_20 wow fadeInUp">
                    <div class="col-lg-4 col-xl-3">
                        <div class="wsus__section_heading">
                            <h5>{{ sectionData($sections, 'flash_deal_section', 'sub_title') }}</h5>
                            <h2>{!! sectionData($sections, 'flash_deal_section', 'title') !!}</h2>
                        </div>
                    </div>
                    <div class="col-lg-5 col-xl-4">
                        <div class="wsus__flash_deals_count">
                            @if (\Carbon\Carbon::parse($startDate)->isFuture())
                                <p>{{ __('Start Of The Offer') }}</p>
                            @else
                                <p>{{ __('End Of The Offer') }}</p>
                            @endif
                            <div class="simply-countdown flash-deal-countdown {{ \Carbon\Carbon::parse($startDate)->isFuture() ? 'future' : 'past' }}"
                                data-start-date="{{ $startDate }}" data-end-date="{{ $endDate }}"></div>
                        </div>
                    </div>
                </div>
                <div class="row flash_deals_slide">
                    @foreach ($flashDeals as $flashProduct)
                        <div class="col-xl-3 wow fadeInUp">
                            @include('components::product-theme-' . config('services.theme'), [
                                'product' => $flashProduct,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!--============================
        FLASH DEALS END
    ==============================-->
@endif
@if (optional($sections?->testimonials_section)?->status ?? false)
    <!--============================
        TESTIMONIAL START
    ==============================-->
    <section class="wsus__testimonial pt_120 xs_pt_100 pb_110 xs_pb_90"
        style="background: url({{ asset('website/images/testimonial_bg.webp') }});">
        <div class="container">
            <div class="row wow fadeInUp">
                <div class="col-xl-6">
                    <div class="wsus__section_heading mb_45">
                        <h5>{{ sectionData($sections, 'testimonials_section', 'sub_title') }}</h5>
                        <h2>{!! sectionData($sections, 'testimonials_section', 'title') !!}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="wsus__testimonial_area">
            <div class="row testimonial_slide">
                @foreach ($testimonials as $testimonial)
                    <div class="col-xl-3 wow fadeInUp">
                        <div class="wsus__testimonial_item">
                            <div class="top">
                                <span>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $testimonial->rating ? '' : 'text-secondary' }}"
                                            aria-hidden="true"></i>
                                    @endfor
                                </span>
                                <p>{{ $testimonial->comment }}</p>
                            </div>
                            <div class="bottom">
                                <div class="img">
                                    <img class="img-fluid w-100" src="{{ asset($testimonial->image) }}"
                                        alt="img">
                                </div>
                                <div class="text">
                                    <h5>{{ $testimonial->name }}</h5>
                                    <p>{{ $testimonial->designation }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--============================
        TESTIMONIAL END
    ==============================-->
@endif
@if (optional($sections?->blog_section)?->status ?? false)
    <!--============================
        BLOG START
    ==============================-->
    <section class="wsus__blog pt_120 xs_pt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row justify-content-center wow fadeInUp">
                <div class="col-xl-6">
                    <div class="wsus__section_heading heading_center mb_20">
                        <h5>{{ sectionData($sections, 'blog_section', 'sub_title') }}</h5>
                        <h2>{!! sectionData($sections, 'blog_section', 'title') !!}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($blogs as $blog)
                    <div class="col-md-6 col-xl-4 wow fadeInUp">
                        @include('components::blog-1')
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--============================
        BLOG END
    ==============================-->
@endif
