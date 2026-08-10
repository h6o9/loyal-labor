@extends('admin.master_layout')
@section('title')
    <title>{{ __('Add Tax') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Add Tax') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Tax List') => route('admin.tax.index'),
                __('Add Tax') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Add Tax')" />
                                <div>
                                    <x-admin.back-button :href="route('admin.tax.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.tax.store') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <x-admin.form-input id="title" name="title"
                                                    label="{{ __('Title') }}" placeholder="{{ __('Enter Title') }}"
                                                    value="{{ old('title') }}" required="true" />
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <x-admin.form-input id="slug" name="slug"
                                                    label="{{ __('Slug') }}" placeholder="{{ __('Enter Slug') }}"
                                                    value="{{ old('slug') }}" required="true" />
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <x-admin.form-input type="number" step="0.01" id="percentage"
                                                    name="percentage" label="{{ __('Percentage') }}"
                                                    placeholder="{{ __('Enter Percentage') }}"
                                                    value="{{ old('percentage') }}" required="true" />
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
