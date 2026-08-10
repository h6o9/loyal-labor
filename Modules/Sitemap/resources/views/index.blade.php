@extends('admin.master_layout')
@section('title')
    <title>{{ __('Sitemap') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Sitemap') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Sitemap') => '#',
            ]" />
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <form class="card-body" action="{{ route('admin.sitemap.store') }}" method="post">
                                @csrf
                                <div class="alert alert-info alert-has-icon">
                                    <div class="alert-icon"><i class="fas fa-sitemap"></i></div>
                                    <div class="alert-body">
                                        <div class="alert-title">{{ __('Info') }}</div>
                                        {{ __('Click the Generate button below to create your sitemap dynamically.') }}
                                    </div>
                                </div>
                                <x-admin.save-button :text="__('Generate')" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
