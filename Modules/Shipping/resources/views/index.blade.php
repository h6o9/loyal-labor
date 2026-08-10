@extends('admin.master_layout')
@section('title')
    <title>{{ __('Shipping Rule') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Shipping Rule') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Shipping Rule') => '#',
            ]" />
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('Shipping Rule Setting')" />
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.shipping.setting.update') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        {{-- <div class="col-12">
                                            <div class="form-group">
                                                <x-admin.form-switch name="hide_shipping_option"
                                                    label="{{ __('Disable shipping options') }}" :checked="old(
                                                        'hide_shipping_option',
                                                        $shippingSetting?->hide_shipping_option,
                                                    )" />
                                                <small class="text-muted">
                                                    {{ __('Shipping options will be removed at the checkout page, customer won\'t be able to select shipping options') }}.
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <x-admin.form-switch name="hide_other_shipping"
                                                    label="{{ __('Hide other shipping options if it has free shipping in the list') }}"
                                                    :checked="old(
                                                        'hide_other_shipping',
                                                        $shippingSetting?->hide_other_shipping,
                                                    )" />
                                            </div>
                                        </div> --}}
                                        <div class="col-12">
                                            <div class="control-label mb-3">{{ __('Sort Shipping Options Direction') }}
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" id="asc"
                                                    name="sort_shipping_direction" type="radio" value="asc"
                                                    {{ $shippingSetting?->sort_shipping_direction == 'asc' ? 'checked' : '' }}>
                                                <label class="custom-switch-description" for="asc">
                                                    {{ __('Price lower to higher') }}
                                                </label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" id="desc"
                                                    name="sort_shipping_direction" type="radio" value="desc"
                                                    {{ $shippingSetting?->sort_shipping_direction == 'desc' ? 'checked' : '' }}>
                                                <label class="custom-switch-description"
                                                    for="desc">{{ __('Price higher to lower') }}</label>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <x-admin.update-button :text="__('Update')">
                                            </x-admin.update-button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Shipping Rule')" />
                                {{-- @adminCan('product.product.create') --}}
                                <div>
                                    <x-admin.add-button :href="route('admin.shipping.create')" />
                                </div>
                                {{-- @endadminCan --}}
                            </div>
                            <div class="card-body text-center">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="shippingTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('SN') }}</th>
                                                <th width="30%">{{ __('Name') }}</th>
                                                <th width="10%">{{ __('Charge') }}</th>
                                                <th width="15%">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
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
    @include('admin.partials.system-records-toast')
    <script>
        $(document).ready(function() {
            initServerDataTable('#shippingTable', "{{ route('admin.shipping.index') }}", [
                { data: 'name', name: 'name' },
                { data: 'charge', name: 'price' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);
        });
    </script>
@endpush
