@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Attribute') }}</title>
@endsection
@php
    $code = request()->get('code') ?? getSessionLanguage();
    $languages = allLanguages();
@endphp
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Edit Attribute') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item active"><a
                            href="{{ route('admin.attribute.index') }}">{{ __('Attribute List') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Edit Attribute') }}</div>
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
                                                    href="{{ route('admin.attribute.edit', ['attribute' => $attribute->id, 'code' => $language->code]) }}"><i
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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Edit Attribute') }}</h4>
                                <div>
                                    <a class="btn btn-primary" href="{{ route('admin.attribute.index') }}"><i
                                            class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form
                                    action="{{ route('admin.attribute.update', $attribute->id) }}?code={{ $code }}"
                                    method="post">
                                    @csrf
                                    @method('PUT')
                                    <input name="code" type="hidden" value="{{ $code }}">
                                    <div class="row">
                                        <div class="col-md-8 offset-md-2">
                                            <div class="form-group">
                                                <label for="name">{{ __('Name') }}<span
                                                        class="text-danger">*</span></label>
                                                <input class="form-control" id="name" name="name"
                                                    data-translate="true" type="text"
                                                    value="{{ old('name', $attribute->getTranslation($code)->name) }}"
                                                    required>
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        @if ($code == $languages->first()->code)
                                            <div class="col-md-8 offset-md-2">
                                                <div class="form-group">
                                                    <label for="slug">{{ __('Slug') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input class="form-control" id="slug" name="slug" type="text"
                                                        value="{{ old('slug', $attribute->slug) }}">
                                                    @error('slug')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
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
