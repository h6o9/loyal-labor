@extends('seller.layouts.master')

@section('title')
    <title>{{ __('Product Price List') }}</title>
@endsection

@section('seller-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Product Price List') }}" :list="[
                __('Dashboard') => route('seller.dashboard'),
                __('Product Price List') => '#',
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-0">
                                <form id="search-form" action="" method="GET">
                                    <div class="row">
                                        <div class="col-xxl-6 col-md-12">
                                            <div class="form-group search-wrapper">
                                                <input class="form-control" name="keyword" type="text"
                                                    value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}..." autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-md-4">
                                            <div class="form-group">
                                                <select class="form-control" id="order_by" name="order_by">
                                                    <option value="">{{ __('Order By') }}</option>
                                                    <option value="asc"
                                                        {{ request('order_by') == 'asc' ? 'selected' : '' }}>
                                                        {{ __('ASC') }}
                                                    </option>
                                                    <option value="desc"
                                                        {{ request('order_by') == 'desc' ? 'selected' : '' }}>
                                                        {{ __('DESC') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-md-4">
                                            <div class="form-group">
                                                <select class="form-control" id="par-page" name="par-page">
                                                    <option value="">{{ __('Per Page') }}</option>
                                                    <option value="5" @selected(request('par-page') == '5')>
                                                        {{ __('5') }} {{ __('Products') }}
                                                    </option>
                                                    <option value="10" @selected(request('par-page') == '10')>
                                                        {{ __('10') }} {{ __('Products') }}
                                                    </option>
                                                    <option value="50" @selected(request('par-page') == '50')>
                                                        {{ __('50') }} {{ __('Products') }}
                                                    </option>
                                                    <option value="100" @selected(request('par-page') == '100')>
                                                        {{ __('100') }} {{ __('Products') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-md-4">
                                            <div class="form-group">
                                                <select class="form-control select2" id="brand_id" name="brand_id">
                                                    <option value="" selected disabled>{{ __('Brand') }}
                                                    </option>
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->id }}" @selected($brand->id == request('brand_id'))>
                                                            {{ $brand->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-md-4">
                                            <div class="form-group">
                                                <select class="form-control select2" id="categories" name="category_id">
                                                    <option value="" selected disabled>{{ __('Categories') }}
                                                    </option>
                                                    @foreach ($categories as $cat)
                                                        <option value="{{ $cat->id }}" @selected($cat->id == request('category_id'))>
                                                            {{ $cat->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('Product Price List')" />
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Sku') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Price') }}</th>
                                                <th>{{ __('Manage Offer') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            @foreach ($products as $index => $product)
                                                @if ($product->has_variant)
                                                    <tr class="text-muted">
                                                        <td>
                                                            <b>{{ $product->sku }}</b>
                                                        </td>
                                                        <td>
                                                            <b>{{ $product->name }}</b>
                                                        </td>
                                                        <td>
                                                            ----
                                                        </td>
                                                        <td>
                                                            ---
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $product = $product->loadMissing([
                                                            'variants.optionValues.translation',
                                                            'variants.optionValues.attribute.translation',
                                                        ]);
                                                    @endphp
                                                    @foreach ($product->variants as $variant)
                                                        <tr>
                                                            <td>
                                                                {{ $variant->sku }}
                                                            </td>
                                                            <td>
                                                                <p>{{ $product->name }}</p>
                                                                <p>
                                                                    @if ($variant->optionValues)
                                                                        @foreach ($variant->optionValues as $option)
                                                                            <span class="badge badge-info">
                                                                                {{ $option->attribute->name }}:
                                                                                {{ $option->name }}
                                                                            </span>
                                                                        @endforeach
                                                                    @endif
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <input class="form-control update-price"
                                                                    data-is-variant="true" data-field="price"
                                                                    data-product-variant-id="{{ $variant->id }}"
                                                                    data-product-id="{{ $product->id }}" type="text"
                                                                    value="{{ $variant->price }}">
                                                            </td>
                                                            <td>
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center">
                                                                    <div class="form-group my-2">
                                                                        <select class="form-select update-price"
                                                                            id="offer_price_type_{{ $variant->id }}"
                                                                            name="offer_price_type" data-is-variant="true"
                                                                            data-field="offer_price_type"
                                                                            data-product-variant-id="{{ $variant->id }}"
                                                                            data-product-id="{{ $product->id }}">
                                                                            <option value="">
                                                                                {{ __('Select Offer Price Type') }}
                                                                            </option>
                                                                            <option value="fixed"
                                                                                @selected(old('offer_price_type', $variant->offer_price_type) == 'fixed')>
                                                                                {{ __('Fixed') }}
                                                                            </option>
                                                                            <option value="percentage"
                                                                                @selected(old('offer_price_type', $variant->offer_price_type) == 'percentage')>
                                                                                {{ __('Percentage') }}
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group my-2">
                                                                        <input
                                                                            class="form-control datepicker update-price"
                                                                            id="offer_price_start-{{ $variant->id }}"
                                                                            data-is-variant="true"
                                                                            data-field="offer_price_start"
                                                                            data-product-variant-id="{{ $variant->id }}"
                                                                            data-product-id="{{ $product->id }}"
                                                                            type="text"
                                                                            value="{{ old('offer_price_start', $variant->offer_price_start) }}"
                                                                            placeholder="{{ __('Start Date') }}">
                                                                    </div>
                                                                    <div class="my-2">
                                                                        <span>{{ __('To') }}</span>
                                                                    </div>
                                                                    <div class="form-group my-2">
                                                                        <input
                                                                            class="form-control datepicker update-price"
                                                                            id="offer_price_end-{{ $variant->id }}"
                                                                            data-is-variant="true"
                                                                            data-field="offer_price_end"
                                                                            data-product-variant-id="{{ $variant->id }}"
                                                                            data-product-id="{{ $product->id }}"
                                                                            type="text"
                                                                            value="{{ old('offer_price_end', $variant->offer_price_end) }}"
                                                                            placeholder="{{ __('End Date') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <input class="form-control update-price"
                                                                        id="offer_price-{{ $variant->id }}"
                                                                        data-is-variant="true" data-field="offer_price"
                                                                        data-product-variant-id="{{ $variant->id }}"
                                                                        data-product-id="{{ $product->id }}"
                                                                        type="text"
                                                                        value="{{ old('offer_price', $variant->offer_price) }}">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td>
                                                            {{ $product->sku }}
                                                        </td>
                                                        <td>
                                                            {{ $product->name }}
                                                        </td>
                                                        <td>
                                                            <input class="form-control update-price"
                                                                data-is-variant="false" data-field="price"
                                                                data-product-id="{{ $product->id }}" type="text"
                                                                value="{{ $product->price }}">
                                                        </td>
                                                        <td>
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
                                                                <div class="form-group my-2">
                                                                    <select class="form-select update-price"
                                                                        id="offer_price_type" name="offer_price_type"
                                                                        data-is-variant="false"
                                                                        data-field="offer_price_type"
                                                                        data-product-id="{{ $product->id }}">
                                                                        <option value="">
                                                                            {{ __('Select Offer Price Type') }}
                                                                        </option>
                                                                        <option value="fixed"
                                                                            @selected(old('offer_price_type', $product->offer_price_type) == 'fixed')>
                                                                            {{ __('Fixed') }}
                                                                        </option>
                                                                        <option value="percentage"
                                                                            @selected(old('offer_price_type', $product->offer_price_type) == 'percentage')>
                                                                            {{ __('Percentage') }}
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group my-2">
                                                                    <input class="form-control datepicker update-price"
                                                                        id="offer_price_start" data-is-variant="false"
                                                                        data-field="offer_price_start"
                                                                        data-product-id="{{ $product->id }}"
                                                                        type="text"
                                                                        value="{{ old('offer_price_start', $product->offer_price_start) }}"
                                                                        placeholder="{{ __('Start Date') }}">
                                                                </div>
                                                                <div class="my-2">
                                                                    <span>{{ __('To') }}</span>
                                                                </div>
                                                                <div class="form-group my-2">
                                                                    <input class="form-control datepicker update-price"
                                                                        id="offer_price_end" data-is-variant="false"
                                                                        data-field="offer_price_end"
                                                                        data-product-id="{{ $product->id }}"
                                                                        type="text"
                                                                        value="{{ old('offer_price_end', $product->offer_price_end) }}"
                                                                        placeholder="{{ __('End Date') }}">
                                                                </div>

                                                            </div>
                                                            <div class="form-group">
                                                                <input class="form-control update-price" id="offer_price"
                                                                    data-is-variant="false" data-field="offer_price"
                                                                    data-product-id="{{ $product->id }}" type="text"
                                                                    value="{{ old('offer_price', $product->offer_price) }}">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="d-flex justify-content-center mt-5">
                                        {{ $products->onEachSide(0)->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
    <script>
        "use strict";

        $(document).ready(function() {
            $('.update-price').on('change', function() {
                var field = $(this).data('field');
                var product_id = $(this).data('product-id');
                var product_variant_id = $(this).data('product-variant-id');
                var is_variant = $(this).data('is-variant');
                var value = $(this).val();

                let dataForm = {
                    field: field,
                    value: value,
                    product_id: product_id,
                    _token: '{{ csrf_token() }}',
                };

                if (is_variant) {
                    dataForm.product_variant_id = product_variant_id;
                }

                $.ajax({
                    url: "{{ route('seller.products.price-update.store') }}",
                    type: 'POST',
                    data: dataForm,
                    headers: {
                        "Accept": "application/json",
                    },
                    beforeSend: function() {
                        $('.update-price').attr('disabled', true);
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }

                        if (response.reload) {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    },
                    complete: function() {
                        $('.update-price').attr('disabled', false);
                    }
                });
            });
        });

        $('#search-form').on('change', function() {
            this.submit();
        });
    </script>
@endpush
