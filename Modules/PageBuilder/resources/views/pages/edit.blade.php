@extends('admin.master_layout')
@section('title')
    <title>{{ __('Update Page') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Update Page') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Custom Page List') => route('admin.custom-pages.index'),
                __('Update Page') => '#',
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
                                    @foreach (allLanguages() as $language)
                                        <li>
                                            <a id="{{ request('code') == $language->code ? 'selected-language' : '' }}"
                                                href="{{ route('admin.custom-pages.edit', ['page' => $page->id, 'code' => $language->code]) }}">
                                                <i
                                                    class="fas {{ request('code') == $language->code ? 'fa-eye' : 'fa-edit' }}"></i>
                                                {{ $language->name }}
                                            </a>
                                        </li>
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
                                <x-admin.form-title :text="__('Update Page')" />
                                <div>
                                    <x-admin.back-button :href="route('admin.custom-pages.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form
                                    action="{{ route('admin.custom-pages.update', ['page' => $page->id, 'code' => $code]) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <x-admin.form-input id="title" name="title"
                                                value="{{ old('title', $page->getTranslation($code)->title) }}"
                                                label="{{ __('Title') }}" placeholder="{{ __('Enter Title') }}"
                                                required="true" />
                                        </div>

                                        <div class="form-group col-md-12">
                                            <x-admin.form-editor id="description" name="description" data-translate="true"
                                                value="{!! old('description', $page->getTranslation($code)->description) !!}" label="{{ __('Description') }}"
                                                required="true" />
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <x-admin.update-button :text="__('Update')" />
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
        'use strict';
        $('#translate-btn').on('click', function() {
            translateAllTo("{{ $code }}");
        })
    </script>
@endpush
