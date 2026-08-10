@extends('admin.master_layout')
@section('title')
    <title>{{ __('Frontend Settings') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Frontend Settings') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Frontend Settings') => '#',
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
                                    @foreach ($languages = allLanguages() as $language)
                                        <li><a id="{{ request('code') == $language->code ? 'selected-language' : '' }}"
                                                href="{{ route('admin.frontend.index', ['code' => $language->code]) }}"><i
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
                                <p>{{ __('Your editing mode') }}:<b> {{ $current_language?->name }}</b></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-body">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-3">
                                    <ul class="nav nav-pills flex-column" id="seo_tab" role="tablist">
                                        <li class="nav-item border rounded mb-1">
                                            <a class="nav-link"
                                                href="{{ route('admin.frontend.homepage') }}">{{ __('Change Theme') }}</a>
                                        </li>
                                        @foreach ($sections as $index => $page)
                                            <li class="nav-item border rounded mb-1">
                                                <a class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                                    id="error-tab-{{ $page->id }}" data-bs-toggle="tab"
                                                    href="#errorTab-{{ $page->id }}" role="tab"
                                                    aria-controls="errorTab-{{ $page->id }}"
                                                    aria-selected="true">{{ str($page->name)->replace('_', ' ')->title() }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-12 col-sm-12 col-md-9">
                                    <div class="border rounded">
                                        <div class="tab-content no-padding" id="settingsContent">
                                            @foreach ($sections as $index => $section)
                                                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                                                    id="errorTab-{{ $section->id }}" role="tabpanel"
                                                    aria-labelledby="error-tab-{{ $section->id }}">
                                                    <div class="card m-0">
                                                        <div class="card-body">
                                                            <form
                                                                action="{{ route('admin.frontend.update', $section->id) }}"
                                                                method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input name="section_id" type="hidden"
                                                                    value="{{ $section->id }}">
                                                                <div class="row">
                                                                    @if ($code == $languages->first()->code)
                                                                        <div class="col-12">
                                                                            <div class="form-group">
                                                                                <label class="form-label"
                                                                                    for={{ $section->name . '_status' }}>{{ str($section->name)->replace('_', ' ')->title() }}
                                                                                    {{ __('Status') }}</label>
                                                                                <select class="form-control"
                                                                                    id="{{ $section->name . '_status' }}"
                                                                                    name="status">
                                                                                    <option value="1"
                                                                                        {{ $section->status == 1 ? 'selected' : '' }}>
                                                                                        {{ __('Active') }}
                                                                                    </option>
                                                                                    <option value="0"
                                                                                        {{ $section->status == 0 ? 'selected' : '' }}>
                                                                                        {{ __('Inactive') }}
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        @if (isset($section->global_content))
                                                                            @foreach ($section->global_content as $fieldName => $globalContent)
                                                                                @if ($globalContent->type == 'file' && $globalContent->value)
                                                                                    <div class="col-md-12">
                                                                                        <img class="img-fluid mb-3 preview_image"
                                                                                            data-preview-id="{{ $fieldName . '_' . $section->name }}"
                                                                                            src="{{ asset($globalContent->value) }}"
                                                                                            alt="{{ $fieldName }}"
                                                                                            style="max-width: 200px;">
                                                                                    </div>
                                                                                @endif
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label"
                                                                                            for="{{ $fieldName . '_' . $section->name }}">{{ str($fieldName)->replace('_', ' ')->title() }}</label>
                                                                                        @if ($globalContent->type == 'select')
                                                                                            <select class="form-select"
                                                                                                id="{{ $fieldName . '_' . $section->name }}"
                                                                                                name="{{ $fieldName }}">
                                                                                                @foreach ($globalContent->options as $value => $label)
                                                                                                    <option
                                                                                                        value="{{ $value }}"
                                                                                                        @selected($globalContent->value == $value)>
                                                                                                        {{ __($label) }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        @elseif($globalContent->type == 'textarea')
                                                                                            <textarea class="form-control" id="{{ $fieldName . '_' . $section->name }}" name="{{ $fieldName }}" rows="4">{{ $globalContent->value }}</textarea>
                                                                                        @elseif($globalContent->type == 'file')
                                                                                            <input
                                                                                                class="form-control show_preview"
                                                                                                id="{{ $fieldName . '_' . $section->name }}"
                                                                                                name="{{ $fieldName }}"
                                                                                                data-preview-id="{{ $fieldName . '_' . $section->name }}"
                                                                                                type="file">
                                                                                        @else
                                                                                            <input class="form-control"
                                                                                                id="{{ $fieldName . '_' . $section->name }}"
                                                                                                name="{{ $fieldName }}"
                                                                                                type="{{ $globalContent->type }}"
                                                                                                value="{{ $globalContent->value }}">
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
                                                                    @endif

                                                                    @php
                                                                        $content =
                                                                            (array) $section->getTranslation($code)
                                                                                ?->content ?? [];
                                                                        $noTranslation = false;
                                                                    @endphp

                                                                    @if (is_array($content) && !empty($content))
                                                                        @foreach ($content as $fieldName => $fieldValue)
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label class="form-label"
                                                                                        for="{{ $fieldName . '_' . $section->name . '_' . $code }}">
                                                                                        {{ str($fieldName)->replace('_', ' ')->title() }}
                                                                                    </label>
                                                                                    <input class="form-control"
                                                                                        id="{{ $fieldName . '_' . $section->name . '_' . $code }}"
                                                                                        name="translations[{{ $code }}][{{ $fieldName }}]"
                                                                                        type="text"
                                                                                        value="{{ $fieldValue }}">
                                                                                    @if (hasTitleKey($fieldName, $section->home_id, $section->name))
                                                                                        <small class="text-muted">
                                                                                            {{ __('You can wrap text with {YOUR_TEXT} to make it underline') }}
                                                                                        </small>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        @php
                                                                            $noTranslation =
                                                                                true &&
                                                                                $code !== $languages?->first()?->code;
                                                                        @endphp
                                                                        @if ($code !== $languages->first()->code)
                                                                            <div class="col-md-12">
                                                                                <div class="alert alert-warning">
                                                                                    {{ __('No translations available for this section') }}
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                                @if (!$noTranslation)
                                                                    <x-admin.update-button :text="__('Update')" />
                                                                @endif
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('css')
    <style>
        .preview_image {
            background-color: #535658;
            padding: 5px;
            border: 1px solid #ddd;
            display: inline-block;
            max-width: 220px;
        }
    </style>
@endpush

@push('js')
    <script>
        //Tab active setup locally
        $(document).ready(function() {
            activeTabSetupLocally('seo_tab');

            $(document).on('change', '.show_preview', function(e) {
                const input = this;
                const previewId = $(input).data('preview-id');
                const file = input.files[0];

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        $('img[data-preview-id="' + previewId + '"]').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endpush
