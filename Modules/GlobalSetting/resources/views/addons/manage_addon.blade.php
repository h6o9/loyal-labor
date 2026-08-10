@extends('admin.master_layout')
@section('title')
    <title>{{ __('Manage Addons') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Manage Addon') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Manage Addon') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Manage Addons') }}</h4>
                                <div class="card-header-action">
                                    @adminCan('addon.install')
                                        <a class="btn btn-success" href="{{ route('admin.addons.install') }}"><i
                                                class="fas fa-plus"></i>
                                            {{ __('Install New') }}</a>
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($addons as $addon)
                                        @php
                                            $options = json_decode($addon->options, true); // Decode as an associative array
                                            $route =
                                                isset($options['setting_route']) && count($options) > 0
                                                    ? $options['setting_route']
                                                    : false;
                                        @endphp
                                        <div class="text-center col-lg-3 col-md-6 col-sm-12">
                                            <div class="p-1 border card">
                                                <div class="card-body">
                                                    @if ($addon->icon)
                                                        <img src="{{ $addon->icon }}" alt="">
                                                    @endif
                                                    <h4>{{ $addon->name }}</h4>
                                                    <p class="card-text">{{ $addon->description }}</p>
                                                    <p class="card-text">{{ __('version') }}: {{ $addon->version }}</p>
                                                    <p class="card-text">{{ __('Update') }}: {{ $addon->last_update }}
                                                    </p>
                                                    @if ($addon->is_default)
                                                        <button class="btn btn-success" type="button"
                                                            disabled>{{ __('Installed') }}</a>
                                                        @else
                                                            @if ($route && $addon->status && Route::has($route))
                                                                <a class="btn btn-primary" href="{{ route($route) }}"
                                                                    target="_blank" rel="noopener noreferrer">
                                                                    <i class="fas fa-cogs"></i>
                                                                </a>
                                                            @endif
                                                            @if ($addon->status)
                                                                <a class="btn btn-warning"
                                                                    href="{{ route('admin.addons.update.status', $addon->slug) }}">{{ __('Disable') }}</a>
                                                            @else
                                                                <a class="btn btn-success"
                                                                    href="{{ route('admin.addons.update.status', $addon->slug) }}">{{ __('Enable') }}</a>
                                                            @endif
                                                            <a class="btn btn-danger" data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal"
                                                                href="{{ route('admin.addons.uninstall', $addon->slug) }}"
                                                                onclick="deleteData('{{ route('admin.addons.uninstall', $addon->slug) }}')">{{ __('Uninstall') }}</a>
                                                    @endif
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
        </section>
    </div>

    <x-admin.delete-modal />
@endsection

@push('js')
    <script>
        "use strict";

        function deleteData(url) {
            $("#deleteForm").attr("action", url)
        }
    </script>
@endpush
