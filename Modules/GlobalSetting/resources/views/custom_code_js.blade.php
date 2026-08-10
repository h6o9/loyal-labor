@extends('admin.master_layout')
@section('title')
    <title>{{ __('Custom JS') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb :title="__('Custom JS')" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Custom JS') => '#',
            ]" />
            <div class="section-body">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-sm-12">
                                    <div class="border rounded">
                                        <div class="m-0 card">
                                            <div class="card-body">
                                                <form action="{{ route('admin.update-custom-code') }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <span data-toggle="tooltip" data-placement="top"
                                                                    class="fa fa-info-circle text--primary"
                                                                    title="{{ __('write your javascript here without the script tag') }}"></span>
                                                                <x-admin.form-textarea id="js-editor-header"
                                                                    name="header_javascript" label="{{ __('Header JS') }}"
                                                                    value=" {!! old('header_javascript', $customCode?->header_javascript) !!}" />
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <span data-toggle="tooltip" data-placement="top"
                                                                    class="fa fa-info-circle text--primary"
                                                                    title="{{ __('write your javascript here without the script tag') }}"></span>
                                                                <x-admin.form-textarea id="js-editor-body"
                                                                    name="body_javascript" label="{{ __('Body JS') }}"
                                                                    value="{!! old('body_javascript', $customCode?->body_javascript) !!}" />
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <span data-toggle="tooltip" data-placement="top"
                                                                    class="fa fa-info-circle text--primary"
                                                                    title="{{ __('write your javascript here without the script tag') }}"></span>
                                                                <x-admin.form-textarea id="js-editor-footer"
                                                                    name="footer_javascript" label="{{ __('Footer JS') }}"
                                                                    value="{!! old('footer_javascript', $customCode?->footer_javascript) !!}" />
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <x-admin.update-button :text="__('Update')" />
                                                </form>
                                            </div>
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
    <link rel="stylesheet" href="{{ asset('backend/codemirror/codemirror.css') }}">
@endpush

@push('js')
    <script src="{{ asset('backend/codemirror/codemirror.js') }}"></script>
    <script src="{{ asset('backend/codemirror/javascript.js') }}"></script>
    <script src="{{ asset('backend/codemirror/css.js') }}"></script>

    <script>
        var editorJsHeader = CodeMirror.fromTextArea(document.getElementById('js-editor-header'), {
            mode: "javascript",
            lineNumbers: true,
            lineWrapping: true,
            autocorrect: true,
        });
        editorJsHeader.save()

        var editorJsBody = CodeMirror.fromTextArea(document.getElementById('js-editor-body'), {
            mode: "javascript",
            lineNumbers: true,
            lineWrapping: true,
            autocorrect: true,
        });
        editorJsBody.save()

        var editorJsFooter = CodeMirror.fromTextArea(document.getElementById('js-editor-footer'), {
            mode: "javascript",
            lineNumbers: true,
            lineWrapping: true,
            autocorrect: true,
        });
        editorJsFooter.save()
    </script>
@endpush
