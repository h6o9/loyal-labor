@extends('admin.master_layout')
@section('title')
    <title>{{ __('Currency List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Currency List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Currency List') => '#',
            ]" />
            <div class="section-body">
                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Currency List')" />
                                @adminCan('currency.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.currency.create')" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="currencyTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Currency') }}</th>
                                                <th>{{ __('Country Code') }}</th>
                                                <th>{{ __('Currency Code') }}</th>
                                                <th>{{ __('Currency Icon') }}</th>
                                                <th>{{ __('Currency Rate') }}</th>
                                                <th>{{ __('Default') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                @if (checkAdminHasPermission('currency.edit') || checkAdminHasPermission('currency.delete'))
                                                    <th>{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
    @adminCan('currency.delete')
        <x-admin.delete-modal />
    @endadminCan

@endsection
@push('js')
    @include('admin.partials.system-records-toast')
    <script>
        $(document).ready(function() {
            initServerDataTable('#currencyTable', "{{ route('admin.currency.index') }}", [
                { data: 'currency_name', name: 'currency_name' },
                { data: 'country_code', name: 'country_code' },
                { data: 'currency_code', name: 'currency_code' },
                { data: 'currency_icon', name: 'currency_icon' },
                { data: 'currency_rate', name: 'currency_rate' },
                { data: 'default_badge', name: 'is_default', orderable: false, searchable: false },
                { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                @if (checkAdminHasPermission('currency.edit') || checkAdminHasPermission('currency.delete'))
                { data: 'action', name: 'action', orderable: false, searchable: false },
                @endif
            ]);
        });
    </script>
@endpush