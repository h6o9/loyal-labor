@extends('website.layouts.app')

@section('title')
    {{ $shop->shop_name }} {{ __('Shop') }} - {{ $setting->app_name }}
@endsection

@section('content')
    <section class="wsus__breadcrumbs" style="background: url({{ asset($setting->breadcrumb_image) }});">
        <div class="wsus__breadcrumbs_overly">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>{{ $shop->shop_name }} {{ __('Shop') }}</h1>
                            <ul>
                                <li>
                                    <a href="{{ route('website.home') }}"><i
                                            class="fas fa-home-lg"></i>{{ __('Home') }}</a>
                                </li>
                                <li>{{ $shop->shop_name }} {{ __('Shop') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="wsus__vendor_details mt_120 xs_mt_100 mb_120 xs_mb_100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="wsus__vendor_details_banner">
                        <div class="img">
                            <img class="img-fluid w-100"
                                src="{{ $shop->banner_image ? asset($shop->banner_image) : asset($setting->default_avatar) }}"
                                alt=" Banner">
                        </div>
                    </div>
                    <div class="vendor_details_text">
                        <div class="logo">
                            <img class="img-fluid w-100"
                                src="{{ $shop->logo_image ? asset($shop->logo_image) : asset($setting->default_avatar) }}"
                                alt="logo">
                        </div>
                        <div class="text">
                            <a class="title" href="javascript:;" role="button" rel="nofollow">{{ $shop->shop_name }}</a>
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
                            <div class="d-flex flex-wrap align-items-center">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt_75 xs_mt_55">
                <div class="col-lg-4 col-xl-3">
                    <div class="wsus__filter_btn d-lg-none">
                        <i class="fas fa-filter" aria-hidden="true"></i>
                        <span>Filter</span>
                    </div>
                    <form class="sidebar_form d-none d-lg-block" id="onChangeSubmit" action="" method="get">
                        <div class="wsus__sidebar_wizard">
                            <div class="accordion accordion-flush wsus__sidebar_accordion" id="accordionFlushExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapseOne" type="button" aria-expanded="false"
                                            aria-controls="flush-collapseOne">
                                            {{ __('Categories') }}
                                        </button>
                                    </h2>
                                    <div class="accordion-collapse collapse show" id="flush-collapseOne"
                                        data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">
                                            @foreach ($categories as $category)
                                                <div class="form-check">
                                                    <input class="form-check-input" id="p_category_{{ $category->id }}"
                                                        name="categories[]" form="onChangeSubmit" type="checkbox"
                                                        value="{{ $category->slug }}" @checked($category->slug == request()->get('category'))
                                                        {{ in_array($category->slug, request()->get('categories', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="p_category_{{ $category->id }}">
                                                        {{ $category->name }}
                                                        <span>{{ $category->total_products_count ?? 0 }}</span>
                                                    </label>
                                                </div>

                                                @foreach ($category->children as $childCategory)
                                                    <div class="form-check ms-3">
                                                        <input class="form-check-input"
                                                            id="p_category_{{ $childCategory->id }}" name="categories[]"
                                                            form="onChangeSubmit" type="checkbox"
                                                            value="{{ $childCategory->slug }}" @checked($childCategory->slug == request()->get('category'))
                                                            {{ in_array($childCategory->slug, request()->get('categories', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="p_category_{{ $childCategory->id }}">
                                                            {{ $childCategory->name }}
                                                            <span>{{ $childCategory->products_count ?? 0 }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wsus__sidebar_wizard wizard">
                            <h3>{{ __('Price Selection') }}</h3>
                            <div class="price_range"></div>
                        </div>
                        <div class="wsus__sidebar_wizard wizard">
                            <h3>{{ __('Color') }}</h3>
                            <p><a
                                    href="{{ route(Route::currentRouteName(), ['slug' => request()->route('slug')]) }}">{{ __('Reset') }}</a>
                            </p>
                            <div class="color-palette">
                                <div class="insider">
                                    @foreach ($colors as $color)
                                        @php
                                            $isChecked = in_array(
                                                $color->name,
                                                old('colors', request()->get('colors', [])) ?? [],
                                            );
                                        @endphp
                                        <label>
                                            <input name="colors[]" form="onChangeSubmit" type="checkbox"
                                                value="{{ $color->name }}" hidden {{ $isChecked ? 'checked' : '' }}>
                                            <div class="color" style="background-color: {{ $color->name }}"></div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="wsus__sidebar_wizard">
                            <div class="accordion accordion-flush wsus__sidebar_accordion" id="accordionFlushExample1">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapseTwo" type="button" aria-expanded="false"
                                            aria-controls="flush-collapseTwo">
                                            {{ __('Filter by Brands') }}
                                        </button>
                                    </h2>
                                    <div class="accordion-collapse collapse show" id="flush-collapseTwo"
                                        data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">
                                            @foreach ($brands as $brand)
                                                <div class="form-check">
                                                    <input class="form-check-input" id="p_brand_{{ $brand->id }}"
                                                        name="brand[]" form="onChangeSubmit" type="checkbox"
                                                        value="{{ $brand->slug }}" @checked(!is_array(request()->get('brand', [])) && request()->get('brand') == $brand->slug)
                                                        {{ is_array(request()->get('brand', [])) && in_array($brand->slug, request()->get('brand', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="p_brand_{{ $brand->id }}">
                                                        {{ $brand->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-8 col-xl-9">
                    <div class="wsus__product_filter wow fadeInUp">
                        @if (count($products) > 0)
                            <p>{{ __('Showing') }} <span>{{ $products->firstItem() }}-{{ $products->lastItem() }}</span>
                                {{ __('Of') }} <span>{{ $products->total() }}</span> {{ __('Results') }}</p>
                        @else
                            <p>{{ __('No Products Found') }}</p>
                        @endif
                        <ul>
                            <li>
                                <select class="select_2 onChangeSubmit" name="sortby" form="onChangeSubmit">
                                    <option value="">{{ __('Default') }}: {{ __('Latest First') }}</option>
                                    <option value="latest" @selected(request()->get('sortby') == 'latest')>{{ __('Latest First') }}</option>
                                    <option value="oldest" @selected(request()->get('sortby') == 'oldest')>{{ __('Oldest First') }}</option>
                                </select>
                            </li>
                            <li>
                                <select class="select_2 onChangeSubmit" name="per-page" form="onChangeSubmit">
                                    <option value="">{{ __('Default') }} : {{ __('16') }} {{ __('items') }}
                                    </option>
                                    <option value="32" @selected(request()->get('per-page') == '16')>{{ __('Show') }} :
                                        {{ __('32') }}</option>
                                    <option value="48" @selected(request()->get('per-page') == '48')>{{ __('Show') }} :
                                        {{ __('48') }}</option>
                                    <option value="64" @selected(request()->get('per-page') == '64')>{{ __('Show') }} :
                                        {{ __('64') }}</option>
                                </select>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        @forelse ($products as $product)
                            <div class="col-sm-6 col-xl-4 wow fadeInUp">
                                @include('components::product-theme-' . config('services.theme'), [
                                    'product' => $product,
                                ])
                            </div>
                        @empty
                            <div class="col-12 wow fadeInUp">
                                <div class="wsus__product_item">
                                    <div class="text">
                                        <p>{{ __('No Products Found') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <div class="row">
                        <div class="col-12 wow fadeInUp">
                            {{ $products->links('components::pagination') }}
                        </div>
                    </div>
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

        $('#onChangeSubmit').on('change', function() {
            $('#onChangeSubmit').submit();
        });

        $('.onChangeSubmit').on('change', function() {
            $('#onChangeSubmit').submit();
        });

        const options = {
            range: {
                min: {{ revertFormattedPrice(currency($minPrice, icon: false)) }},
                max: {{ revertFormattedPrice(currency($maxPrice, icon: false)) }},
                step: 1
            },
            initialSelectedValues: {
                from: {{ request()->filled('from') ? request('from') : revertFormattedPrice(currency($minPrice, icon: false)) }},
                to: {{ request()->filled('to') ? request('to') : revertFormattedPrice(currency($maxPrice, icon: false)) ?? 100 }}
            },
            theme: "dark",
            orientation: "horizontal",
        };

        $('.price_range').alRangeSlider(options);

        @if (request()->filled('from') && request()->filled('to'))
            $('.price_range').alRangeSlider('update', {
                values: {
                    from: {{ request()->from }},
                    to: {{ request()->to }}
                },
            });
        @endif

        $('.price_range').on('click', function() {
            $('#onChangeSubmit').submit();
        });
    </script>
@endpush
