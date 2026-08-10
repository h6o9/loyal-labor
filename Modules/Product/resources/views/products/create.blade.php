@extends('admin.master_layout')
@section('title')
    <title>{{ __('Create Product') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Product List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Product List') => route('admin.product.index'),
                __('Create Product') => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4 class="section_title">{{ __('Create Product') }}</h4>
                                <div>
                                    <a class="btn btn-primary" href="{{ route('admin.product.index') }}"><i
                                            class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <form class="create_product_table" action="{{ route('admin.product.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="name">{{ __('Name') }}<span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="name" name="name"
                                                            type="text" value="{{ old('name') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="slug">{{ __('Slug') }}<span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="slug" name="slug"
                                                            type="text" value="{{ old('slug') }}">

                                                        <span class="text-muted me-2" id="slug_preview"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="sku">{{ __('BARCODE') }}<span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <input class="form-control" id="barcode" name="barcode"
                                                                type="text" value="{{ old('barcode', $barcode) }}" />
                                                            <span
                                                                class="input-group-text mb-0 generate_barcode cursor-pointer"
                                                                id="barcode2"><i
                                                                    class="fas fa-barcode"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="sku">{{ __('SKU') }}<span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <input class="form-control" id="sku" name="sku"
                                                                type="text" value="{{ old('sku', $sku) }}" />
                                                            <span class="input-group-text mb-0 generate_sku cursor-pointer"
                                                                id="sku2"><i
                                                                    class="fas fa-barcode"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="unit_type_id">{{ __('Unit') }}</label>
                                                        <select class="form-select select2" id="unit_type_id"
                                                            name="unit_type_id">
                                                            <option value="">{{ __('Select unit') }}</option>
                                                            @foreach ($units as $unit)
                                                                <option value="{{ $unit->id }}">
                                                                    {{ $unit->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="brand_id">{{ __('Brand') }}</label>
                                                        <select class="form-select select2" id="brand_id" name="brand_id">
                                                            <option value="">{{ __('Select Brand') }}</option>
                                                            @foreach ($brands as $brand)
                                                                <option value="{{ $brand->id }}">
                                                                    {{ $brand->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="tags">{{ __('Tags') }}<span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <input class="form-control tag" id="tags" name="tags"
                                                                type="text" value="{{ old('tags') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="short_description">{{ __('Short Description') }}<span
                                                                class="text-danger">*</span></label>
                                                        <textarea class="form-control text-area-5" id="" name="short_description" rows="9">{{ old('short_description') }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="description">{{ __('Description') }}<span
                                                                class="text-danger">*</span></label>
                                                        <textarea class="summernote" id="" name="description" rows="9">{{ old('description') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="price">{{ __('Price') }}<span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">{{ currency_icon() }}</span>
                                                            <input class="form-control" id="price" name="price"
                                                                type="number" value="{{ old('price') }}"
                                                                step="00.01">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="d-flex justify-content-between form-label"
                                                            for="price">
                                                            <span>
                                                                {{ __('Offer Price') }}
                                                            </span>
                                                            <a class="discount-period" href="javascript:;">
                                                                {{ __('Choose Discount Period') }}
                                                            </a>
                                                        </label>

                                                        <div class="input-group">
                                                            <div class="input-group-text" id="offer_price_type_icon">
                                                                {{ currency_icon() }}
                                                            </div>

                                                            <input class="form-control" id="offer_price"
                                                                name="offer_price" type="number"
                                                                value="{{ old('offer_price') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="offer_price_type">{{ __('Offer Price Type') }}</label>
                                                        <select class="form-select" id="offer_price_type"
                                                            name="offer_price_type">
                                                            <option value="">{{ __('Select Offer Price Type') }}
                                                            </option>
                                                            <option value="fixed" @selected(old('offer_price_type') == 'fixed' || old('offer_price_type') == '')>
                                                                {{ __('Fixed') }}
                                                            </option>
                                                            <option value="percentage" @selected(old('offer_price_type') == 'percentage')>
                                                                {{ __('Percentage') }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 offer_price_date d-none">
                                                    <div class="form-group">
                                                        <label for="offer_price_start">
                                                            {{ __('From Date') }}
                                                        </label>
                                                        <input class="form-control datepicker" id="offer_price_start"
                                                            name="offer_price_start" type="text"
                                                            value="{{ old('offer_price_start') }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 offer_price_date d-none">
                                                    <div class="form-group">
                                                        <label for="offer_price_end">
                                                            {{ __('End Date') }}
                                                        </label>
                                                        <input class="form-control datepicker" id="offer_price_end"
                                                            name="offer_price_end" type="text"
                                                            value="{{ old('offer_price_end') }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check mb-4">
                                                        <input class="form-check-input" id="manage_stock"
                                                            name="manage_stock" type="checkbox" value="1">
                                                        <label class="form-check-label"
                                                            for="manage_stock">{{ __('Manage Stock') }}</label>
                                                    </div>
                                                    <div class="form-group stock_area d-none shipping_area">
                                                        <label for="offer_price_end">
                                                            {{ __('Stock status') }}
                                                        </label>
                                                        <div class="d-flex justify-content-start align-items-center gap-5">
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input class="form-check-input" id="in_stock"
                                                                    name="stock_status" type="radio" value="in_stock">
                                                                <label class="form-check-label"
                                                                    for="in_stock">{{ __('In Stock') }}</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input class="form-check-input" id="out_of_stock"
                                                                    name="stock_status" type="radio"
                                                                    value="out_of_stock">
                                                                <label class="form-check-label"
                                                                    for="out_of_stock">{{ __('Out of Stock') }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="stock_status d-none mt-3">
                                                        <div class="form-group">
                                                            <label for="stock_qty">
                                                                {{ __('Stock Quantity') }}
                                                            </label>
                                                            <input class="form-control" id="stock_qty" name="stock_qty"
                                                                type="number" value="{{ old('stock_qty') }}"
                                                                step="1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">{{ __('Shipping') }}</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label class="form-label" for="weight">{{ __('Weight') }}
                                                        (g)</label>
                                                    <input class="form-control" id="weight" name="weight"
                                                        type="number" value="0">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="length">{{ __('Length') }}
                                                        (cm)</label>
                                                    <input class="form-control" id="length" name="length"
                                                        type="number" value="0">
                                                </div>
                                                <div class="col-6 mt-3">
                                                    <label class="form-label" for="wide">{{ __('Wide') }}
                                                        (cm)</label>
                                                    <input class="form-control" id="wide" name="wide"
                                                        type="number" value="0">
                                                </div>
                                                <div class="col-6 mt-3">
                                                    <label class="form-label" for="wide">{{ __('Height') }}
                                                        (cm)</label>
                                                    <input class="form-control" id="wide" name="wide"
                                                        type="number" value="0">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    {{-- <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">{{ __('Product Return Policy') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label
                                                            for="return_policy_id">{{ __('Select Return Policies') }}</label>
                                                        <select class="form-select select2" id="return_policy_id"
                                                            name="return_policy_id"
                                                            placeholder="{{ __('Select Return Policy') }}">
                                                            <option value="">
                                                                {{ __('Select Return Policy') }}
                                                            </option>
                                                            @foreach ($productReturnPolicies as $returnPolicy)
                                                                <option value="{{ $returnPolicy->id }}"
                                                                    @selected(old('return_policy_id') == $returnPolicy->id)>
                                                                    {{ $returnPolicy->question }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <h5>{{ __('Or Create New') }}</h5>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <lable class="form-label" for="return_policy_question">
                                                            {{ __('Return Policy Question') }}<span
                                                                class="text-success">*</span></lable>
                                                        <input class="form-control" id="return_policy_question"
                                                            name="return_policy_question" type="text"
                                                            placeholder="{{ __('Return Policy Question') }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <lable class="form-label" for="return_policy_answer">
                                                            {{ __('Return Policy Answer') }}</lable>
                                                        <textarea class="form-control text-area-5" id="return_policy_answer" name="return_policy_answer" rows="4"
                                                            placeholder="{{ __('Return Policy Answer') }}"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">{{ __('SEO Settings') }}</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="seo_title">{{ __('SEO Title') }}</label>
                                                        <input class="form-control" id="seo_title" name="seo_title"
                                                            type="text">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="seo_title">{{ __('SEO Description') }}</label>
                                                        <textarea class="form-control text-area-5" id="seo_description" name="seo_description"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <x-admin.save-button :text="__('Save')">
                                        </x-admin.save-button>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 mb-5">
                                                    <x-admin.form-image-preview name="thumbnail_image"
                                                        div_id="image-preview" label_id="image-label"
                                                        input_id="image-upload" label="{{ __('Image') }}"
                                                        required="true" />
                                                </div>
                                                <div class="col-md-6 col-lg-12">
                                                    <div class="form-group">
                                                        <label for="status">{{ __('Status') }}<span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control" id="status" name="status">
                                                            <option value="1">
                                                                {{ __('Published') }}</option>
                                                            <option value="0">
                                                                {{ __('Hidden') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-12">
                                                    <div class="form-group">
                                                        <label
                                                            for="allow_checkout_when_out_of_stock">{{ __('Allow Checkout Without Stock') }}<span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control" id="allow_checkout_when_out_of_stock"
                                                            name="allow_checkout_when_out_of_stock">
                                                            <option value="0">
                                                                {{ __('No') }}</option>
                                                            <option value="1">
                                                                {{ __('Yes') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-12">
                                                    <div class="form-group">
                                                        <label
                                                            for="is_cash_delivery">{{ __('Allow Cash on Delivery') }}<span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control" id="is_cash_delivery"
                                                            name="is_cash_delivery">
                                                            <option value="1">
                                                                {{ __('Yes') }}</option>
                                                            <option value="0">
                                                                {{ __('No') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <input name="is_return" type="hidden" value="0">
                                                {{-- <div class="col-md-6 col-lg-12">
                                                    <div class="form-group">
                                                        <label
                                                            for="is_return">{{ __('Allow Return') }}<span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control" id="is_return" name="is_return">
                                                            <option value="1">
                                                                {{ __('Yes') }}</option>
                                                            <option value="0">
                                                                {{ __('No') }}</option>
                                                        </select>
                                                    </div>
                                                </div> --}}
                                                @if (getSettingStatus('has_vendor', 'int'))
                                                    <div class="col-md-6 col-lg-12">
                                                        <div class="form-group">
                                                            <label for="vendor_id">{{ __('Vendor') }}<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <select class="form-control select2" id="vendor_id"
                                                                    name="vendor_id">
                                                                    <option value="">{{ __('Select Vendor') }}
                                                                    </option>
                                                                    @foreach ($vendors as $vendor)
                                                                        <option value="{{ $vendor->id }}">
                                                                            {{ $vendor->shop_name }}
                                                                            ({{ $vendor->user->name }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-md-6 col-lg-12">
                                                    <div class="form-group">
                                                        <label for="tax_id">{{ __('Tax') }}</label>
                                                        <div class="form-check">
                                                            @foreach ($taxes as $tax)
                                                                <div class="form-check">
                                                                    <input class="form-check-input"
                                                                        id="tax-{{ $tax->id }}" name="tax_ids[]"
                                                                        type="checkbox" value="{{ $tax->id }}">
                                                                    <label class="form-check-label"
                                                                        for="tax-{{ $tax->id }}">
                                                                        {{ $tax->title }} ({{ $tax->percentage }}%)
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">
                                                <label class="form-label"
                                                    for="category_id[]">{{ __('Categories') }}</label>
                                            </h4>
                                        </div>

                                        <div class="card-body">
                                            <div class="mb-3">
                                                <div class="input-group">
                                                    <input class="form-control" id="search-category-input" type="text"
                                                        placeholder="{{ __('Search') }}..."
                                                        onkeyup="filterCategories()">
                                                    <span class="input-group-text mb-0 generate_sku cursor-pointer"
                                                        id="sku2"><svg class="icon svg-icon-ti-ti-search"
                                                            xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24V0H0z" fill="none"></path>
                                                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                            <path d="M21 21l-6 -6"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="categories-list">
                                                <ul class="list-unstyled">
                                                    @foreach ($categories as $category)
                                                        <li>
                                                            <label class="form-check" for="parent-{{ $category->id }}">
                                                                <input class="form-check-input"
                                                                    id="parent-{{ $category->id }}" name="category_id[]"
                                                                    type="checkbox" value="{{ $category->id }}">
                                                                <span
                                                                    class="form-check-label">{{ $category->name }}</span>
                                                            </label>

                                                            @if ($category->children->isNotEmpty())
                                                                <ul class="list-unstyled ms-4 mt-2">
                                                                    @foreach ($category->children as $child)
                                                                        <li class="ms-2">
                                                                            <label class="form-check"
                                                                                for="child-{{ $child->id }}">
                                                                                <input class="form-check-input"
                                                                                    id="child-{{ $child->id }}"
                                                                                    name="category_id[]"
                                                                                    data-parent-id="{{ $category->id }}"
                                                                                    data-parent-input-id="parent-{{ $category->id }}"
                                                                                    type="checkbox"
                                                                                    value="{{ $child->id }}">
                                                                                <span
                                                                                    class="form-check-label">{{ $child->name }}</span>
                                                                            </label>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">{{ __('Product collections') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" id="is_best_selling"
                                                                name="is_best_selling" type="checkbox" value="1">
                                                            <label class="form-check-label"
                                                                for="is_best_selling">{{ __('Best Seller') }}</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" id="is_popular"
                                                                name="is_popular" type="checkbox" value="1">
                                                            <label class="form-check-label"
                                                                for="is_popular">{{ __('Popular') }}</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" id="is_featured"
                                                                name="is_featured" type="checkbox" value="1">
                                                            <label class="form-check-label"
                                                                for="is_featured">{{ __('Featured Product') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">{{ __('Labels') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        @foreach ($labels as $label)
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="{{ $label->slug }}"
                                                                    name="labels[]" type="checkbox"
                                                                    value="{{ $label->id }}" multiple>
                                                                <label class="form-check-label"
                                                                    for="{{ $label->slug }}">{{ $label->name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between">
                                            <h5 class="card-title">{{ __('Flash Sale') }}</h5>
                                            <div class="form-group">
                                                <input name="is_flash_deal" type="hidden" value="0">
                                                <input id="is_flash_deal" name="is_flash_deal" data-toggle="toggle"
                                                    data-onlabel="{{ __('Yes') }}"
                                                    data-offlabel="{{ __('NO') }}" data-onstyle="success"
                                                    data-offstyle="secondary" type="checkbox" value="1"
                                                    @checked(old('is_flash_deal') == 1)>
                                            </div>
                                        </div>
                                        <div class="card-body {{ old('is_flash_deal', 0) == 0 ? 'd-none' : '' }}"
                                            id="flash_deal_area">
                                            <div class="row">
                                                <div
                                                    class="col-md-12 flash_deal_image">
                                                    <div class="form-group">
                                                        <label for="flash_deal_image">
                                                            {{ __('Flash Sale Image') }}
                                                        </label>
                                                        <input class="form-control" id="flash_deal_image"
                                                            name="flash_deal_image" type="file" accept="image/*">
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-md-6 flash_deal_start">
                                                    <div class="form-group">
                                                        <label for="flash_deal_start">
                                                            {{ __('Start Date') }}<span class="text-success"
                                                                title="{{ __('This is required if the flash sale is active') }}">*
                                                            </span>
                                                        </label>
                                                        <input class="form-control datepicker" id="flash_deal_start"
                                                            name="flash_deal_start" type="text"
                                                            value="{{ old('flash_deal_start') }}">
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-md-6 flash_deal_end">
                                                    <div class="form-group">
                                                        <label for="flash_deal_end">
                                                            {{ __('End Date') }}<span class="text-success"
                                                                title="{{ __('This is required if the flash sale is active') }}">*
                                                            </span>
                                                        </label>
                                                        <input class="form-control datepicker" id="flash_deal_end"
                                                            name="flash_deal_end" type="text"
                                                            value="{{ old('flash_deal_end') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="flash_deal_price">
                                                            {{ __('Flash Sale Price') }}
                                                            <span class="text-success"
                                                                title="{{ __('This is required if the flash sale is active') }}">*
                                                            </span>
                                                        </label>

                                                        <div class="input-group">
                                                            <div class="input-group-text">
                                                                {{ currency_icon() }}
                                                            </div>

                                                            <input class="form-control" id="flash_deal_price"
                                                                name="flash_deal_price" type="number"
                                                                value="{{ old('flash_deal_price') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="flash_deal_qty">
                                                            {{ __('Flash Deal Stock Quantity') }}<span
                                                                class="text-success"
                                                                title="{{ __('This is required if the flash sale is active') }}">*
                                                            </span>
                                                        </label>
                                                        <input class="form-control" id="flash_deal_qty"
                                                            name="flash_deal_qty" type="number"
                                                            value="{{ max(1, old('flash_deal_qty')) }}" min="1"
                                                            step="1">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <small
                                                        class="text-warning">{{ __('Flash deal products will not have any varient.') }}</small>
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
@endsection
@push('css')
    <link href="{{ asset('backend/css/dropzone.min.css') }}" rel="stylesheet">
@endpush
@push('js')
    <script src="{{ asset('backend/js/dropzone.min.js') }}"></script>
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $('[name="name"]').on('input', function() {
                    var name = $(this).val();
                    var slug = convertToSlug(name);
                    $("[name='slug']").val(slug);

                    let url = '{{ route('website.product', ':slug') }}';
                    url = url.replace(':slug', slug);
                    var text = "{{ __('Product Link will be') }}: " + url;
                    $('#slug_preview').html(text).css('margin-top', '2px');
                });

                $('.generate_barcode').on('click', function() {
                    var sku = "{{ generateUniqueBarcode() }}";
                    $("[name='barcode']").val(sku);
                });

                $('.generate_sku').on('click', function() {
                    var sku = "{{ generateSku() }}";
                    $("[name='sku']").val(sku);
                });

                $.uploadPreview({
                    input_field: "#image-upload",
                    preview_box: "#image-preview",
                    label_field: "#image-label",
                    label_default: "Choose File",
                    label_selected: "Change File",
                    no_label: false
                });

                // discount period

                $('.discount-period').on('click', function() {
                    const hasClass = $('.offer_price_date').hasClass('d-none')
                    if (hasClass) {
                        $('.offer_price_date').removeClass('d-none')
                        $(this).html('{{ __('Cancel') }}')

                        // remove disabled attr
                        $(`[name="offer_price_start"]`).removeAttr('disabled');
                        $(`[name="offer_price_end"]`).removeAttr('disabled');
                    } else {
                        $('.offer_price_date').addClass('d-none')
                        $(this).html('{{ __('Choose Discount Period') }}')

                        // add disabled attr
                        $(`[name="offer_price_start"]`).attr('disabled', true);
                        $(`[name="offer_price_end"]`).attr('disabled', true);
                    }
                })

                // mange stock

                $('#manage_stock').on('change', function() {
                    const hasClass = $('.stock_area').hasClass('d-none')
                    if (hasClass) {
                        $('.stock_area').removeClass('d-none')

                    } else {
                        $('.stock_area').addClass('d-none')
                        $('.stock_status').addClass('d-none')

                        // removed checked
                        $('[name="stock_status"]').prop('checked', false);
                    }
                });

                $('#return_policy_id').on('select2:unselect select2:select change', function() {
                    const selectedValue = $(this).val();
                    if (selectedValue && selectedValue.length > 0) {
                        $('#return_policy_question').attr('disabled', true);
                        $('#return_policy_answer').attr('disabled', true);
                    } else {
                        $('#return_policy_question').removeAttr('disabled');
                        $('#return_policy_answer').removeAttr('disabled');
                    }
                });

                // stock status
                $('[name="stock_status"]').on('change', function() {
                    const stockStatus = $(this).val();
                    if (stockStatus == 'in_stock') {
                        $('.stock_status').removeClass('d-none')
                    } else {
                        $('.stock_status').addClass('d-none')
                    }

                });

                // offer price type
                $('#offer_price_type').on('change', function() {
                    const offerPriceType = $(this).val();
                    if (offerPriceType == 'percentage') {
                        $('#offer_price_type_icon').html('%');
                    } else {
                        $('#offer_price_type_icon').html('{{ currency_icon() }}');
                    }
                });
            });

            function changeAttr(val, selectorName) {
                if (val == 1) {
                    $(`[name="${selectorName}"]`).attr('required', true);
                    $(`.${selectorName}`).removeClass('d-none')
                    $(`[name="${selectorName}"]`).removeAttr('disabled');
                } else {
                    $(`[name="${selectorName}"]`).removeAttr('required');
                    $(`[name="${selectorName}"]`).attr('disabled');
                    $(`.${selectorName}`).addClass('d-none')
                }
            }

        })(jQuery);
    </script>
    <script>
        const tags = @json($tags ?? []);
        var input = document.querySelector('.tag');

        new Tagify(input, {
            whitelist: tags.map(tag => ({
                value: tag.name
            })),
            dropdown: {
                enabled: 1,
                classname: 'tagify-example',
                RTL: '{{ session('text_direction') }}' == 'rtl',
                escapeHTML: false
            }
        });
    </script>

    <script>
        function filterCategories() {
            const searchInput = document.getElementById('search-category-input').value.toLowerCase();
            const categories = document.querySelectorAll('.categories-list label');

            categories.forEach(category => {
                const text = category.textContent.toLowerCase();
                category.style.display = text.includes(searchInput) ? '' : 'none';
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            $('.categories-list .form-check-input').on('change', function() {
                if ($(this).is(':checked')) {
                    let parentId = $(this).data('parent-input-id');
                    if (parentId) {
                        $('#' + parentId).prop('checked', true);
                    }
                }
            });

            $('.categories-list .form-check-input').on('click', function(e) {
                let parentId = $(this).attr('id');
                if (parentId && $(this).is(':not(:checked)')) {
                    let childCheckboxes = $(
                        `.categories-list .form-check-input[data-parent-input-id="${parentId}"]`);
                    let anyChildChecked = childCheckboxes.is(':checked');

                    if (anyChildChecked) {
                        childCheckboxes.prop('checked', false);
                    }
                }
            });

            $('#is_flash_deal').on('change', function() {
                toggleFlashDealArea(false);
            });

            function toggleFlashDealArea(onLoad = false) {
                const $area = $('#flash_deal_area');

                if ($('#is_flash_deal').is(':checked')) {
                    if (onLoad) {
                        $area.removeClass('d-none').hide().slideDown(300);
                    } else {
                        $area.stop(true, true).removeClass('d-none').slideDown(300);
                    }
                } else {
                    $area.stop(true, true).slideUp(300, function() {
                        $area.addClass('d-none');
                    });
                }
            }
        });
    </script>
@endpush
