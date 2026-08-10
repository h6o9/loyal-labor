@extends('admin.master_layout')
@section('title')
    <title>{{ __('SEO Setup') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('SEO Setup') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('SEO Setup') => '#',
            ]" />
            <div class="section-body">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-3">
                                    <ul class="nav nav-pills flex-column" id="seo_tab" role="tablist">
                                        @foreach ($pages as $index => $page)
                                            <li class="nav-item border rounded mb-1">
                                                <a class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                                    id="error-tab-{{ $page->id }}" data-bs-toggle="tab"
                                                    href="#errorTab-{{ $page->id }}" role="tab"
                                                    aria-controls="errorTab-{{ $page->id }}"
                                                    aria-selected="true">{{ $page->page_name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-12 col-sm-12 col-md-9">
                                    <div class="border rounded">
                                        <div class="tab-content no-padding" id="settingsContent">
                                            @foreach ($pages as $index => $page)
                                                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                                                    id="errorTab-{{ $page->id }}" role="tabpanel"
                                                    aria-labelledby="error-tab-{{ $page->id }}">
                                                    <div class="card m-0">
                                                        <div class="card-body">
                                                            <form
                                                                action="{{ route('admin.update-seo-setting', $page->id) }}"
                                                                method="POST">
                                                                @method('PUT')
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <x-admin.form-input id="seo_title"
                                                                                name="seo_title"
                                                                                value="{{ $page->seo_title }}"
                                                                                label="{{ __('SEO Title') }}"
                                                                                placeholder="{{ __('Enter SEO Title') }}"
                                                                                :required="true" />
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <x-admin.form-textarea id="seo_description"
                                                                                name="seo_description"
                                                                                value="{{ $page->seo_description }}"
                                                                                label="{{ __('SEO Description') }}"
                                                                                placeholder="{{ __('Enter SEO Description') }}"
                                                                                maxlength="1000" :required="true" />

                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <x-admin.update-button :text="__('Update')" />
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
@push('js')
    <script>
        //Tab active setup locally
        $(document).ready(function() {
            activeTabSetupLocally('seo_tab')
        });
    </script>
@endpush
