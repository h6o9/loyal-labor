@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Testimonial') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit Testimonial') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Testimonials') => route('admin.testimonial.index'),
                __('Edit Testimonial') => '#',
            ]" />
            <div class="section-body row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header gap-3 justify-content-between align-items-center">
                            <h5 class="m-0 service_card">{{ __('Available Translations') }}</h5>
                            @adminCan('testimonial.translate')
                                @if ($code !== $languages->first()->code)
                                    <x-admin.button id="translate-btn"  :text="__('Translate')" />
                                @endif
                            @endadminCan
                        </div>
                        <div class="card-body">
                            <div class="lang_list_top">
                                <ul class="lang_list">
                                    @foreach (allLanguages() as $language)
                                        <li>
                                            <a id="{{ request('code') == $language->code ? 'selected-language' : '' }}"
                                                href="{{ route('admin.testimonial.edit', ['testimonial' => $testimonial->id, 'code' => $language->code]) }}">
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
                                <x-admin.form-title :text="__('Edit Testimonial')" />
                                <div>
                                    <x-admin.back-button :href="route('admin.testimonial.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form
                                    action="{{ route('admin.testimonial.update', [
                                        'testimonial' => $testimonial->id,
                                        'code' => $code,
                                    ]) }}"
                                    enctype="multipart/form-data" method="post">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-input id="name"  name="name" label="{{ __('Name') }}" placeholder="{{ __('Enter Name') }}" value="{{ old('name', $testimonial->getTranslation($code)->name) }}" required="true"/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-input id="designation"  name="designation" label="{{ __('Designation') }}" placeholder="{{ __('Enter Designation') }}" value="{{ old('designation', $testimonial->getTranslation($code)->designation) }}" required="true"/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-textarea data-translate="true" id="comment" name="comment" label="{{ __('Comment') }}" placeholder="{{ __('Enter Comment') }}" value="{{ old('comment', $testimonial->getTranslation($code)->comment) }}" maxlength="5000" required="true" />
                                            </div>
                                        </div>
                                        <div class="col-md-12 {{$code == $languages->first()->code ? '': 'd-none'}}">
                                            <div class="form-group">
                                                <x-admin.form-input type="number" id="rating"  min="1" max="5"  name="rating" label="{{ __('Rating') }}" placeholder="{{ __('Enter Rating') }}" value="{{ old('rating', $testimonial->rating) }}"  required="true"/>
                                            </div>
                                        </div>
                                            <div class="col-md-12 {{$code == $languages->first()->code ? '': 'd-none'}}">
                                                <div class="form-group">
                                                    <x-admin.form-image-preview label="Image" :image="$testimonial->image" required="0"/>
                                                </div>
                                            </div>
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
    <script src="{{ asset('backend/js/jquery.uploadPreview.min.js') }}"></script>
    <script>
        "use strict";
        $.uploadPreview({
            input_field: "#image-upload",
            preview_box: "#image-preview",
            label_field: "#image-label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });
    </script>
    <script>
        'use strict';
        $('#translate-btn').on('click', function() {
            translateAllTo("{{ $code }}");
        })
    </script>
@endpush
