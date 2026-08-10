@if (optional($sections?->hero_section)?->status ?? false)
    <!--============================
        BANNER 4 START
    ==============================-->
    <section class="wsus__banner_4" style="background: url({{ asset('website/images/banner_4_bg.webp') }});">
        <div class="row justify-content-between">
            <div class="col-lg-3 col-xl-3 wow fadeInLeft">
                <div class="wsus__banner_4_img_1">
                    <img src="{{ asset(sectionData($sections, 'hero_section', 'product_one_image')) }}"
                        alt="img-fluid w-100">
                    <span>
                        <img class="img-fluid w-100"
                            src="{{ asset(sectionData($sections, 'hero_section', 'product_one_label_image')) }}"
                            alt="img">
                    </span>
                </div>
            </div>
            <div class="col-lg-6 col-xl-6 wow fadeInUp">
                <div class="wsus__banner_4_text">
                    <h1>{!! sectionData($sections, 'hero_section', 'title') !!}</h1>
                    <ul>
                        <li>
                            <h6>{{ sectionData($sections, 'hero_section', 'label_one') }}</h6>
                            <p>{{ sectionData($sections, 'hero_section', 'label_one_text') }}</p>
                        </li>
                        <li>
                            <h6>{{ sectionData($sections, 'hero_section', 'label_two') }}</h6>
                            <p>{{ sectionData($sections, 'hero_section', 'label_two_text') }}</p>
                        </li>
                        <li>
                            <h6>{{ sectionData($sections, 'hero_section', 'label_two_text') }}</h6>
                            <p>{{ sectionData($sections, 'hero_section', 'label_two_text') }}</p>
                        </li>
                    </ul>
                    <a class="common_btn"
                        href="{{ sectionData($sections, 'hero_section', 'action_link') }}">{{ sectionData($sections, 'hero_section', 'action_button_text') }}</a>
                </div>
            </div>
            <div class="col-lg-3 col-xl-3 wow fadeInRight">
                <div class="wsus__banner_4_img_2">
                    <img src="{{ asset(sectionData($sections, 'hero_section', 'product_two_image')) }}"
                        alt="img-fluid w-100">
                    <span>
                        <img class="img-fluid w-100"
                            src="{{ asset(sectionData($sections, 'hero_section', 'product_two_label_image')) }}"
                            alt="img">
                    </span>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        BANNER 4 END
    ==============================-->
@endif

@if (optional($sections?->featured_category_section)?->status ?? false)
    <!--============================
        CATAGORY 4  START
    ==============================-->
    <section class="wsus__category_4 pt_80">
        <div class="container">
            <div class="row">
                @foreach (allCategories(status: true, featured: true, limit: optional($sections?->featured_category_section)?->limit) as $category)
                    <div class="col-xl-2 col-sm-6 col-lg-4 wow fadeInUp">
                        @include('components::category-4')
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--============================
        CATAGORY 4  END
    ==============================-->
@endif

@if (optional($sections?->flash_deal_section)?->status ?? false)
    <!--============================
        FLASH DEALS 4 START
    ==============================-->
    @if ($flashDeals)

        @php
            $startDate = sectionData($sections, 'flash_deal_section', 'flash_deal_start_date');
            $endDate = sectionData($sections, 'flash_deal_section', 'flash_deal_end_date');
        @endphp

        <section class="wsus__flash_deals_4 pt_120 xs_pt_100 pb_95 xs_pb_75">
            <div class="container">
                <div class="row mb_20">
                    <div class="col-xl-3 col-lg-5 wow fadeInUp">
                        <div class="wsus__section_heading_4">
                            <h6>{{ sectionData($sections, 'flash_deal_section', 'sub_title') }}</h6>
                            <h2>{{ sectionData($sections, 'flash_deal_section', 'title') }}</h2>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-7 wow fadeInUp">
                        <div class="wsus__flash_deals_header">
                            <div class="wsus__flash_deals_4_count">
                                <img class="img-fluid w-100" src="{{ asset('website/images/clock.webp') }}"
                                    alt="icon">
                                <div class="simply-countdown flash-deal-countdown"
                                    data-start-date="{{ $startDate }}" data-end-date="{{ $endDate }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row flash_deals_3">
                    @foreach ($flashDeals as $flashProduct)
                        <div class="col-xl-3 wow fadeInUp">
                            @include('components::product-theme-4', [
                                'product' => $flashProduct,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!--============================
        FLASH DEALS 4 END
    ==============================-->
@endif

@if (optional($sections?->hot_deal_section)?->status ?? false)
    <!--============================
        HOT DEAL 4 START
    ==============================-->
    <section class="wsus__hot_deal_4">
        <div class="row">
            <div class="col-xl-5 col-xxl-6 wow fadeInLeft">
                <div class="wsus__hot_deal_4_left"
                    style="background: url({{ asset('website/images/hot_deals_4_bg_1.webp') }});">
                    <div class="img">
                        <img class="img-fluid w-100"
                            src="{{ asset(sectionData($sections, 'hot_deal_section', 'deal_one_image')) }}"
                            alt="img">
                    </div>
                    <div class="wsus__section_heading_4">
                        <h6>{{ sectionData($sections, 'hot_deal_section', 'deal_one_subtitle') }}</h6>
                        <h2>{{ sectionData($sections, 'hot_deal_section', 'deal_one_title') }}</h2>
                        <a class="common_btn"
                            href="{{ sectionData($sections, 'hot_deal_section', 'deal_one_button_url') }}">{{ sectionData($sections, 'hot_deal_section', 'deal_one_subtitle') }}<i
                                class="far fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 col-xxl-6">
                <div class="row">
                    <div class="col-xl-5 wow fadeInUp">
                        <div class="wsus__hot_deal_4_medal"
                            style="background: url({{ asset('website/images/hot_deals_4_bg_2.webp') }});">
                            <h2>{{ sectionData($sections, 'hot_deal_section', 'deal_two_title') }}</h2>
                            <div class="simply-countdown simply-countdown-one"
                                data-start-time="{{ sectionData($sections, 'hot_deal_section', 'deal_two_start_time') }}"
                                data-end-time="{{ sectionData($sections, 'hot_deal_section', 'deal_two_end_time') }}">
                            </div>
                            <a class="common_btn"
                                href="{{ sectionData($sections, 'hot_deal_section', 'deal_two_button_url') }}">{{ sectionData($sections, 'hot_deal_section', 'deal_two_button_text') }}<i
                                    class="far fa-arrow-right" aria-hidden="true"></i></a>
                            <div class="img">
                                <img class="img-fluid w-100"
                                    src="{{ asset(sectionData($sections, 'hot_deal_section', 'deal_two_image')) }}"
                                    alt="img">
                                <span><img class="img-fluid w-100"
                                        src="{{ asset(sectionData($sections, 'hot_deal_section', 'deal_two_label_image')) }}"
                                        alt="offer"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7 wow fadeInRight">
                        <div class="wsus__hot_deal_4_right">
                            <div class="img">
                                <img class="img-fluid w-100"
                                    src="{{ asset(sectionData($sections, 'hot_deal_section', 'deal_three_image')) }}"
                                    alt="img">
                            </div>
                            <div class="wsus__section_heading_4">
                                <h6>{{ sectionData($sections, 'hot_deal_section', 'deal_three_subtitle') }}</h6>
                                <h2>{{ sectionData($sections, 'hot_deal_section', 'deal_three_title') }}</h2>
                                <a href="{{ sectionData($sections, 'hot_deal_section', 'deal_three_button_url') }}">{{ sectionData($sections, 'hot_deal_section', 'deal_three_button_text') }}<i
                                        class="far fa-arrow-right" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="wsus__hot_deal_4_right wsus__hot_deal_4_right_2">
                            <div class="img">
                                <img class="img-fluid w-100"
                                    src="{{ asset(sectionData($sections, 'hot_deal_section', 'deal_four_image')) }}"
                                    alt="img">
                            </div>
                            <div class="wsus__section_heading_4">
                                <h6>{{ sectionData($sections, 'hot_deal_section', 'deal_four_subtitle') }}</h6>
                                <h2>{{ sectionData($sections, 'hot_deal_section', 'deal_four_title') }}</h2>
                                <a href="{{ sectionData($sections, 'hot_deal_section', 'deal_four_button_url') }}">
                                    {{ sectionData($sections, 'hot_deal_section', 'deal_four_button_text') }}<i
                                        class="far fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        HOT DEAL 4 END
    ==============================-->
@endif
@if (optional($sections?->best_sales_section)?->status ?? false)
    <!--============================
        PRODUCT 4 START
    ==============================-->
    <section class="wsus__product_4 pt_120 xs_pt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 wow fadeInUp">
                    <div class="wsus__section_heading_4 heading_center mb_20">
                        <h6>{{ sectionData($sections, 'best_sales_section', 'sub_title') }}</h6>
                        <h2>{{ sectionData($sections, 'best_sales_section', 'title') }}</h2>
                    </div>
                    <div class="wsus__product_4_navs">
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-latest-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-latest" type="button" role="tab"
                                    aria-controls="pills-latest"
                                    aria-selected="true">{{ __('Latest Products') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-best-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-best" type="button" role="tab"
                                    aria-controls="pills-best"
                                    aria-selected="false">{{ __('Best Sellers') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-rated-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-rated" type="button" role="tab"
                                    aria-controls="pills-rated" aria-selected="false">{{ __('Top Rating') }}</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="tab-content wsus__product_4_tab_contant" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-latest" role="tabpanel"
                            aria-labelledby="pills-latest-tab" tabindex="0">
                            <div class="row">
                                @foreach ($bestSellingProducts['latestProducts'] as $latestProduct)
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        @include('components::product-theme-4', [
                                            'product' => $latestProduct,
                                        ])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-best" role="tabpanel" aria-labelledby="pills-best-tab"
                            tabindex="0">
                            <div class="row">
                                @foreach ($bestSellingProducts['mostSalesProducts'] as $latestProduct)
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        @include('components::product-theme-4', [
                                            'product' => $latestProduct,
                                        ])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-rated" role="tabpanel"
                            aria-labelledby="pills-rated-tab" tabindex="0">
                            <div class="row">
                                @foreach ($bestSellingProducts['topRatedProducts'] as $latestProduct)
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        @include('components::product-theme-4', [
                                            'product' => $latestProduct,
                                        ])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!--============================
        PRODUCT 4 END
    ==============================-->
@endif
@if (optional($sections?->discount_section)?->status ?? false)
    <!--============================
        PROCESS 4 START
    ==============================-->
    <section class="wsus__process_4" style="background:url({{ asset('website/images/process_4_bg.webp') }});">
        <div class="row justify-content-between align-items-center">
            <div class="col-xl-4 wow fadeInLeft">
                <div class="wsus__process_4_bg">
                    <img class="img-fluid w-100"
                        src="{{ asset(sectionData($sections, 'discount_section', 'video_thumbnail')) }}"
                        alt="img">
                    @if (sectionData($sections, 'discount_section', 'video_one_status') == 'active')
                        <a class="play_btn_1 venobox vbox-item" data-autoplay="true" data-vbtype="video"
                            href="{{ sectionData($sections, 'discount_section', 'video_one_link') }}">
                            <img class="img-fluid w-100" src="{{ asset('website/images/play_btn.webp') }}"
                                alt="icon">
                        </a>
                    @endif
                    @if (sectionData($sections, 'discount_section', 'video_two_status') == 'active')
                        <a class="play_btn_2 venobox vbox-item" data-autoplay="true" data-vbtype="video"
                            href="{{ sectionData($sections, 'discount_section', 'video_two_link') }}">
                            <img class="img-fluid w-100" src="{{ asset('website/images/play_btn.webp') }}"
                                alt="icon">
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-lg-5 col-xl-3 wow fadeInLeft">
                <div class="wsus__process_4_img">
                    <div class="img">
                        <img class="img-fluid w-100"
                            src="{{ asset(sectionData($sections, 'discount_section', 'product_image')) }}"
                            alt="img">
                        <span><img class="img-fluid w-100"
                                src="{{ asset(sectionData($sections, 'discount_section', 'product_label_image')) }}"
                                alt="offer"></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-xl-4 wow fadeInUp">
                <div class="wsus__process_4_text">
                    <h1>{{ sectionData($sections, 'discount_section', 'title') }}</h1>
                    <ul>
                        <li>
                            <span><img class="img-fluid w-100"
                                    src="{{ asset(sectionData($sections, 'discount_section', 'process_one_icon')) }}"
                                    alt="icon"></span>
                            <div class="text">
                                <h6>{{ sectionData($sections, 'discount_section', 'process_one_title') }}</h6>
                                <p>{{ sectionData($sections, 'discount_section', 'process_one_subtitle') }}</p>
                            </div>
                        </li>
                        <li>
                            <span><img class="img-fluid w-100"
                                    src="{{ asset(sectionData($sections, 'discount_section', 'process_two_icon')) }}"
                                    alt="icon"></span>
                            <div class="text">
                                <h6>{{ sectionData($sections, 'discount_section', 'process_two_title') }}</h6>
                                <p>{{ sectionData($sections, 'discount_section', 'process_two_subtitle') }}</p>
                            </div>
                        </li>
                        <li>
                            <span><img class="img-fluid w-100"
                                    src="{{ asset(sectionData($sections, 'discount_section', 'process_three_icon')) }}"
                                    alt="icon"></span>
                            <div class="text">
                                <h6>{{ sectionData($sections, 'discount_section', 'process_three_title') }}</h6>
                                <p>{{ sectionData($sections, 'discount_section', 'process_three_subtitle') }}</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        PROCESS 4 END
    ==============================-->
@endif
@if (optional($sections?->top_selling_section)?->status ?? false)
    <!--============================
        TOP SELLING 4 START
    ==============================-->
    <section class="wsus__top_selling_4 pt_95 xs_pt_75 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-xl-6 wow fadeInUp">
                    <div class="wsus__top_selling_4_text"
                        style="background: url({{ asset('website/images/top_selling_4_bg.webp') }});">
                        <div class="wsus__section_heading_4">
                            <h6>{{ sectionData($sections, 'top_selling_section', 'sub_title') }}</h6>
                            <h2>{{ sectionData($sections, 'top_selling_section', 'title') }}</h2>
                        </div>
                        <p>{{ sectionData($sections, 'top_selling_section', 'top_selling_price_label') }} : <span>
                                {{ currency(sectionData($sections, 'top_selling_section', 'top_selling_price')) }}</span>
                        </p>
                        <a class="common_btn"
                            href="{{ sectionData($sections, 'top_selling_section', 'action_url') }}">{{ sectionData($sections, 'top_selling_section', 'action_button_text') }}<i
                                class="far fa-arrow-right" aria-hidden="true"></i></a>
                        <div class="img">
                            <img class="img-fluid w-100"
                                src="{{ asset(sectionData($sections, 'top_selling_section', 'top_selling_image')) }}"
                                alt="selling">
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-xl-6 wow fadeInUp">
                    <div class="row">
                        @foreach ($topSellingProducts as $latestProduct)
                            <div class="col-sm-6 col-xl-6">
                                @include('components::product-theme-4', [
                                    'product' => $latestProduct,
                                ])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        TOP SELLING 4 END
    ==============================-->
@endif
@if (optional($sections?->filtered_product_section)?->status ?? false)
    <!--============================
        FILTER PRODUCT 4 START
    ==============================-->
    @if ($filteredProduct)
        <section class="wsus__filter_product_4 pt_120 xs_pt_100 pb_120 xs_pb_100"
            style="background: url({{ asset('website/images/buy_product_bg.webp') }});">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-xl-6 wow fadeInLeft">
                        <div class="wsus__filter_product_4_img">
                            <div class="img">
                                <img class="img-fluid w-100"
                                    src="{{ asset(sectionData($sections, 'filtered_product_section', 'product_image')) }}"
                                    alt="img">
                            </div>
                            <span><img class="img-fluid w-100"
                                    src="{{ asset(sectionData($sections, 'filtered_product_section', 'product_label_image')) }}"
                                    alt="img"></span>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-6 wow fadeInUp">
                        <div class="wsus__filter_product_4_text">
                            <div class="wsus__section_heading_4">
                                <h6>{{ $filteredProduct?->labels->pluck('name')->implode(', ') ?? '' }}</h6>
                                <h2>{{ $filteredProduct->name }}</h2>
                            </div>
                            <h4>
                                @if ($filteredProduct->discounted_price->is_discounted)
                                    {{ currency($filteredProduct->discounted_price->discounted_price) }}
                                    <del>{{ currency($filteredProduct->discounted_price->price) }}</del>
                                @else
                                    {{ currency($filteredProduct->discounted_price->price) }}
                                @endif
                            </h4>
                            @if ($filteredProduct->brand->name ?? false)
                                <div class="wsus__filter_product_4_brand">
                                    <h5>{{ __('Brand') }}:</h5>
                                    <ul>
                                        <li>
                                            <span>
                                                <img class="img-fluid w-100"
                                                    src="{{ asset('website/images/filter_icon_2.webp') }}"
                                                    alt="icon">
                                            </span>
                                            {{ $filteredProduct->brand->name ?? '' }}
                                        </li>
                                    </ul>
                                </div>
                            @endif
                            <p>
                                {{ $filteredProduct->short_description }}
                            </p>
                            <form class="wsus__filter_product_4_bottom" action="javascript:void()">
                                <button class="view_single_product_modal" data-id="{{ $filteredProduct->id }}"
                                    role="button" rel="nofollow">
                                    <img class="img-fluid w-100" src="{{ asset('website/images/eye.webp') }}"
                                        alt="icon">
                                    {{ __('View Details') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!--============================
        FILTER PRODUCT 4 END
    ==============================-->
@endif
@if (optional($sections?->blog_section)?->status ?? false)
    <!--============================
        BLOG 4 START
    ==============================-->
    <section class="wsus__blog_4 pt_120 xs_pt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 wow fadeInUp">
                    <div class="wsus__section_heading_4 mb_45">
                        <h6>{{ sectionData($sections, 'blog_section', 'sub_title') }}</h6>
                        <h2>{{ sectionData($sections, 'blog_section', 'title') }}</h2>
                    </div>
                </div>
            </div>
            <div class="row blog_4">
                @foreach ($blogs as $blog)
                    <div class="col-xl-4 wow fadeInUp">
                        @include('components::blog-4')
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--============================
        BLOG 4 END
    ==============================-->
@endif
