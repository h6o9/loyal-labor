@extends('admin.master_layout')
@section('title')
    <title>{{ __('Category List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Edit Category') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Category List') => route('admin.blog-category.index'),
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
                                                href="{{ route('admin.blog-category.edit', ['blog_category' => $category->id, 'code' => $language->code]) }}"><i
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
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Edit Category')" />
                                <div>
                                    <x-admin.back-button :href="route('admin.blog-category.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form
                                    action="{{ route('admin.blog-category.update', ['blog_category' => $category->id, 'code' => $code]) }}"
                                    method="post">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <x-admin.form-input id="title" name="title" data-translate="true"
                                            value="{{ old('title', $category->getTranslation($code)->title) }}"
                                            label="{{ __('Title') }}" placeholder="{{ __('Enter Title') }}"
                                            placeholder="{{ __('Enter Title') }}" required="true" />
                                    </div>
                                    <div class="form-group">
                                        <x-admin.form-textarea id="short_description" name="short_description"
                                            data-translate="true"
                                            value="{{ old('short_description', $category->getTranslation($code)->short_description) }}"
                                            label="{{ __('Sort Description') }}"
                                            placeholder="{{ __('Enter Sort Description') }}" maxlength="255" />
                                    </div>
                                    <x-admin.update-button :text="__('Update')" />
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
        'use strict';

        $('#translate-btn').on('click', function() {
            translateAllTo("{{ $code }}");
        })
    </script>
@endpush
