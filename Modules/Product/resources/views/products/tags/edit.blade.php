@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Tag') }}</title>
@endsection
@php
    $languages = allLanguages();
@endphp
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit Tag') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Tag List') => route('admin.product.tags.index'),
                __('Edit Tag') => '#',
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
                                                href="{{ route('admin.product.tags.edit', ['tag' => $tag->id, 'code' => $language->code]) }}"><i
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
                <form action="{{ route('admin.product.tags.update', ['tag' => $tag->id, 'code' => $code]) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h4 class="section_title">{{ __('Edit Tag') }}</h4>
                                    <div>
                                        <a class="btn btn-primary" href="{{ route('admin.product.tags.index') }}"><i
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
                                                            value="{{ old('name', $tag->getTranslation($code)->name) }}"
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
                                                                type="text" value="{{ old('slug', $tag->slug) }}">
                                                            @error('slug')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endif
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
