@extends('admin.master_layout')
@section('title')
    <title>{{ __('Create Page') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Create Page') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Custom Page List') => route('admin.custom-pages.index'),
                __('Create Page') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Create Page')" />
                                <div>
                                    <x-admin.back-button :href="route('admin.custom-pages.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.custom-pages.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <x-admin.form-input id="title" name="title" value="{{ old('title') }}"
                                                label="{{ __('Title') }}" placeholder="{{ __('Enter Title') }}"
                                                required="true" />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-input id="slug" name="slug" value="{{ old('slug') }}"
                                                label="{{ __('Slug') }}" placeholder="{{ __('Enter Slug') }}"
                                                required="true" />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-editor id="description" name="description"
                                                value="{!! old('description') !!}" label="{{ __('Description') }}"
                                                required="true" />
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
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
