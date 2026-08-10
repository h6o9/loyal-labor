@extends('admin.master_layout')
@section('title')
    <title>{{ __('Category List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Add Category') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Category List') => route('admin.blog-category.index'),
                __('Add Category') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Add Category')" />
                                <div>
                                    <x-admin.back-button :href="route('admin.blog-category.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.blog-category.store') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <x-admin.form-input id="title" name="title"
                                                    label="{{ __('Title') }}" placeholder="{{ __('Enter Title') }}"
                                                    value="{{ old('title') }}" required="true" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <x-admin.form-input id="slug" name="slug"
                                                    label="{{ __('Slug') }}" placeholder="{{ __('Enter Slug') }}"
                                                    value="{{ old('slug') }}" required="true" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-textarea id="short_description" name="short_description"
                                                    label="{{ __('Sort Description') }}"
                                                    placeholder="{{ __('Enter Sort Description') }}"
                                                    value="{{ old('short_description') }}" maxlength="255" />
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <x-admin.save-button :text="__('Save')" />
                                        </div>
                                    </div>
                                </form>
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
        (function($) {
            "use strict";
            $(document).ready(function() {
                $("#title").on("keyup", function(e) {
                    $("#slug").val(convertToSlug($(this).val()));
                })
            });
        })(jQuery);

        function convertToSlug(Text) {
            return Text
                .toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
        }
    </script>
@endpush
