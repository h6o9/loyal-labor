@extends('seller.layouts.master')

@section('title')
    <title>{{ __('Product Return Policy') }}</title>
@endsection

@section('seller-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Product Return Policy') }}" :list="[
                __('Dashboard') => route('seller.dashboard'),
                __('Product Return Policy') => '#',
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card mt-5">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Product Return Policy')" />
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table product_list_table min-height-600" id="returnPoliciesTable"
                                        style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Question') }}</th>
                                                <th>{{ __('Answer') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
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
    <script>
        "use strict";

        $(document).ready(function() {
            initServerDataTable('#returnPoliciesTable', "{{ route('seller.products.product-return-policy') }}", [
                { data: 'question', name: 'question' },
                { data: 'answer_col', name: 'answer' },
                { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);
        });

        function deleteData(id) {
            var id = id;
            var url = '{{ route('seller.products.product-return-policy.delete', ':id') }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
            $('#deleteModal').modal('show');
        }
    </script>
@endpush
