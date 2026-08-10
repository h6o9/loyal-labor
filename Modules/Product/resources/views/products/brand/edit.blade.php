@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Brand') }}</title>
@endsection
@php
    $code = request()->get('code') ?? getSessionLanguage();
    $languages = allLanguages();
@endphp
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit Brand') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Brand List') => route('admin.brand.index'),
                __('Edit Brand') => '#',
            ]" />
            <div class="section-body row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header gap-3 justify-content-between align-items-center">
                            <h5 class="m-0 service_card">{{ __('Available Translations') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="lang_list_top">
                                <ul class="lang_list">
                                    @foreach ($languages as $language)
                                        <li><a id="{{ request('code') == $language->code ? 'selected-language' : '' }}"
                                                href="{{ route('admin.brand.edit', ['brand' => $brand->id, 'code' => $language->code]) }}"><i
                                                    class="fas {{ request('code') == $language->code ? 'fa-eye' : 'fa-edit' }}"></i>
                                                {{ $language->name }}</a></li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="mt-2 alert alert-danger" role="alert">
                                @php
                                    $current_language = $languages->where('code', request()->get('code'))->first();
                                @endphp
                                <p>{{ __('Your editing mode') }} :
                                    <b>{{ $current_language?->name }}</b>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="{{ route('admin.brand.update', ['brand' => $brand->id, 'code' => $code]) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h4 class="section_title">{{ __('Edit Brand') }}</h4>
                                    <div>
                                        <a class="btn btn-primary" href="{{ route('admin.brand.index') }}"><i
                                                class="fa fa-arrow-left"></i>{{ __('Back') }}</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="name">{{ __('Name') }}<span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="name" name="name"
                                                            type="text"
                                                            value="{{ old('name', $brand->getTranslation($code)->name) }}"
                                                            required>
                                                        @error('name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                @if ($code == $languages->first()->code)
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="slug">{{ __('Slug') }}<span
                                                                    class="text-danger">*</span></label>
                                                            <input class="form-control" id="slug" name="slug"
                                                                type="text" value="{{ old('slug', $brand->slug) }}">
                                                            @error('slug')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <x-admin.form-image-preview name="image"
                                                                div_id="image-preview" label_id="image-label"
                                                                input_id="image-upload"
                                                                label="{{ config('services.theme') == 4 ? __('Icon') : __('Image') }}"
                                                                required="true"
                                                                button_label="{{ config('services.theme') == 4 ? __('Upload Icon') : __('Upload Image') }}"
                                                                :image="$brand->image" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <x-admin.form-switch name="status" label="{{ __('Status') }}"
                                                                :checked="old('status', $brand->status) == 1" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <x-admin.form-switch name="is_featured"
                                                                label="{{ __('Is Featured') }}" :checked="old('is_featured', $brand->is_featured) == 1" />
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="form-group col-md-12">
                                                    <x-admin.form-input id="seo_title" name="seo_title"
                                                        value="{{ old('seo_title', $brand->getTranslation($code)->seo_title) }}"
                                                        label="{{ __('SEO Title') }}"
                                                        placeholder="{{ __('Enter SEO Title') }}" />
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <x-admin.form-textarea id="seo_description" name="seo_description"
                                                        value="{{ old('seo_description', $brand->getTranslation($code)->seo_description) }}"
                                                        label="{{ __('SEO Description') }}"
                                                        placeholder="{{ __('Enter SEO Description') }}" maxlength="2000" />
                                                </div>

                                                <div class="text-center">
                                                    <x-admin.update-button :text="__('Update')" />

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('js')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $('[name="name"]').on('input', function() {
                    var name = $(this).val();
                    var slug = convertToSlug(name);
                    $("[name='slug']").val(slug);
                });
            });
        })(jQuery);
    </script>
@endpush

@push('js')
    @if ($code != $languages->first()->code)
        <x-admin.translation :code="$code" />
    @endif
@endpush
