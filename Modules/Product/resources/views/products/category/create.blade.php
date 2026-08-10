@extends('admin.master_layout')
@section('title')
    <title>{{ __('Create Category') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Create Category') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Category List') => route('admin.category.index'),
                __('Create Category') => '#',
            ]" />
            <div class="section-body">
                <form action="{{ route('admin.category.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h4 class="section_title">{{ __('Create Category') }}</h4>
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
                                                            type="text" value="{{ old('name') }}" required>
                                                        @error('name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="slug">{{ __('Slug') }}<span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="slug" name="slug"
                                                            type="text" value="{{ old('slug') }}">
                                                        @error('slug')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="position">{{ __('Position') }}</label>
                                                        <input class="form-control" id="position" name="position"
                                                            type="text" value="{{ old('position') }}">
                                                        @error('position')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="parent">{{ __('Parent Id') }}</label>
                                                        <select class="form-control select2" id="parent"
                                                            name="parent_id">
                                                            <option value="">{{ __('Select One') }}</option>
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}">
                                                                    {{ str_repeat('--', $category->depth) }}
                                                                    {{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('parent')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <x-admin.form-image-preview name="image" div_id="image-preview"
                                                            label_id="image-label" input_id="image-upload"
                                                            label="{{ config('services.theme') == 4 ? __('Image') : __('Image') }}"
                                                            required="true"
                                                            button_label="{{ config('services.theme') == 4 ? __('Upload Image') : __('Upload Image') }}" />
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <x-admin.form-image-preview name="icon" div_id="icon-preview"
                                                            label_id="icon-label" input_id="icon-upload"
                                                            label="{{ __('Icon') }}" required="true"
                                                            button_label="{{ __('Upload Icon') }}" />
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <x-admin.form-switch name="status" label="{{ __('Status') }}"
                                                            :checked="old('status') == 1" />
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <x-admin.form-switch name="is_featured"
                                                            label="{{ __('Is Featured') }}" :checked="old('is_featured') == 1" />
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <x-admin.form-input id="seo_title" name="seo_title"
                                                        value="{{ old('seo_title') }}" label="{{ __('SEO Title') }}"
                                                        placeholder="{{ __('Enter SEO Title') }}" />
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <x-admin.form-textarea id="seo_description" name="seo_description"
                                                        value="{{ old('seo_description') }}"
                                                        label="{{ __('SEO Description') }}"
                                                        placeholder="{{ __('Enter SEO Description') }}" maxlength="2000" />
                                                </div>

                                                <div class="text-center">
                                                    <x-admin.save-button :text="__('Save')">
                                                    </x-admin.save-button>
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
