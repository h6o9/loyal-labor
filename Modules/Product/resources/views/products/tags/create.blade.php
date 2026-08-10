@extends('admin.master_layout')
@section('title')
    <title>{{ __('Create Tag') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Create Tag') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Tag List') => route('admin.product.tags.index'),
                __('Create Tag') => '#',
            ]" />
            <div class="section-body">
                <form action="{{ route('admin.product.tags.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h4 class="section_title">{{ __('Create Tag') }}</h4>
                                    <div>
                                        <a href="{{ route('admin.product.tags.index') }}" class="btn btn-primary"><i
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
                                                        <input type="text" name="name" class="form-control"
                                                            id="name" required value="{{ old('name') }}">
                                                        @error('name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="slug">{{ __('Slug') }}<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="slug" class="form-control"
                                                            id="slug" value="{{ old('slug') }}">
                                                        @error('slug')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
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
