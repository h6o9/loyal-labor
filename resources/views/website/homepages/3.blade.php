@if (optional($sections?->hero_section)?->status ?? false)
    <!--============================
        BANNER 3 START
    ==============================-->
    <section class="wsus__banner_3" style="background: url({{ asset('website/images/banner_3_bg.webp') }});">
        <div class="container">
            <div class="row align-items-end">
                <div class="col-lg-6 col-xl-7 wow fadeInLeft">
                    <div class="wsus__banner_3_contant">
                        <h6>{{ sectionData($sections, 'hero_section', 'subtitle') }}</h6>
                        <h1>{{ sectionData($sections, 'hero_section', 'title') }}</h1>
                        <p>{{ sectionData($sections, 'hero_section', 'title') }}</p>
                        <a class="common_btn" href="{{ sectionData($sections, 'hero_section', 'action_button_url') }}"
                            tabindex="0">{{ sectionData($sections, 'hero_section', 'action_button_text') }}<i
                                class="far fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-5 wow fadeInRight">
                    <div class="wsus__banner_3_img">
                        <img class="img-fluid w-100"
                            src="{{ asset(sectionData($sections, 'hero_section', 'banner_image')) }}" alt="banner">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        BANNER 3 END
    ==============================-->
@endif

@if (optional($sections?->featured_category_section)?->status ?? false)
    <!--============================
        CATEGORY 3 START
    ==============================-->
    <section class="wsus__category_3 pt_120 xs_pt_100 pb_25">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 wow fadeInUp">
                    <div class="wsus__section_heading_3 mb_40">
                        <h5>{{ sectionData($sections, 'featured_category_section', 'sub_title') }}</h5>
                        <h2>{{ sectionData($sections, 'featured_category_section', 'title') }}</h2>
                    </div>
                </div>
            </div>
            <div class="row category_3_slider">
                @foreach (allCategories(status: true, featured: true, limit: optional($sections?->featured_category_section)?->limit) as $category)
                    <div class="col-xl-2 wow fadeInUp">
                        @include('components::category-3')
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--============================
        CATEGORY 3 END
    ==============================-->
@endif
@if (optional($sections?->summer_sale_section)?->status ?? false)
    <!--============================
        SUMMER SALE 3 START
    ==============================-->
    <section class="wsus__summersale_3">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-xl-6 wow fadeInLeft">
                    <div class="wsus__summersale_3_item"
                        style="background: url({{ asset('website/images/summersale_3_bg_1.webp') }});">
                        <div class="wsus__section_heading_3">
                            <h5>{{ sectionData($sections, 'summer_sale_section', 'sub_title_one') }}</h5>
                            <h2>{{ sectionData($sections, 'summer_sale_section', 'title_one') }}</h2>
                            <a href="{{ sectionData($sections, 'summer_sale_section', 'action_link_one') }}">{{ sectionData($sections, 'summer_sale_section', 'action_link_text_one') }}<i
                                    class="far fa-arrow-right" aria-hidden="true"></i></a>
                        </div>
                        <div class="img_1">
                            <img class="img-fluid w-100"
                                src="{{ asset(sectionData($sections, 'summer_sale_section', 'image_one')) }}"
                                alt="img">
                        </div>
                        <span><img class="img-fluid w-100" src="{{ asset('website/images/summersale_3_shape.webp') }}"
                                alt="shape"></span>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-6 wow fadeInRight">
                    <div class="wsus__summersale_3_item"
                        style="background: url({{ asset('website/images/summersale_3_bg_2.webp') }});">
                        <div class="wsus__section_heading_3">
                            <h5>{{ sectionData($sections, 'summer_sale_section', 'sub_title_two') }}</h5>
                            <h2>{{ sectionData($sections, 'summer_sale_section', 'title_two') }}</h2>
                            <a href="{{ sectionData($sections, 'summer_sale_section', 'action_link_one') }}">{{ sectionData($sections, 'summer_sale_section', 'action_link_text_two') }}<i
                                    class="far fa-arrow-right" aria-hidden="true"></i></a>
                        </div>
                        <div class="img_2">
                            <img class="img-fluid w-100"
                                src="{{ asset(sectionData($sections, 'summer_sale_section', 'image_two')) }}"
                                alt="img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        SUMMER SALE 3 END
    ==============================-->
@endif
@if (optional($sections?->feature_products_section)?->status ?? false)
    <!--============================
        PRODUCT 3 START
    ==============================-->
    <section class="wsus__product_3 pt_115 xs_pt_95">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 wow fadeInUp">
                    <div class="wsus__section_heading_3 heading_center">
                        <h5>{{ sectionData($sections, 'feature_products_section', 'sub_title') }}</h5>
                        <h2>{{ sectionData($sections, 'feature_products_section', 'title') }}</h2>
                    </div>
                    <div class="wsus__product_3_nav">
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            @foreach ($featureProductCategories as $featureProductCategory)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                        id="pills-{{ $featureProductCategory->slug }}-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-{{ $featureProductCategory->slug }}" type="button"
                                        role="tab" aria-controls="pills-{{ $featureProductCategory->slug }}"
                                        aria-selected="true">{{ $featureProductCategory->name }}</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="tab-content wsus__product_3_tab" id="pills-tabContent">
                @foreach ($featureProductCategories as $featureProductCategory)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                        id="pills-{{ $featureProductCategory->slug }}" role="tabpanel"
                        aria-labelledby="pills-{{ $featureProductCategory->slug }}-tab" tabindex="0">
                        <div class="row">
                            @foreach ($featureProductCategory->products as $product)
                                <div class="col-sm-6 col-lg-4 col-xl-3">
                                    @include('components::product-theme-3', [
                                        'product' => $product,
                                    ])
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--============================
        PRODUCT 3 END
    ==============================-->
@endif
@if (optional($sections?->flash_deal_section)?->status ?? false)
    <!--============================
    LATEST DEALS 3 START
    ==============================-->
    @if ($flashDealsProducts)
        <section class="wsus__latest_deals_3 pt_120 xs_pt_100">
            <div class="container">
                <div class="row justify-content-between mb_20">
                    <div class="col-xl-6 wow fadeInUp">
                        <div class="wsus__section_heading_3">
                            <h5>{{ sectionData($sections, 'flash_deal_section', 'sub_title') }}</h5>
                            <h2>{{ sectionData($sections, 'flash_deal_section', 'title') }}</h2>
                        </div>
                    </div>
                    <div class="col-xl-5 wow fadeInUp">
                        <div class="wsus__latest_deals_3_heading">
                            <p>{{ sectionData($sections, 'flash_deal_section', 'description') }}</p>
                        </div>
                    </div>
                </div>
                <div class="row latest_deals_3">
                    @foreach ($flashDealsProducts as $flashProduct)
                        @php
                            $product = $flashProduct;
                        @endphp

                        <div class="col-xl-6 wow fadeInUp">
                            <div class="wsus__latest_deals_3_item">
                                <div class="img">
                                    <img class="img-fluid w-100" src="{{ asset($product->flash_deal_image) }}"
                                        alt="img">
                                </div>
                                <div class="wsus__latest_deals_3_item_text">
                                    <span class="rating">
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
                                        <b>({{ $product->reviews->count() ?? 0 }} {{ __('Reviews') }})</b>
                                    </span>
                                    <a
                                        href="{{ route('website.product', ['product' => $product->slug]) }}">{{ $product->name }}</a>
                                    <h5>
                                        @if ($product->discounted_price->is_discounted)
                                            {{ currency($product->discounted_price->discounted_price) }}
                                            <del>{{ currency($product->discounted_price->price) }}</del>
                                        @else
                                            {{ currency($product->discounted_price->price ?? $product->price) }}
                                        @endif
                                    </h5>
                                    <p>{{ $product->short_description }}</p>
                                    <div class="simply-countdown flash-deal-countdown"
                                        data-start-date="{{ $product->flash_deal_start }}"
                                        data-end-date="{{ $flashProduct->flash_deal_end }}">
                                    </div>
                                    <b>{{ __('Available') }}: {{ $flashProduct->flash_deal_qty ?? 0 }} -
                                        {{ __('Sold') }}:
                                        <span>{{ $product->order_details_count ?? 0 }}</span></b>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!--============================
        LATEST DEALS 3 END
    ==============================-->
@endif
@if (optional($sections?->top_products_section)?->status ?? false)
    <!--============================
        TOP PRODUCT 3 START
    ==============================-->
    <section class="wsus__top_product_3 pt_70 xs_pt_50">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-xl-4 wow fadeInLeft">
                    <div class="wsus__top_product_3_area">
                        <h5>{{ sectionData($sections, 'top_products_section', 'section_one_title') }}</h5>
                        <div class="row top_product_3">
                            @foreach ($topProductsSectionOne as $product)
                                <div class="col-12">
                                    <div class="wsus__top_product_3_item">
                                        <div class="img">
                                            <img class="img-fluid w-100" src="{{ asset($product->thumbnail_image) }}"
                                                alt="{{ $product->name }}">
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
                                                <b>({{ $product->reviews->count() ?? 0 }} {{ __('Reviews') }})</b>
                                            </span>
                                            <a class="title"
                                                href="{{ route('website.product', ['product' => $product->slug]) }}">{{ $product->name }}</a>
                                            <h6>
                                                @if ($product->discounted_price->is_discounted)
                                                    {{ currency($product->discounted_price->discounted_price) }}
                                                    <del>{{ currency($product->discounted_price->price) }}</del>
                                                @else
                                                    {{ currency($product->discounted_price->price ?? $product->price) }}
                                                @endif
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4 wow fadeInUp">
                    <div class="wsus__top_product_3_bg"
                        style="background: url({{ asset(sectionData($sections, 'top_products_section', 'banner_image')) }});">
                        <div class="wsus__section_heading_3">
                            <h5>{{ sectionData($sections, 'top_products_section', 'banner_subtitle') }}</h5>
                            <h2>{{ sectionData($sections, 'top_products_section', 'banner_title') }}</h2>
                            <a href="{{ sectionData($sections, 'top_products_section', 'banner_link') }}">{{ sectionData($sections, 'top_products_section', 'banner_link_text') }}<i
                                    class="far fa-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4 wow fadeInRight">
                    <div class="wsus__top_product_3_area">
                        <h5>{{ sectionData($sections, 'top_products_section', 'section_two_title') }}</h5>
                        <div class="row top_product_3">
                            @foreach ($topProductsSectionTwo as $product)
                                <div class="col-12">
                                    <div class="wsus__top_product_3_item">
                                        <div class="img">
                                            <img class="img-fluid w-100" src="{{ asset($product->thumbnail_image) }}"
                                                alt="{{ $product->name }}">
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
                                                <b>({{ $product->reviews->count() ?? 0 }} {{ __('Reviews') }})</b>
                                            </span>
                                            <a class="title"
                                                href="{{ route('website.product', ['product' => $product->slug]) }}">{{ $product->name }}</a>
                                            <h6>
                                                @if ($product->discounted_price->is_discounted)
                                                    {{ currency($product->discounted_price->discounted_price) }}
                                                    <del>{{ currency($product->discounted_price->price) }}</del>
                                                @else
                                                    {{ currency($product->discounted_price->price ?? $product->price) }}
                                                @endif
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        TOP PRODUCT 3 END
    ==============================-->
@endif
@if (optional($sections?->popular_products_section)?->status ?? false)
    <!--============================
        POPULAR PRODUCT 3 START
    ==============================-->
    <section class="wsus__popular_product_3 pt_120 xs_pt_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-xl-3 wow fadeInUp">
                    <div class="wsus__popular_product_3_bg"
                        style="background: url({{ asset('website/images/popular_product_3_bg.webp') }});">
                        <div class="wsus__section_heading_3 mb_25">
                            <h5>{{ sectionData($sections, 'popular_products_section', 'banner_subtitle') }}</h5>
                            <h2>{{ sectionData($sections, 'popular_products_section', 'banner_title') }}</h2>
                        </div>
                        <div class="img">
                            <img class="img-fluid w-100"
                                src="{{ asset(sectionData($sections, 'popular_products_section', 'banner_image')) }}"
                                alt="img">
                        </div>
                        <a class="common_btn"
                            href="{{ sectionData($sections, 'popular_products_section', 'banner_link') }}"
                            tabindex="0">{{ sectionData($sections, 'popular_products_section', 'banner_link_text') }}<i
                                class="far fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="col-lg-8 col-xl-9">
                    <div class="wsus__popular_product_3_area wow fadeInUp">
                        <div class="wsus__section_heading_3 mb_25">
                            <h5>{{ sectionData($sections, 'popular_products_section', 'sub_title') }}</h5>
                            <h2>{{ sectionData($sections, 'popular_products_section', 'title') }}</h2>
                        </div>
                    </div>
                    <div class="row popular_product_3">
                        @foreach ($popularProducts as $product)
                            <div class="col-xl-4 wow fadeInUp">
                                @include('components::product-theme-3', [
                                    'product' => $product,
                                ])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        POPULAR PRODUCT 3 END
    ==============================-->
@endif
@if (optional($sections?->blog_section)?->status ?? false)
    <!--============================
        BLOG 3 START
    ==============================-->
    <section class="wsus__blog_3 pt_120 xs_pt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 wow fadeInUp">
                    <div class="wsus__section_heading_3 heading_center mb_25">
                        <h5>{{ sectionData($sections, 'blog_section', 'sub_title') }}</h5>
                        <h2>{{ sectionData($sections, 'blog_section', 'title') }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($blogs as $blog)
                    <div class="col-md-6 col-xl-4 wow fadeInUp">
                        @include('components::blog-3')
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--============================
        BLOG 3 END
    ==============================-->
@endif
@if (optional($sections?->benefit_section)?->status ?? false)
    <!--============================
        BENEFITE 3 START
    ==============================-->
    <section class="wsus__benefit_3">
        <div class="container">
            <div class="row">
                <div class="col-12 wow fadeInUp">
                    <ul class="wsus__benefit_3_area">
                        <li>
                            <span><img class="img-fluid w-100"
                                    src="{{ asset(sectionData($sections, 'benefit_section', 'area_one_icon')) }}"
                                    alt="icon"></span>
                            <div class="text">
                                <h5 class="title">
                                    {{ sectionData($sections, 'benefit_section', 'area_one_title') }}
                                </h5>
                                <p>{{ sectionData($sections, 'benefit_section', 'area_one_sub_title') }}</p>
                            </div>
                        </li>
                        <li>
                            <span><img class="img-fluid w-100"
                                    src="{{ asset(sectionData($sections, 'benefit_section', 'area_two_icon')) }}"
                                    alt="icon"></span>
                            <div class="text">
                                <h5 class="title">
                                    {{ sectionData($sections, 'benefit_section', 'area_two_title') }}
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
        BENEFITE 3 END
    ==============================-->
@endif
