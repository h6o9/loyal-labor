@extends('admin.master_layout')
@section('title')
    <title>{{ __('Change Homepage') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Change Homepage') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Manage Contents') => route('admin.frontend.index'),
                __('Change Homepage') => '#',
            ]" />
            <div class="section-body">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <form class="on-change-submit" action="{{ route('admin.frontend.homepage.update') }}"
                                        method="POST">
                                        @csrf
                                        <label class="d-flex align-items-center mb-0">
                                            <input class="custom-switch-input" name="show_all_homepage" type="hidden"
                                                value="0">
                                            <input class="custom-switch-input" name="show_all_homepage" type="checkbox"
                                                value="1" {{ $setting?->show_all_homepage == '1' ? 'checked' : '' }}>
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">{{ __('Show All Homepage') }}</span>
                                        </label>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="row">
                    @foreach ($themes as $theme)
                        @php
                            $is_active = config('services.theme') == $theme?->name?->value;
                        @endphp

                        <div class="col-md-6 col-lg-4 col-xl-3 theme_item">
                            <h6 class="text-center d-flex justify-content-center gap-1">
                                {{ $theme?->title }}
                                @if ($is_active)
                                    <div class="badges">
                                        <span class="badge badge-success m-0">{{ __('Active') }}</span>
                                    </div>
                                @endif
                            </h6>
                            <div class="theme_screenshot shadow">
                                <img src="{{ asset($theme?->screenshot) }}" alt="{{ $theme?->title }}">
                            </div>
                            @unless ($is_active)
                                <form class="d-none" id="theme-update-{{ $theme?->name }}"
                                    action="{{ route('admin.frontend.homepage.update') }}" method="POST">
                                    @csrf
                                    <x-admin.form-input id="{{ $theme?->name }}" name="theme" type="hidden"
                                        value="{{ $theme?->name }}" required />
                                </form>
                                <button class="btn btn-sm btn-primary activate-default-theme"
                                    data-form-id="#theme-update-{{ $theme?->name }}">
                                    {{ __('Activate') }}
                                </button>
                            @endunless
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endsection
