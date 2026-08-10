@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Category') }}</title>
@endsection
@php
    $code = request()->get('code') ?? getSessionLanguage();
    $languages = allLanguages();
@endphp
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit Category') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Category List') => route('admin.category.index'),
                __('Edit Category') => '#',
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
                                                href="{{ route('admin.category.edit', ['category' => $cat->id, 'code' => $language->code]) }}"><i
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
                <form action="{{ route('admin.category.update', ['category' => $cat->id, 'code' => $code]) }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h4 class="section_title">{{ __('Edit Category') }}</h4>
                                    <div>
                                        <a class="btn btn-primary" href="{{ route('admin.category.index') }}"><i
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
                                                            value="{{ old('name', $cat->getTranslation($code)->name) }}"
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
                                                                type="text" value="{{ old('slug', $cat->slug) }}">
                                                            @error('slug')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="position">{{ __('Position') }}</label>
                                                            <input class="form-control" id="position" name="position"
                                                                type="text"
                                                                value="{{ old('position', $cat->position) }}">
                                                            @error('position')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    @if ($cat->children->isEmpty())
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label for="parent">{{ __('Parent Id') }}</label>
                                                                <select class="form-control select2" id="parent"
                                                                    name="parent_id">
                                                                    <option value="">{{ __('No Parent') }}</option>
                                                                    @foreach ($categories as $category)
                                                                        <option value="{{ $category->id }}"
                                                                            @selected($cat->parent_id == $category->id)>
                                                                            {{ str_repeat('--', $category->depth) }}
                                                                            {{ $category->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('parent')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <x-admin.form-image-preview name="image"
                                                                div_id="image-preview" label_id="image-label"
                                                                input_id="image-upload" required="true" :image="$cat->image"
                                                                label="{{ __('Image') }}" required="true"
                                                                button_label="{{ __('Upload Image') }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <x-admin.form-image-preview name="icon" div_id="icon-preview"
                                                                label_id="icon-label" input_id="icon-upload"
                                                                :image="$cat->icon" label="{{ __('Icon') }}"
                                                                required="true" button_label="{{ __('Upload Icon') }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <x-admin.form-switch name="status" label="{{ __('Status') }}"
                                                                :checked="$cat->status == 1" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <x-admin.form-switch name="is_featured"
                                                                label="{{ __('Is Featured') }}" :checked="$cat->is_featured == 1" />
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="form-group col-md-12">
                                                    <x-admin.form-input id="seo_title" name="seo_title"
                                                        value="{{ old('seo_title', $cat->getTranslation($code)->seo_title) }}"
                                                        label="{{ __('SEO Title') }}"
                                                        placeholder="{{ __('Enter SEO Title') }}" />
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <x-admin.form-textarea id="seo_description" name="seo_description"
                                                        value="{{ old('seo_description', $cat->getTranslation($code)->seo_description) }}"
                                                        label="{{ __('SEO Description') }}"
                                                        placeholder="{{ __('Enter SEO Description') }}"
                                                        maxlength="2000" />
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
