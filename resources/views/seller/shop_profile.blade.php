@extends('seller.layouts.master')

@section('title')
    <title>{{ __('My Shop') }}</title>
@endsection

@section('seller-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Shop Profile') }}" :list="[
                'Dashboard' => route('seller.dashboard'),
                'Shop Profile' => '#',
            ]" />
            <div class="section-body">
                <div class="row mt-sm-4">
                    <div class="col-12">
                        <div class="card profile-widget">
                            <div class="profile-widget-description">
                                <form action="{{ route('seller.update-seller-shop') }}" enctype="multipart/form-data"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>{{ __('Current Banner Image') }}</label>
                                            <div>
                                                <img id="preview-banner-img" src="{{ asset($seller->banner_image) }}"
                                                    alt="" width="300px">
                                            </div>
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('New Banner Image') }}</label>
                                            <input class="form-control" name="banner_image" type="file">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Shop Name') }} <span class="text-danger">*</span></label>
                                            <input class="form-control" name="shop_name" type="text"
                                                value="{{ $seller->shop_name }}" readonly>
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Email') }} <span class="text-danger">*</span></label>
                                            <input class="form-control" name="email" type="email"
                                                value="{{ $seller->email }}" readonly>
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Phone') }} <span class="text-danger">*</span></label>
                                            <input class="form-control" name="phone" type="text"
                                                value="{{ $seller->phone }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Current Logo') }}</label>
                                            <div>
                                                <img id="preview-logo-img" src="{{ asset($seller->logo_image) }}"
                                                    alt="" width="300px">
                                            </div>
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('New Logo Image') }}</label>
                                            <input class="form-control" name="logo_image" type="file">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Opens at') }} <span class="text-danger">*</span></label>
                                            <input class="form-control clockpicker" name="opens_at" data-align="top"
                                                data-autoclose="true" type="time" value="{{ $seller->open_at }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Closed at') }} <span class="text-danger">*</span></label>
                                            <input class="form-control clockpicker" name="closed_at" data-align="top"
                                                data-autoclose="true" type="time" value="{{ $seller->closed_at }}">

                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Address') }} <span class="text-danger">*</span></label>
                                            <input class="form-control" name="address" type="text"
                                                value="{{ $seller->address }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Seo Title') }}</label>
                                            <input class="form-control" name="seo_title" type="text"
                                                value="{{ $seller->seo_title }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Seo Description') }}</label>
                                            <textarea class="form-control text-area-5" id="" name="seo_description" cols="30" rows="10">{{ $seller->seo_description }}</textarea>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary">{{ __('Save Changes') }}</button>
                                        </div>
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

@push('js')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {

                // name="banner_image" on change preive to preview-banner-img
                $(document).on('change', '[name="banner_image"]', function() {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        $('#preview-banner-img').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                });

                $(document).on('change', '[name="logo_image"]', function() {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        $('#preview-logo-img').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                });
            });
        })(jQuery);
    </script>
@endpush
