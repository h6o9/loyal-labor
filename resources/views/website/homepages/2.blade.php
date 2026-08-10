@if (optional($sections?->hero_section)?->status ?? false)
    <!--============================
        BANNER 2 START
    ==============================-->
    <section class="wsus__banner_2">
        <div class="row banner_slider_2">
            @if (sectionData(sections: $sections, sectionName: 'hero_section', propertyName: 'item_one_status') == 'active')
                <div class="col-12">
                    <div class="wsus__banner_2_slider"
                        style="background: url({{ asset('website/images/banner_2_bg.webp') }});">
                        <div class="container">
                            <div class="row justify-content-between">
                                <div class="col-lg-6 col-xl-6">
                                    <div class="wsus__banner_2_text">
                                        <h6>{{ sectionData($sections, 'hero_section', 'item_one_subtitle') }}</h6>
                                        <h1>{{ sectionData($sections, 'hero_section', 'item_one_title') }}</h1>
                                        <h4>{{ sectionData($sections, 'hero_section', 'item_one_price') }}</h4>
                                        <a class="common_btn"
                                            href="{{ sectionData($sections, 'hero_section', 'item_one_action_button_url') }}">{{ sectionData($sections, 'hero_section', 'item_one_action_button_text') }}<i
                                                class="far fa-arrow-right"></i></a>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-xl-5">
                                    <div class="wsus__banner_2_img">
                                        <img class="img-fluid w-100"
                                            src="{{ asset(sectionData($sections, 'hero_section', 'item_one_image')) }}"
                                            alt="banner">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (sectionData(sections: $sections, sectionName: 'hero_section', propertyName: 'item_two_status') == 'active')
                <div class="col-12">
                    <div class="wsus__banner_2_slider"
                        style="background: url({{ asset('website/images/banner_2_bg.webp') }});">
                        <div class="container">
                            <div class="row justify-content-between">
                                <div class="col-lg-6 col-xl-6">
                                    <div class="wsus__banner_2_text">
                                        <h6>{{ sectionData($sections, 'hero_section', 'item_two_subtitle') }}</h6>
                                        <h1>{{ sectionData($sections, 'hero_section', 'item_two_title') }}</h1>
                                        <h4>{{ sectionData($sections, 'hero_section', 'item_two_price') }}</h4>
                                        <a class="common_btn"
                                            href="{{ sectionData($sections, 'hero_section', 'item_two_action_button_url') }}">{{ sectionData($sections, 'hero_section', 'item_two_action_button_text') }}<i
                                                class="far fa-arrow-right"></i></a>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-xl-5">
                                    <div class="wsus__banner_2_img">
                                        <img class="img-fluid w-100"
                                            src="{{ asset(sectionData($sections, 'hero_section', 'item_two_image')) }}"
                                            alt="banner">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (sectionData(sections: $sections, sectionName: 'hero_section', propertyName: 'item_three_status') == 'active')
                <div class="col-12">
                    <div class="wsus__banner_2_slider"
                        style="background: url({{ asset('website/images/banner_2_bg.webp') }});">
                        <div class="container">
                            <div class="row justify-content-between">
                                <div class="col-lg-6 col-xl-6">
                                    <div class="wsus__banner_2_text">
                                        <h6>{{ sectionData($sections, 'hero_section', 'item_three_subtitle') }}</h6>
                                        <h1>{{ sectionData($sections, 'hero_section', 'item_three_title') }}</h1>
                                        <h4>{{ sectionData($sections, 'hero_section', 'item_three_price') }}</h4>
                                        <a class="common_btn"
                                            href="{{ sectionData($sections, 'hero_section', 'item_three_action_button_url') }}">{{ sectionData($sections, 'hero_section', 'item_three_action_button_text') }}<i
                                                class="far fa-arrow-right"></i></a>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-xl-5">
                                    <div class="wsus__banner_2_img">
                                        <img class="img-fluid w-100"
                                            src="{{ asset(sectionData($sections, 'hero_section', 'item_three_image')) }}"
                                            alt="banner">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
@endif
</section>
<!--============================
        BANNER 2 END
    ==============================-->
@endif

@if (optional($sections?->featured_category_section)?->status ?? false)
    <!--============================
        CATEGORIES 2 START
    ==============================-->
    <section class="wsus__categories_2 pt_120 xs_pt_100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 wow fadeInUp">
                    <div class="wsus__section_heading_2 heading_center mb_45">
                        <h5>{{ sectionData($sections, 'featured_category_section', 'sub_title') }}</h5>
                        <h2>{{ sectionData($sections, 'featured_category_section', 'title') }}</h2>
                    </div>
                </div>
            </div>
            <div class="row categories_2_slider">
                @foreach (allCategories(status: true, featured: true, limit: optional($sections?->featured_category_section)?->limit) as $category)
                    <div class="col-xl-3 wow fadeInUp">
                        @include('components::category-2')
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--============================
        CATEGORIES 2 END
    ==============================-->
@endif

@if (optional($sections?->flash_deal_section)?->status ?? false)
    <!--============================
        PRODUCT 2 START
    ==============================-->
    @if ($flashDeals)

        @php
            $startDate = sectionData($sections, 'flash_deal_section', 'flash_deal_start_date');
            $endDate = sectionData($sections, 'flash_deal_section', 'flash_deal_end_date');
        @endphp

        <section class="wsus__product_2 pt_110 xs_pt_90">
            <div class="container">
                <div class="row justify-content-between align-items-center mb_20">
                    <div class="col-md-5 col-xl-6 wow fadeInUp">
                        <div class="wsus__section_heading_2">
                            <h5>{{ sectionData($sections, 'flash_deal_section', 'sub_title') }}</h5>
                            <h2>{{ sectionData($sections, 'flash_deal_section', 'title') }}</h2>
                        </div>
                    </div>
                    <div class="col-md-7 col-xl-6 wow fadeInUp">
                        <div class="wsus__product_2_right">
                            <div class="product_2_count">
                                @if (\Carbon\Carbon::parse($startDate)->isFuture())
                                    <p>{{ __('Start Of The Offer') }}</p>
                                @else
                                    <p>{{ __('End Of The Offer') }}</p>
                                @endif
                                <div class="simply-countdown flash-deal-countdown"
                                    data-start-date="{{ $startDate }}" data-end-date="{{ $endDate }}"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row product_2_slider">
                    @foreach ($flashDeals as $flashProduct)
                        <div class="col-xl-3 wow fadeInUp">
                            @include('components::product-theme-2', [
                                'product' => $flashProduct,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!--============================
        PRODUCT 2 END
    ==============================-->
@endif

@if (optional($sections?->new_arrival_section)?->status ?? false)
    <!--============================
        NEW ARRIVAL 2 START
    ==============================-->
    <section class="wsus__new_arrival_2 pt_100 xs_pt_80">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-xl-8 wow fadeInUp">
                    <div class="wsus__new_arrival_2_one"
                        style="background: url({{ asset(sectionData($sections, 'new_arrival_section', 'new_arrival_image_one')) }});">
                        <div class="wsus__section_heading_2">
                            <h5>{{ sectionData($sections, 'new_arrival_section', 'new_arrival_one_subtitle') }}</h5>
                            <h2>{{ sectionData($sections, 'new_arrival_section', 'new_arrival_one_title') }}</h2>
                            <a class="common_btn border_btn"
                                href="{{ sectionData($sections, 'new_arrival_section', 'new_arrival_one_action_url') }}">{{ sectionData($sections, 'new_arrival_section', 'new_arrival_one_action_text') }}<i
                                    class="far fa-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-xl-4 wow fadeInUp">
                    <div class="wsus__new_arrival_2_two"
                        style="background: url({{ asset(sectionData($sections, 'new_arrival_section', 'new_arrival_image_two')) }});">
                        <div class="wsus__section_heading_2">
                            <h5>{{ sectionData($sections, 'new_arrival_section', 'new_arrival_two_subtitle') }}</h5>
                            <h2>{{ sectionData($sections, 'new_arrival_section', 'new_arrival_two_title') }}</h2>
                            <a
                                href="{{ sectionData($sections, 'new_arrival_section', 'new_arrival_two_action_url') }}">{{ sectionData($sections, 'new_arrival_section', 'new_arrival_two_action_text') }}<i
                                    class="far fa-arrow-right" aria-hidden="true"></i></a>
                        </div>
                        <span>{{ sectionData($sections, 'new_arrival_section', 'new_arrival_two_label') }}</span>
                    </div>
                    <div class="wsus__new_arrival_2_two mt_25"
                        style="background: url({{ asset(sectionData($sections, 'new_arrival_section', 'new_arrival_image_three')) }});">
                        <div class="wsus__section_heading_2">
                            <h5>{{ sectionData($sections, 'new_arrival_section', 'new_arrival_three_subtitle') }}</h5>
                            <h2>{{ sectionData($sections, 'new_arrival_section', 'new_arrival_three_title') }}</h2>
                            <a
                                href="{{ sectionData($sections, 'new_arrival_section', 'new_arrival_three_action_url') }}">{{ sectionData($sections, 'new_arrival_section', 'new_arrival_three_action_text') }}<i
                                    class="far fa-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        NEW ARRIVAL 2 END
    ==============================-->
@endif

@if (optional($sections?->favorite_products_section)?->status ?? false)
    <!--============================
        FAVORITES PRODUCT START
    ==============================-->
    <section class="wsus__product_2 wsus__fav_product pt_120 xs_pt_100 pb_95 xs_pb_75">
        <div class="container">
            <div class="row justify-content-between mb_35">
                <div class="col-lg-6 col-xl-6 wow fadeInUp">
                    <div class="wsus__section_heading_2">
                        <h5>{{ sectionData($sections, 'favorite_products_section', 'sub_title') }}</h5>
                        <h2>{{ sectionData($sections, 'favorite_products_section', 'title') }}</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-6 wow fadeInUp">
                    <div class="wsus__fav_product_nav">
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            @foreach ($favoriteProductCategories as $favoriteProductCategory)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="pills-home-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-{{ $favoriteProductCategory->slug }}" type="button"
                                        role="tab" aria-controls="pills-{{ $favoriteProductCategory->slug }}"
                                        aria-selected="true">{{ $favoriteProductCategory->name }}</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="tab-content wsus__fav_product_tab_contant" id="pills-tabContent">
                        @foreach ($favoriteProductCategories as $favoriteProductCategory)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                id="pills-{{ $favoriteProductCategory->slug }}" role="tabpanel"
                                aria-labelledby="pills-{{ $favoriteProductCategory->slug }}-tab" tabindex="0">
                                <div class="row">
                                    @foreach ($favoriteProductCategory->products as $product)
                                        <div class="col-sm-6 col-lg-4 col-xl-3">
                                            @include('components::product-theme-2', [
                                                'product' => $product,
                                            ])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        FAVORITES PRODUCT END
    ==============================-->
@endif

@if (optional($sections?->discount_banner_section)?->status ?? false)
    <!--============================
        DISCOUNT 2 START
    ==============================-->
    <section class="wsus__discount_2 pt_120 xs_pt_100 pb_115 xs_pb_95"
        style="background: url({{ asset('website/images/discount_2_bg.webp') }});">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-3 col-xl-3 wow fadeInLeft">
                <div class="wsus__discount_2_img_1">
                    <img class="img-fluid w-100"
                        src="{{ asset(sectionData($sections, 'discount_banner_section', 'image_one')) }}"
                        alt="product">
                </div>
            </div>
            <div class="col-lg-5 col-xl-5 wow fadeInUp">
                <div class="wsus__discount_2_text">
                    <div class="wsus__section_heading_2 heading_center">
                        <h5>{{ sectionData($sections, 'discount_banner_section', 'sub_title') }}</h5>
                        <h2>{{ sectionData($sections, 'discount_banner_section', 'title') }}</h2>
                    </div>
                    <div class="simply-countdown flash-deal-countdown"
                        data-start-date="{{ sectionData($sections, 'discount_banner_section', 'offer_start_time') }}"
                        data-end-date="{{ sectionData($sections, 'discount_banner_section', 'offer_end_time') }}">
                    </div>
                    <a class="common_btn"
                        href="{{ sectionData($sections, 'discount_banner_section', 'action_url') }}">{{ sectionData($sections, 'discount_banner_section', 'action_text') }}<i
                            class="far fa-arrow-right" aria-hidden="true"></i></a>
                    <p>{!! sectionData($sections, 'discount_banner_section', 'description') !!}</p>
                </div>
            </div>
            <div class="col-lg-3 col-xl-3 wow fadeInRight">
                <div class="wsus__discount_2_img_2">
                    <img class="img-fluid w-100"
                        src="{{ asset(sectionData($sections, 'discount_banner_section', 'image_two')) }}"
                        alt="product">
                </div>
            </div>
        </div>
    </section>
    <!--============================
        DISCOUNT 2 END
    ==============================-->
@endif

@if (optional($sections?->feature_products_section)?->status ?? false)
    <!--============================
        PRODUCT 2 COLLECTION START
    ==============================-->
    <section class="wsus__product_2 wsus__product_2_collection pt_115 xs_pt_95 pb_95 xs_pb_75">
        <div class="container">
            <div class="row justify-content-between mb_20">
                <div class="col-md-6 col-xl-6 wow fadeInUp">
                    <div class="wsus__section_heading_2">
                        <h5>{{ sectionData($sections, 'feature_products_section', 'sub_title') }}</h5>
                        <h2>{{ sectionData($sections, 'feature_products_section', 'title') }}</h2>
                    </div>
                </div>
                <div class="col-md-6 col-xl-6 wow fadeInUp">
                    <div class="wsus__product_2_collection_btn">
                        <a class="common_btn"
                            href="{{ route('website.products', ['filter' => 'featured']) }}">{{ __('See all Products') }}</a>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($featureProducts as $product)
                    <div class="col-sm-6 col-lg-4 col-xl-3 wow fadeInUp">
                        @include('components::product-theme-2', [
                            'product' => $product,
                        ])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--============================
        PRODUCT 2 COLLECTION END
    ==============================-->
@endif

@if (optional($sections?->call_to_action_section)?->status ?? false)
    <!--============================
        SUMMER COLLECTION START
    ==============================-->
    <section class="wsus__summer_collection">
        <div class="container">
            <div class="wsus__summer_collection_bg"
                style="background: url({{ asset('website/images/summer_collection_bg.webp') }});">
                <div class="row justify-content-between align-items-end">
                    <div class="col-lg-3 col-xl-3 wow fadeInLeft">
                        <div class="wsus__summer_collection_img1">
                            <img class="img-fluid w-100"
                                src="{{ asset(sectionData($sections, 'call_to_action_section', 'cta_image_one')) }}"
                                alt="sc">
                            <span><img class="img-fluid w-100"
                                    src="{{ asset(sectionData($sections, 'call_to_action_section', 'cta_image_discount')) }}"
                                    alt="img"></span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-4 wow fadeInUp">
                        <div class="wsus__summer_collection_text">
                            <div class="wsus__section_heading_2 heading_center">
                                <h5>{{ sectionData($sections, 'call_to_action_section', 'cta_one_subtitle') }}</h5>
                                <h2>{{ sectionData($sections, 'call_to_action_section', 'cta_one_title') }}</h2>
                            </div>
                            <p>{{ sectionData($sections, 'call_to_action_section', 'description') }}</p>
                            <a class="common_btn border_btn"
                                href="{{ sectionData($sections, 'call_to_action_section', 'action_url') }}">{{ sectionData($sections, 'call_to_action_section', 'action_text') }}<i
                                    class="far fa-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xl-3 wow fadeInRight">
                        <div class="wsus__summer_collection_img2">
                            <img class="img-fluid w-100"
                                src="{{ asset(sectionData($sections, 'call_to_action_section', 'cta_image_two')) }}"
                                alt="sc">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        SUMMER COLLECTION END
    ==============================-->
@endif
@if (optional($sections?->hot_deal_section)?->status ?? false)
    <!--============================
        HOT DEALS 2 START
    ==============================-->
    <section class="wsus__hot_deals_2 pt_120 xs_pt_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-xl-3 wow fadeInUp">
                    <div class="wsus__hot_deals_2_bg"
                        style="background: url({{ asset('website/images/hot_deals_2_bg.webp') }});">
                        <div class="wsus__section_heading_2">
                            <h5>{{ sectionData($sections, 'hot_deal_section', 'hot_deal_sub_title') }}</h5>
                            <h2>{{ sectionData($sections, 'hot_deal_section', 'hot_deal_title') }}</h2>
                        </div>
                        <a class="common_btn border_btn"
                            href="{{ sectionData($sections, 'hot_deal_section', 'hot_deal_action_url') }}">{{ sectionData($sections, 'hot_deal_section', 'hot_deal_action_text') }}<i
                                class="far fa-arrow-right" aria-hidden="true"></i></a>
                        <span>
                            <img class="img-fluid w-100"
                                src="{{ asset(sectionData($sections, 'hot_deal_section', 'hot_deal_image')) }}"
                                alt="img">
                        </span>
                    </div>
                </div>
                <div class="col-lg-9 col-xl-9">
                    <div class="row justify-content-between mb_20">
                        <div class="col-md-6 col-lg-7 col-xl-6 wow fadeInUp">
                            <div class="wsus__section_heading_2">
                                <h5>{{ sectionData($sections, 'hot_deal_section', 'subtitle') }}</h5>
                                <h2>{{ sectionData($sections, 'hot_deal_section', 'title') }}</h2>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-5 col-xl-6 wow fadeInUp">
                            <div class="wsus__hot_deals_2_btn">
                                <a
                                    href="{{ route('website.products', ['filter' => optional($sections?->hot_deal_section)?->collection_type]) }}">{{ __('See all Products') }}<i
                                        class="far fa-arrow-right" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($hotDealProducts as $product)
                            <div class="col-sm-6 col-lg-4 col-xl-4 wow fadeInUp">
                                @include('components::product-theme-2', [
                                    'product' => $product,
                                ])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
<!--============================
        HOT DEALS 2 END
    ==============================-->
@if (optional($sections?->blog_section)?->status ?? false)
    <!--============================
        BLOG 2 START
    ==============================-->
    <section class="wsus__blog_2 pt_90 xs_pt_70 pb_60">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 wow fadeInUp">
                    <div class="wsus__section_heading_2 heading_center mb_20">
                        <h5>{{ sectionData($sections, 'blog_section', 'sub_title') }}</h5>
                        <h2>{{ sectionData($sections, 'blog_section', 'title') }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($blogs as $blog)
                    <div class="col-md-6 col-xl-4 wow fadeInUp">
                        @include('components::blog-2')
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--============================
        BLOG 2 END
    ==============================-->
@endif
@if (optional($sections?->benefit_section)?->status ?? false)
    <!--============================
        BENEFITE 2 START
    ==============================-->
    <section class="wsus__benefit_2 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                <div class="col-12 wow fadeInUp">
                    <ul class="wsus__benefit_2_area">
                        <li>
                            <span><img class="img-fluid w-100"
                                    src="{{ asset(sectionData($sections, 'benefit_section', 'area_one_icon')) }}"
                                    alt="icon"></span>
                            <div class="text">
                                <h5 class="title">{{ sectionData($sections, 'benefit_section', 'area_one_title') }}
                                </h5>
                                <p>{{ sectionData($sections, 'benefit_section', 'area_one_sub_title') }}</p>
                            </div>
                        </li>
                        <li>
                            <span><img class="img-fluid w-100"
                                    src="{{ asset(sectionData($sections, 'benefit_section', 'area_two_icon')) }}"
                                    alt="icon"></span>
                            <div class="text">
                                <h5 class="title">{{ sectionData($sections, 'benefit_section', 'area_two_title') }}
                                </h5>
                                <p>{{ sectionData($sections, 'benefit_section', 'area_two_sub_title') }}</p>
                            </div>
                        </li>
                        <li>
                            <span><img class="img-fluid w-100"
                                    src="{{ asset(sectionData($sections, 'benefit_section', 'area_three_icon')) }}"
                                    alt="icon"></span>
                            <div class="text">
                                <h5 class="title">
                                    {{ sectionData($sections, 'benefit_section', 'area_three_title') }}</h5>
                                <p>{{ sectionData($sections, 'benefit_section', 'area_three_sub_title') }}</p>
                            </div>
                        </li>
                        <li>
                            <span><img class="img-fluid w-100"
                                    src="{{ asset(sectionData($sections, 'benefit_section', 'area_four_icon')) }}"
                                    alt="icon"></span>
                            <div class="text">
                                <h5 class="title">
                                    {{ sectionData($sections, 'benefit_section', 'area_four_title') }}</h5>
                                <p>{{ sectionData($sections, 'benefit_section', 'area_four_sub_title') }}</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        BENEFITE 2 END
    ==============================-->
@endif
