@extends('seller.layouts.master')

@section('title')
    <title>
        {{ $product->name }} {{ __('Details') }}</title>
@endsection

@section('seller-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Product List') }}" :list="[
                __('Dashboard') => route('seller.dashboard'),
                __('Product List') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div class="card-title">
                                    <h4>{{ __('Product Details') }}</h4>
                                </div>
                                <div class="card-header-action">
                                    <a class="btn btn-primary" href="{{ route('seller.product.index') }}"><i
                                            class="fa fa-arrow-left"></i> {{ __('Back') }}</a>

                                    <a class="btn btn-warning" href="{{ route('seller.product.edit', $product->id) }}"><i
                                            class="fa fa-edit"></i> {{ __('Edit') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-lg-3 col-xxl-3 row" id="product_images">
                                        <div class="mb-4">
                                            <h5 class="fw-bold mb-3">{{ __('Thumbnail') }}</h5>
                                            <img class="img-fluid rounded shadow-sm w-100"
                                                src="{{ asset($product->thumbnail_image) }}" alt="Thumbnail Image"
                                                style="max-height: 400px; object-fit: contain;" loading="lazy">
                                        </div>
                                        @if ($product->gallery->count())
                                            <div class="mb-4">
                                                <h5 class="fw-bold mb-3">{{ __('Gallery') }}</h5>
                                                <div class="carousel slide" id="galleryCarousel" data-bs-ride="carousel">
                                                    <div class="carousel-indicators">
                                                        @foreach ($product->gallery as $index => $image)
                                                            <button class="{{ $index === 0 ? 'active' : '' }}"
                                                                data-bs-target="#galleryCarousel"
                                                                data-bs-slide-to="{{ $index }}" type="button"
                                                                aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                                                aria-label="Slide {{ $index + 1 }}"></button>
                                                        @endforeach
                                                    </div>
                                                    <div class="carousel-inner rounded">
                                                        @foreach ($product->gallery as $index => $image)
                                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                                <img class="d-block w-100 img-thumbnail"
                                                                    src="{{ asset($image->path) }}" alt="Gallery Image"
                                                                    style="max-height: 400px; object-fit: contain;"
                                                                    loading="lazy">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <button class="carousel-control-prev" data-bs-target="#galleryCarousel"
                                                        data-bs-slide="prev" type="button">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">{{ __('Previous') }}</span>
                                                    </button>
                                                    <button class="carousel-control-next" data-bs-target="#galleryCarousel"
                                                        data-bs-slide="next" type="button">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">{{ __('Next') }}</span>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($product->variantImage->count())
                                            <div class="mb-4">
                                                <h5 class="fw-bold mb-3">{{ __('Variant Images') }}</h5>
                                                <div class="carousel slide" id="variantCarousel" data-bs-ride="carousel">
                                                    <div class="carousel-indicators">
                                                        @foreach ($product->variantImage as $index => $variantImage)
                                                            <button class="{{ $index === 0 ? 'active' : '' }}"
                                                                data-bs-target="#variantCarousel"
                                                                data-bs-slide-to="{{ $index }}" type="button"
                                                                aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                                                aria-label="Slide {{ $index + 1 }}"></button>
                                                        @endforeach
                                                    </div>
                                                    <div class="carousel-inner rounded">
                                                        @foreach ($product->variantImage as $index => $variantImage)
                                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                                <img class="d-block w-100 img-thumbnail"
                                                                    src="{{ asset($variantImage->image) }}"
                                                                    alt="Variant Image"
                                                                    style="max-height: 400px; object-fit: contain;"
                                                                    loading="lazy">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <button class="carousel-control-prev" data-bs-target="#variantCarousel"
                                                        data-bs-slide="prev" type="button">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">{{ __('Previous') }}</span>
                                                    </button>
                                                    <button class="carousel-control-next" data-bs-target="#variantCarousel"
                                                        data-bs-slide="next" type="button">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">{{ __('Next') }}</span>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif

                                    </div>

                                    <div class="col-md-8 col-lg-9 col-xxl-9">
                                        <div class="container-fluid mt-4">
                                            <div class="row mb-3">
                                                <div class="col-md-3 fw-bold">{{ __('Name') }}</div>
                                                <div class="col-md-9">{{ $product->name }}</div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-3 fw-bold">{{ __('Category') }}</div>
                                                <div class="col-md-9">
                                                    @foreach ($product->categories as $category)
                                                        <span class="badge bg-primary">{{ $category->name }}</span>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-3 fw-bold">{{ __('Brand') }}</div>
                                                <div class="col-md-9">{{ $product->brand?->name }}</div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-3 fw-bold">{{ __('Unit') }}</div>
                                                <div class="col-md-9">{{ $product->unit?->name }}</div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-3 fw-bold">{{ __('Tags') }}</div>
                                                <div class="col-md-9">
                                                    @foreach ($product->tags as $tag)
                                                        <span class="badge bg-primary">{{ $tag->name }}</span>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-3 fw-bold">{{ __('Labels') }}</div>
                                                <div class="col-md-9">
                                                    @foreach ($product->labels as $label)
                                                        <span class="badge bg-primary">{{ $label->name }}</span>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-3 fw-bold">{{ __('Price') }}</div>
                                                <div class="col-md-9">
                                                    {{ currency($product->price) }}
                                                </div>
                                            </div>

                                            @if ($product->is_flash_deal)
                                                <div class="row mb-3">
                                                    <div class="col-md-3 fw-bold">{{ __('Flash Deal Start Date') }}</div>
                                                    <div class="col-md-9">
                                                        {{ formattedDate($product->flash_deal_start) }}
                                                    </div>
                                                    <div class="col-md-3 fw-bold">{{ __('Flash Deal End Date') }}</div>
                                                    <div class="col-md-9">
                                                        {{ formattedDate($product->flash_deal_end) }}
                                                    </div>
                                                    <div class="col-md-3 fw-bold">{{ __('Flash Deal Price') }}</div>
                                                    <div class="col-md-9">
                                                        {{ currency($product->flash_deal_price) }}
                                                    </div>
                                                    <div class="col-md-3 fw-bold">{{ __('Flash Deal Quantity') }}</div>
                                                    <div class="col-md-9">
                                                        {{ $product->flash_deal_qty }} {{ $product->unit?->name ?? '' }}
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($product->offer_price)
                                                <div class="row mb-3">
                                                    <div class="col-md-3 fw-bold">{{ __('Offer Price') }}</div>
                                                    <div class="col-md-9">
                                                        @if ($product->offer_price_type == 'fixed')
                                                            {{ currency($product->offer_price) }} ({{ __('Start Date') }}:
                                                            {{ $product->offer_price_start }} - {{ __('End Date') }}:
                                                            {{ $product->offer_price_end }})
                                                        @else
                                                            {{ $product->offer_price }}% ({{ __('Start Date') }}:
                                                            {{ $product->offer_price_start }} - {{ __('End Date') }}:
                                                            {{ $product->offer_price_end }})
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="row mb-3">
                                                <div class="col-md-3 fw-bold">{{ __('Manage Stock') }}</div>
                                                <div class="col-md-9">
                                                    {{ $product->manage_stock ? __('Yes') : __('No') }}
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-3 fw-bold">{{ __('Stock Quantity') }}</div>
                                                <div class="col-md-9">
                                                    {{ $product->manageStocks->quantity ?? 0 }}
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-3 fw-bold">{{ __('Stock Status') }}</div>
                                                <div class="col-md-9">
                                                    {{ $product->stock_status }}
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-3 fw-bold">{{ __('Allow Checkout When Out Of Stock') }}
                                                </div>
                                                <div class="col-md-9">
                                                    {{ $product->allow_checkout_when_out_of_stock ? __('Yes') : __('No') }}
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-3 fw-bold">{{ __('Is Cash Delivery') }}</div>
                                                <div class="col-md-9">
                                                    {{ $product->is_cash_delivery ? __('Yes') : __('No') }}
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-3 fw-bold">{{ __('Is Return') }}</div>
                                                <div class="col-md-9">
                                                    {{ $product->is_return ? __('Yes') : __('No') }}
                                                    <span>({{ $product->productReturnPolicy->question ?? '' }})</span>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-3 fw-bold">{{ __('Is Featured') }}</div>
                                                <div class="col-md-9">
                                                    {{ $product->is_featured ? __('Yes') : __('No') }}
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-3 fw-bold">{{ __('Is Popular') }}</div>
                                                <div class="col-md-9">
                                                    {{ $product->is_popular ? __('Yes') : __('No') }}
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-3 fw-bold">{{ __('Is Best Selling') }}</div>
                                                <div class="col-md-9">
                                                    {{ $product->is_best_selling ? __('Yes') : __('No') }}
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-3 fw-bold">{{ __('Views') }}</div>
                                                <div class="col-md-9">
                                                    {{ $product->viewed }}
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-3 fw-bold">{{ __('Short Description') }}</div>
                                                <div class="col-md-9">{{ $product->short_description }}</div>
                                            </div>
                                        </div>
                                        @php
                                            $product = $product->loadMissing([
                                                'variants.product.unit',
                                                'variants.optionValues.translation',
                                                'variants.optionValues.attribute.translation',
                                            ]);
                                        @endphp
                                        @if ($product->variants)
                                            <div class="container-fluid mt-5">
                                                <div class="d-flex justify-content-between mb-3">
                                                    <h5>
                                                        <a
                                                            href="{{ route('seller.product.product-variant', ['id' => $product->id]) }}">{{ __('Product Variants') }}</a>
                                                        <span
                                                            class="badge bg-info">{{ $product->variants->count() }}</span>
                                                    </h5>
                                                    <div>
                                                        <a class="btn btn-success"
                                                            href="{{ route('seller.products.product-prices', ['keyword' => $product->sku]) }}">{{ __('Update Price') }}</a>
                                                        <a class="btn btn-info"
                                                            href="{{ route('seller.products.product-inventory', ['keyword' => $product->sku]) }}">{{ __('Update Stock') }}</a>
                                                    </div>
                                                </div>
                                                <div class="row fw-bold border-bottom pb-2 mb-3">
                                                    <div class="col-md-3">{{ __('SKU') }}</div>
                                                    <div class="col-md-3">{{ __('Options') }}</div>
                                                    <div class="col-md-3">{{ __('Price') }}</div>
                                                    <div class="col-md-3">{{ __('Qty') }}</div>
                                                </div>

                                                <div class="scrollable">
                                                    @foreach ($product->variants as $variant)
                                                        <div class="row py-2 border-bottom">
                                                            <div class="col-md-3">
                                                                {{ $variant->sku }}
                                                            </div>

                                                            <div class="col-md-3">
                                                                @if ($variant->optionValues)
                                                                    @foreach ($variant->optionValues as $option)
                                                                        <span class="badge bg-info text-dark me-1 mb-1">
                                                                            {{ $option?->attribute?->name }}:
                                                                            {{ $option->name }}
                                                                        </span>
                                                                    @endforeach
                                                                @endif
                                                            </div>

                                                            <div class="col-md-3">
                                                                {{ currency($variant->discount->discounted_price) }}
                                                                ({{ $variant->discount->discount_percent }}%)
                                                            </div>

                                                            <div class="col-md-3">
                                                                {{ $variant->stock_qty }}
                                                                {{ optional($product->unit)->name }}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-2">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('Recent Orders') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">

                                            <table class="table table-striped">
                                                <tr>
                                                    <th>{{ __('SN') }}</th>
                                                    <th>{{ __('User') }}</th>
                                                    <th>{{ __('Order ID') }}</th>
                                                    <th>{{ __('Price') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                    <th>{{ __('Payment') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>

                                                @forelse ($orders as $index => $order)
                                                    <tr>
                                                        <td>{{ ++$index }}</td>
                                                        <td>{{ $order?->user?->name }}</td>
                                                        <td>#{{ $order->order_id }}</td>
                                                        <td>{{ $order->payable_amount }} {{ $order->payable_currency }}
                                                        </td>

                                                        <td>
                                                            {{ $order->order_status->getLabel() }}
                                                        </td>

                                                        <td>
                                                            {{ $order->paymentDetails->payment_status->getLabel() }}
                                                            ({{ $order->payment_method }})
                                                        </td>

                                                        <td>
                                                            <a class="btn btn-primary btn-sm"
                                                                href="{{ route('seller.orders.show', $order->order_id) }}">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <x-empty-table :name="__('Orders')" route="" create="no"
                                                        :message="__('No data found!')" colspan="7">
                                                    </x-empty-table>
                                                @endforelse
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-2">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('Recent Reviews') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">

                                                <table class="table table-striped">
                                                    <tr>
                                                        <th>{{ __('SN') }}</th>
                                                        <th>{{ __('User') }}</th>
                                                        <th>{{ __('Order') }}</th>
                                                        <th>{{ __('Review') }}</th>
                                                        <th>{{ __('Rating') }}</th>
                                                        <th>{{ __('Action') }}</th>
                                                    </tr>

                                                    @forelse ($product->reviews as $index => $review)
                                                        <tr>
                                                            <td>{{ ++$index }}</td>
                                                            <td>{{ $review->user?->name ?? __('Anonymous') }}</td>
                                                            <td>#{{ $review->order?->order_id ?? '-----' }}</td>
                                                            <td>{{ str($review->review)->limit(100) }}</td>
                                                            <td>{{ $review->rating }} {{ __('Star') }}</td>
                                                            <td>
                                                                <a href="">
                                                                    <button class="btn btn-primary btn-sm">
                                                                        <i class="fa fa-eye"></i>
                                                                    </button>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td class="text-center" colspan="6">
                                                                {{ __('No reviews found!') }}
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </table>
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
    </div>
@endsection

@push('css')
    <style>
        .scrollable {
            max-height: 500px;
            overflow-y: auto;
            display: block;
            widows: 100%;
        }
    </style>
@endpush
@push('js')
    <script></script>
@endpush
