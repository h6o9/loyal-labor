@extends('admin.master_layout')
@section('title')
    <title>{{ __('Ecommerce Setup') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Ecommerce Setup') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Ecommerce Setup') => '#',
            ]" />
            <div class="section-body">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.ecom_setup.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            {{-- <tr>
                                                <td>
                                                    {{ __('Multi Vendor Service') }}
                                                </td>
                                                <td>
                                                    <select class="form-select" id="has_vendor" name="has_vendor">
                                                        <option value="1" @selected($ecomSetting->has_vendor == 1)>
                                                            {{ __('Enable') }}</option>
                                                        <option value="0" @selected($ecomSetting->has_vendor == 0)>
                                                            {{ __('Disable') }}</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{ __('Mobile App') }}
                                                </td>
                                                <td>
                                                    <select class="form-select" id="has_app" name="has_app">
                                                        <option value="1" @selected($ecomSetting->has_app == 1)>
                                                            {{ __('Have') }}</option>
                                                        <option value="0" @selected($ecomSetting->has_app == 0)>
                                                            {{ __('Does not have') }}</option>
                                                    </select>
                                                </td>
                                            </tr> --}}
                                            <tr>
                                                <td>
                                                    {{ __('Product Commission Rate') }}
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input class="form-control" id="product_commission_rate"
                                                            name="product_commission_rate" type="number"
                                                            value="{{ old('product_commission_rate', $ecomSetting->product_commission_rate) }}"
                                                            placeholder="{{ __('Product Commission Rate') }}" min="0"
                                                            max="100">
                                                        <div class="input-group-text" id="product_commission_rate_label">
                                                            %
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{ __('Guest can checkout') }}
                                                </td>
                                                <td>
                                                    <select class="form-select" id="can_guest_checkout"
                                                        name="can_guest_checkout">
                                                        <option value="1" @selected($ecomSetting->can_guest_checkout == 1)>
                                                            {{ __('Enable') }}</option>
                                                        <option value="0" @selected($ecomSetting->can_guest_checkout == 0)>
                                                            {{ __('Disable') }}</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{ __('Generated SKU Prefix') }}
                                                </td>
                                                <td>
                                                    <input class="form-control" name="sku_prefix" type="text"
                                                        value="{{ $ecomSetting->sku_prefix }}"
                                                        placeholder="{{ __('SKU Prefix') }}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{ __('Generated SKU Length') }}
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <div class="input-group-text" id="sku_length_label">
                                                            {{ $ecomSetting->sku_prefix }}
                                                        </div>
                                                        <input class="form-control" id="sku_length" name="sku_length"
                                                            type="number"
                                                            value="{{ old('sku_length', $ecomSetting->sku_length) }}"
                                                            placeholder="{{ __('SKU Length') }}" min="4"
                                                            max="15">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{ __('Invoice Prefix') }}
                                                </td>
                                                <td>
                                                    <input class="form-control" name="invoice_prefix" type="text"
                                                        value="{{ $ecomSetting->invoice_prefix }}"
                                                        placeholder="{{ __('Invoice Prefix') }}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{ __('Invoice Length') }}
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <div class="input-group-text" id="invoice_length_label">
                                                            {{ $ecomSetting->invoice_prefix }}
                                                        </div>
                                                        <input class="form-control" name="invoice_length" type="number"
                                                            value="{{ $ecomSetting->invoice_length }}" min="4"
                                                            max="15" placeholder="{{ __('Invoice Length') }}">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{ __('Order Cancel Limit In Minutes') }}
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input class="form-control" id="order_cancel_minutes_before"
                                                            name="order_cancel_minutes_before" type="number"
                                                            value="{{ old('order_cancel_minutes_before', $ecomSetting->order_cancel_minutes_before) }}"
                                                            placeholder="{{ __('Order Cancel Limit In Minutes') }}"
                                                            min="1">
                                                        <div class="input-group-text"
                                                            id="order_cancel_minutes_before_label">
                                                            {{ __('Minutes') }}
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    {{ __('Marketing Status') }} <span data-bs-toggle="tooltip"
                                                        title="{{ __('Enable Facebook Pixel and Google Tag Manager events integration.') }}">
                                                        <i class="fas fa-info-circle text-info"></i>
                                                    </span>
                                                </td>
                                                <td>
                                                    <select class="form-select" id="marketing_status"
                                                        name="marketing_status">
                                                        <option value="1" @selected($ecomSetting->marketing_status == 1)>
                                                            {{ __('Enable') }}</option>
                                                        <option value="0" @selected($ecomSetting->marketing_status == 0)>
                                                            {{ __('Disable') }}</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-12 d-flex justify-content-center">
                                        <button class="btn btn-lg btn-primary me-1 mb-1" type="submit">
                                            {{ __('Update') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
