@extends('admin.master_layout')

@section('title')
    <title>{{ __('Edit Label') }}</title>
@endsection

@php
    $code = request()->get('code', getSessionLanguage());
    $languages = allLanguages();
@endphp

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Edit Label') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item active"><a
                            href="{{ route('admin.label.index') }}">{{ __('Label List') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Edit Label') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
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
                                                    href="{{ route('admin.label.edit', ['label' => $label->id, 'code' => $language->code]) }}"><i
                                                        class="fas {{ request('code') == $language->code ? 'fa-eye' : 'fa-edit' }}"></i>
                                                    {{ $language->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="mt-2 alert alert-danger" role="alert">
                                    @php
                                        $current_language = $languages
                                            ->where('code', request()->get('code', $code))
                                            ->first();
                                    @endphp
                                    <p>{{ __('Your editing mode') }} :
                                        <b>{{ $current_language?->name }}</b>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Edit Label') }}</h4>
                                <div>
                                    <a class="btn btn-primary" href="{{ route('admin.label.index') }}"><i
                                            class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form
                                    action="{{ route('admin.label.update', [
                                        'label' => $label->id,
                                        'code' => $code,
                                    ]) }}"
                                    method="post">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="name">{{ __('Name') }} <span
                                                        class="text-danger">*</span></label>
                                                <input class="form-control" id="name" name="name"
                                                    data-translate="true" type="text"
                                                    value="{{ old('name', $label->getTranslation($code)->name) }}"
                                                    required>
                                            </div>
                                        </div>
                                        @if ($code == $languages->first()->code)
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="slug">{{ __('Slug') }} <span
                                                            class="text-danger">*</span></label>
                                                    <input class="form-control" id="slug" name="slug" type="text"
                                                        value="{{ old('slug', $label->slug) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="status">{{ __('Status') }} <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="status" name="status" required>
                                                        <option value="1" @selected($label->status)>
                                                            {{ __('Active') }}
                                                        </option>
                                                        <option value="0" @selected(!$label->status)>
                                                            {{ __('Inactive') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="text-center offset-md-2 col-md-8">
                                            <x-admin.update-button :text="__('Update')">
                                            </x-admin.update-button>
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
    @if ($code != $languages->first()->code)
        <x-admin.translation :code="$code" />
    @endif

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
