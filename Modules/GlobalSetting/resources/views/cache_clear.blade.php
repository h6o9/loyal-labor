@extends('admin.master_layout')
@section('title')
    <title>{{ __('Clear cache') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Clear cache') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Clear cache') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="alert alert-warning alert-has-icon">
                                    <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                                    <div class="alert-body">
                                        <div class="alert-title">{{ __('Warning') }}</div>
                                        {{ __('If you want to clearing all caches on your website may briefly affect its performance as cached data is regenerated.') }}
                                    </div>
                                </div>
                                <x-admin.button variant="danger" data-bs-toggle="modal" data-bs-target="#cacheClearModal" text="{{__('Clear cache')}}"/>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="cacheClearModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Cache Clear Confirmation') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Are You sure want to clear cache ?') }}</p>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <form action="{{ route('admin.cache-clear-confirm') }}" method="POST">
                        @csrf
                        <x-admin.button variant="danger" data-bs-dismiss="modal" text="{{ __('Close') }}" />
                        <x-admin.button type="submit" text="{{ __('Yes, Clear') }}" />
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
