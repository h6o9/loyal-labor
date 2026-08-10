@extends('admin.master_layout')
@section('title')
    <title>{{ __('Database clear') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Database clear') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Database clear') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="alert alert-warning alert-has-icon">
                                    <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                                    <div class="alert-body">
                                        <div class="alert alert-warning" role="alert">
                                            <h4 class="alert-heading">⚠️ {{ __('Warning') }}:
                                                {{ __('This will permanently delete') }} <span
                                                    class="text-danger">({{ __('NO BACKUP FOUND') }})</span>:
                                            </h4>
                                            <ul>
                                                <li>{{ __('All products, variants, galleries, and their translations') }}
                                                </li>
                                                <li>{{ __('All brands, categories, tags, and related metadata') }}</li>
                                                <li>{{ __('All user and vendor accounts with their wishlists') }}</li>
                                                <li>{{ __('All KYC and withdrawal method configurations') }}</li>
                                                <li>{{ __('All tax rules, shipping rules, and shipping items') }}</li>
                                                <li>{{ __('All blog posts, blog categories, and blog comments') }}</li>
                                                <li>{{ __('All testimonials and their translations') }}</li>
                                                <li>{{ __('All FAQ entries and their translations') }}</li>
                                                <li>{{ __('All coupons and discount codes') }}</li>
                                                <li>{{ __('All product labels and product terms of service') }}</li>
                                                <li>{{ __('All countries, states, and cities') }}</li>
                                                <li>{{ __('All product attributes, values, and their translations') }}</li>
                                                <li>{{ __('All unit types and return policies') }}</li>
                                                <li>{{ __('All user reviews and related order details') }}</li>
                                            </ul>
                                            <hr>
                                            <p class="mb-0"><strong>{{ __('Important Note') }}:</strong>
                                                {{ __('This action will completely erase all data from the system and cannot be undone. Please ensure you have a full database backup before proceeding.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <x-admin.button data-bs-toggle="modal" data-bs-target="#clearDatabaseModal" variant="danger"
                                    text="{{ __('Clear Database') }}" />
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="clearDatabaseModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <form class="modal-content" action="{{ route('admin.database-clear-success') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Clear Database Confirmation') }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Are you really want to clear this database?') }}</p>
                    <x-admin.form-input id="password" name="password" type="password" label="{{ __('Password') }}"
                        required="true" />
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <x-admin.button data-bs-dismiss="modal" variant="danger" text="{{ __('Close') }}" />
                    <x-admin.button type="submit" text="{{ __('Yes, Clear') }}" />
                </div>
            </form>
        </div>
    </div>
@endsection
