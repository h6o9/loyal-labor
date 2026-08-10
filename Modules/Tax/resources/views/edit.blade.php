@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Tax') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Edit Tax') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Tax List') => route('admin.tax.index'),
                __('Edit Tax') => '#',
            ]" />
            <div class="section-body row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header gap-3 justify-content-between align-items-center">
                            <h5 class="m-0 service_card">{{ __('Available Translations') }}</h5>
                            @adminCan('tax.translate')
                                @if ($code !== $languages->first()->code)
                                    <x-admin.button id="translate-btn" :text="__('Translate')" />
                                @endif
                            @endadminCan
                        </div>
                        <div class="card-body">
                            <div class="lang_list_top">
                                <ul class="lang_list">
                                    @foreach ($languages as $language)
                                        <li><a id="{{ request('code') == $language->code ? 'selected-language' : '' }}"
                                                href="{{ route('admin.tax.edit', ['tax' => $tax->id, 'code' => $language->code]) }}"><i
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
                                <x-admin.form-title :text="__('Edit Tax')" />
                                <div>
                                    <x-admin.back-button :href="route('admin.tax.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.tax.update', ['tax' => $tax->id, 'code' => $code]) }}"
                                    method="post">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <x-admin.form-input id="title" name="title" data-translate="true"
                                                    value="{{ old('title', $tax->getTranslation($code)->title) }}"
                                                    label="{{ __('Title') }}" placeholder="{{ __('Enter Title') }}"
                                                    placeholder="{{ __('Enter Title') }}" required="true" />
                                            </div>
                                        </div>
                                        @if ($code == $languages->first()->code)
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <x-admin.form-input id="slug" name="slug"
                                                        value="{{ old('slug', $tax->slug) }}" label="{{ __('Slug') }}"
                                                        placeholder="{{ __('Enter Slug') }}" required="true" />
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <x-admin.form-input id="percentage" step="0.01" name="percentage"
                                                    data-translate="true" type="number"
                                                    value="{{ old('slug', $tax->percentage) }}"
                                                    label="{{ __('Percentage') }}"
                                                    placeholder="{{ __('Enter Percentage') }}" required="true" />
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

@if ($code == $languages->first()->code)
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
@else
    @push('js')
        <script>
            var isTranslatingInputs = true;

            function translateOneByOne(inputs, index = 0) {
                if (index >= inputs.length) {
                    if (isTranslatingInputs) {
                        isTranslatingInputs = false;
                        translateAllTextarea();
                    }
                    $('#translate-btn').prop('disabled', false);
                    $('#update-btn').prop('disabled', false);
                    return;
                }

                var $input = $(inputs[index]);
                var inputValue = $input.val();

                if (inputValue) {
                    $.ajax({
                        url: "{{ route('admin.languages.update.single') }}",
                        type: "POST",
                        data: {
                            lang: '{{ $code }}',
                            text: inputValue,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $input.prop('disabled', true);
                            iziToast.show({
                                timeout: false,
                                close: true,
                                theme: 'dark',
                                icon: 'loader',
                                iconUrl: 'https://hub.izmirnic.com/Files/Images/loading.gif',
                                title: "{{ __('Translation Processing, please wait...') }}",
                                position: 'center',
                            });
                        },
                        success: function(response) {
                            $input.val(response);
                            $input.prop('disabled', false);
                            iziToast.destroy();
                            toastr.success("{{ __('Translated Successfully!') }}");
                            translateOneByOne(inputs, index + 1);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error(textStatus, errorThrown);
                            iziToast.destroy();
                            toastr.error('Error', 'Error');
                        }
                    });
                } else {
                    translateOneByOne(inputs, index + 1);
                }
            }

            function translateAll() {
                iziToast.question({
                    timeout: 20000,
                    close: false,
                    overlay: true,
                    displayMode: 'once',
                    id: 'question',
                    zindex: 999,
                    title: "{{ __('This will take a while!') }}",
                    message: "{{ __('Are you sure?') }}",
                    position: 'center',
                    buttons: [
                        ["<button title='{{ __('Yes') }}'><b>{{ __('YES') }}</b></button>", function(
                            instance, toast) {
                            var isDemo = "{{ env('APP_MODE') ?? 'LIVE' }}";

                            if (isDemo == 'DEMO') {
                                instance.hide({
                                    transitionOut: 'fadeOut'
                                }, toast, 'button');
                                toastr.error("{{ __('This Is Demo Version. You Can Not Change Anything') }}");
                                return;
                            }

                            $('#translate-btn').prop('disabled', true);
                            $('#update-btn').prop('disabled', true);

                            instance.hide({
                                transitionOut: 'fadeOut'
                            }, toast, 'button');

                            var inputs = $('input[data-translate="true"]').toArray();
                            translateOneByOne(inputs);

                        }, true],
                        ["<button title='{{ __('No') }}'>{{ __('NO') }}</button>", function(instance,
                            toast) {

                            instance.hide({
                                transitionOut: 'fadeOut'
                            }, toast, 'button');

                        }],
                    ],
                    onClosing: function(instance, toast, closedBy) {},
                    onClosed: function(instance, toast, closedBy) {}
                });
            };

            function translateAllTextarea() {
                var inputs = $('textarea[data-translate="true"]').toArray();
                if (inputs.length === 0) {
                    return;
                }
                translateOneByOne(inputs);
            }

            $(document).ready(function() {
                var selectedTranslation = $('#selected-language').text();
                var btnText = "{{ __('Translate to') }}" + selectedTranslation;
                $('#translate-btn').text(btnText);
            });
        </script>
    @endpush
@endif
