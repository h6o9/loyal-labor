@extends('admin.master_layout')
@section('title')
    <title>{{ __('Create Coupon') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Create Coupon') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Coupon List') => route('admin.coupon.index'),
                __('Create Coupon') => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4 class="section_title">{{ __('Create Coupon') }}</h4>
                                <div>
                                    <a class="btn btn-primary" href="{{ route('admin.coupon.index') }}"><i
                                            class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <form class="create_coupon" action="{{ route('admin.coupon.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="name">{{ __('Name') }}<span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="name" name="name"
                                                            type="text" value="{{ old('name') }}">
                                                        @error('name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="coupon_code">{{ __('Coupon Code') }}<span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="coupon_code" name="coupon_code"
                                                            type="text" value="{{ old('coupon_code') }}">
                                                        @error('coupon_code')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="is_percent">{{ __('Discount Type') }}<span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-select" id="is_percent" name="is_percent">
                                                            <option value="1"
                                                                {{ old('is_percent') == 1 ? 'selected' : '' }}>
                                                                {{ __('Percentage') }} </option>
                                                            <option value="0"
                                                                {{ old('is_percent') == 0 ? 'selected' : '' }}>
                                                                {{ __('Fixed') }} </option>
                                                        </select>
                                                        @error('is_percent')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="discount">{{ __('Discount') }}<span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="discount" name="discount"
                                                            type="number" value="{{ old('discount') }}" autocomplete="off"
                                                            step="0.01">
                                                        @error('discount')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="start_date">{{ __('Start Date') }}<span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control datetimepicker_mask" id="start_date"
                                                            name="start_date" type="text"
                                                            value="{{ old('start_date') }}" autocomplete="off">
                                                        @error('start_date')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="end_date">{{ __('End Date') }}</label>
                                                        <input class="form-control datetimepicker_mask" id="end_date"
                                                            name="end_date" type="text" value="{{ old('end_date') }}"
                                                            autocomplete="off">
                                                        @error('end_date')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <x-admin.form-switch name="is_never_expired"
                                                            label="{{ __('Never expired') }}?" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">{{ __('Usage') }}</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="minimum_spend">{{ __('Minimum Order Amount') }}</label>
                                                        <input class="form-control" id="minimum_spend" name="minimum_spend"
                                                            type="text" value="{{ old('minimum_spend') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="usage_limit_per_coupon">{{ __('Usage Limit') }}
                                                            ({{ __('Count') }})</label>
                                                        <input class="form-control" id="usage_limit_per_coupon"
                                                            name="usage_limit_per_coupon" type="text"
                                                            value="{{ old('usage_limit_per_coupon') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="usage_limit_per_customer">{{ __('Usage Limit Per Customer') }}
                                                            ({{ __('Count') }})</label>
                                                        <input class="form-control" id="usage_limit_per_customer"
                                                            name="usage_limit_per_customer" type="text"
                                                            value="{{ old('usage_limit_per_customer') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="usage_limit_per_customer">{{ __('Apply for') }}</label>
                                                        <select class="form-select" id="apply_for" name="apply_for">
                                                            <option value="all">
                                                                {{ __('All Order') }}
                                                            </option>
                                                            <option value="product">{{ __('Product') }}</option>
                                                            <option value="category">{{ __('Category') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card product-cart d-none">
                                        <div class="card-header">
                                            <h4 class="card-title">{{ __('Select Products') }}</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <select class="select2" id="products">
                                                        <option value="" selected disabled>
                                                            {{ __('Select Product') }}
                                                        </option>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}">
                                                                {{ $product->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card category-cart d-none">
                                        <div class="card-header">
                                            <h4 class="card-title">{{ __('Select Category') }}</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <select class="select2" id="categories" name="category_id[]"
                                                        multiple>
                                                        <option value="" disabled>
                                                            {{ __('Select Category') }}
                                                        </option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">
                                                                {{ str_repeat('--', $category->depth) }}
                                                                {{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="product-container d-none">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">{{ __('Selected Products') }}</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="row product-row">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 col-lg-12">
                                                    <div class="form-group">
                                                        <x-admin.form-switch name="status" label="{{ __('Status') }}"
                                                            :checked="old('status') == 1" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-12">
                                                    <div class="form-group">
                                                        <x-admin.form-switch name="free_shipping"
                                                            label="{{ __('Free Shipping') }}" :checked="old('free_shipping') == 1" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-12">
                                                    <div class="form-group">
                                                        <x-admin.form-switch name="can_use_with_campaign"
                                                            label="{{ __('Can be used with flash sale') }}?"
                                                            :checked="old('can_use_with_campaign') == 1" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-12">
                                                    <div class="form-group">
                                                        <x-admin.form-switch name="show_homepage"
                                                            label="{{ __('Show on homepage') }}?" :checked="old('show_homepage') == 1" />
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <x-admin.save-button :text="__('Save')">
                                                    </x-admin.save-button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <x-admin.preloader />
@endsection

@push('js')
    <script>
        'use strict';

        const products = @json($products);
        const categories = @json($categories);
        $(document).ready(function() {
            $('[name="is_never_expired"]').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#end_date').attr('disabled', true);
                } else {
                    $('#end_date').attr('disabled', false);
                }
            })

            $('#apply_for').on('change', function() {
                if ($(this).val() == 'all') {
                    $('.product-cart').addClass('d-none');
                    $('.category-cart').addClass('d-none');
                    $('.product-container').addClass('d-none');
                    $('.product-container .product-row').html('');
                    $('[name="product_id[]"]').attr('disabled', true);
                    $('[name="category_id[]"]').attr('disabled', true);
                } else if ($(this).val() == 'product') {
                    $('.product-cart').removeClass('d-none');
                    $('.category-cart').addClass('d-none');
                    $('[name="category_id[]"]').attr('disabled', true);
                    $('[name="product_id[]"]').removeAttr('disabled');

                } else if ($(this).val() == 'category') {
                    $('.product-cart').addClass('d-none');
                    $('.category-cart').removeClass('d-none');
                    $('.product-container .product-row').html('');
                    $('.product-container').addClass('d-none');
                    $('[name="product_id[]"]').attr('disabled', true);
                    $('[name="category_id[]"]').removeAttr('disabled');
                }
            })

            $(document).on('click', '.coupon_product_remove a', function() {
                $(this).closest('.col-12').remove();
                const insertedProduct = $('input[name="product_id[]"]').map(function() {
                    return $(this).val();
                }).get();
                if (insertedProduct.length == 0) {
                    $('.product-container').addClass('d-none');
                }

            })

            // for selected products
            $('#products').on('change', function() {
                var product_id = $(this).val();

                const insertedProduct = $('input[name="product_id[]"]').map(function() {
                    return $(this).val();
                }).get();

                if (insertedProduct.includes(product_id)) {
                    toastr.error('{{ __('This product is already added') }}');
                    return;
                }
                var product = products.find(item => item.id == product_id);
                var productHtml = `<div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center${insertedProduct.length > 0 ? ' mt-3' : ''}">
                                            <div class="coupon_product">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="coupon_product_image"
                                                        style="background: url('{{ asset('') }}${product.thumbnail_image}')">
                                                    </div>
                                                    <div class="coupon_product_info">
                                                        <a href="javascript:void(0)">${product.name}</a>
                                                        <input type="hidden" name="product_id[]"
                                                            value="${product.id}">
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="coupon_product_remove">
                                                    <a href="javascript:void(0)"><i class="fa fa-times"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                        </div>
                                    </div>`;

                $('.product-container .product-row').append(productHtml);

                if ($('.product-container').hasClass('d-none')) {
                    $('.product-container').removeClass('d-none');
                }
            })
        });
    </script>
@endpush
