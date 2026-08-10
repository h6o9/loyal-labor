@extends('admin.master_layout')
@section('title')
    <title>{{ __('Translate Language') }} ({{ $language->name }})</title>
@endsection

@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Translate Language') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Manage Language') => route('admin.languages.index'),
                __('Translate Language') => '#',
            ]" />

            <div class="section-body">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-3 service_card">{{ __('Language Translations') }}</h5>
                            <div>
                                <a class="btn btn-primary" href="{{ route('admin.languages.index') }}"><i
                                        class="fa fa-arrow-left"></i>{{ __('Back') }}</a>
                            </div>
                        </div>
                        <hr>
                        <div class="lang_list_top">
                            <ul class="lang_list">
                                @foreach ($languages as $lang)
                                    <li><a href="{{ route('admin.languages.edit-static-languages', $lang->code) }}"><i
                                                class="fas {{ $lang->code !== request('code') ? 'fa-edit' : 'fa-eye' }}"></i>
                                            {{ $lang->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mt-2 alert alert-danger" role="alert">
                            <p>{{ __('Your editing mode') }} : <b>{{ $language->name }}</b></p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header d-block">
                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center">
                                        <h4 class="mb-0">{{ __('Edit') }}
                                            {{ ucwords(str_replace(['_', '-'], ' ', request('file'))) }}
                                            {{ __('Language') }}</h4>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <form onchange="$(this).trigger('submit')"
                                            action="{{ route('admin.languages.edit-static-languages', ['code' => request('code'), 'file' => request('file')]) }}"
                                            method="get">

                                            <div class="input-group">
                                                <input class="form-control" name="search" type="text"
                                                    value="{{ request('search') }}" placeholder="{{ __('Search') }}">
                                                <x-admin.button type="submit" :text="__('Search')" />
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-center justify-content-end">
                                        <button class="btn btn-primary" id="translateAll" data-code="{{ request('code') }}"
                                            data-file="{{ request('file') }}"
                                            type="button">{{ __('Translate All To ') }}{{ $language->name }}</button>
                                    </div>
                                </div>

                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.languages.update-static-languages', request('code')) }}"
                                    method="post">
                                    @csrf
                                    <table class="table table-bordered">
                                        @php($paginateData = [])
                                        @foreach ($data as $index => $value)
                                            <tr>
                                                <td width="50%">{{ $index }}</td>
                                                <td width="50%">
                                                    <input class="form-control" id="translation-{{ $loop->index + 1 }}"
                                                        name="values[{{ $index }}]" type="text"
                                                        value="{{ $value }}">
                                                </td>
                                            </tr>
                                            @php($paginateData[$index] = $value)
                                        @endforeach
                                    </table>
                                    <div class="text-center">
                                        <button class="btn btn-lg btn-primary" type="submit">{{ __('Update') }}</button>
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
    <script src="{{ asset('backend/js/iziToast.min.js') }}"></script>

    <script>
        "use strict"
        $(document).ready(function() {
            $(document).on('click', '#translateAll', function() {
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
                        ["<button title='{{ __('Yes') }}'><b>{{ __('YES') }}</b></button>",
                            function(instance,
                                toast) {
                                var isDemo = "{{ env('APP_MODE') ?? 'LIVE' }}";
                                var code = $('#translateAll').data('code');
                                var file = $('#translateAll').data('file');

                                if (isDemo == 'DEMO') {
                                    instance.hide({
                                        transitionOut: 'fadeOut'
                                    }, toast, 'button');
                                    toastr.error(
                                        "{{ __('This Is Demo Version. You Can Not Change Anything') }}"
                                    );
                                    return;
                                }

                                $.ajax({
                                    type: "post",
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        code: code,
                                        file: file,
                                        texts: "{{ json_encode($paginateData) }}",
                                    },
                                    url: "{{ route('admin.languages.translateAll') }}",
                                    beforeSend: function() {
                                        instance.hide({
                                            transitionOut: 'fadeOut'
                                        }, toast, 'button');

                                        iziToast.show({
                                            timeout: false,
                                            close: true,
                                            theme: 'dark',
                                            icon: 'loader',
                                            iconUrl: 'https://hub.izmirnic.com/Files/Images/loading.gif',
                                            title: "{{ __('This will take a while! wait....') }}",
                                            position: 'center',
                                        });
                                        $('.lang-btn').prop('disabled', true);
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            $('.lang-btn').prop('disabled', false);
                                            iziToast.destroy();
                                            toastr.success(response.message);
                                            setTimeout(function() {
                                                window.location.reload();
                                            }, 2000);
                                        }
                                    },
                                    error: function(err) {
                                        $('.lang-btn').prop('disabled', false);
                                        iziToast.destroy();
                                        toastr.error("{{ __('Failed!') }}")
                                        console.log(err);
                                    },
                                })

                            },
                            true
                        ],
                        ["<button title='{{ __('No') }}'>{{ __('NO') }}</button>",
                            function(instance, toast) {

                                instance.hide({
                                    transitionOut: 'fadeOut'
                                }, toast, 'button');

                            }
                        ],
                    ],
                    onClosing: function(instance, toast, closedBy) {},
                    onClosed: function(instance, toast, closedBy) {}
                });
            });
        });
    </script>
@endpush
