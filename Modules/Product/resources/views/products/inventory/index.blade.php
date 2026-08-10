@extends('admin.master_layout')
@section('title')
    <title>{{ __('Product Price List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Product Price List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Product Price List') => '#',
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-0">
                                <form id="search-form" action="" method="GET" onsubmit="return false;">
                                    <div class="row">
                                        <div class="col-xl-4 col-md-6">
                                            <div class="form-group search-wrapper">
                                                <input class="form-control" id="keyword" name="keyword" type="text"
                                                    value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}..." autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-6">
                                            <div class="form-group">
                                                <select class="form-control" id="order_by" name="order_by">
                                                    <option value="">{{ __('Order By') }}</option>
                                                    <option value="asc"
                                                        {{ request('order_by') == 'asc' ? 'selected' : '' }}>
                                                        {{ __('ASC') }}
                                                    </option>
                                                    <option value="desc"
                                                        {{ request('order_by') == 'desc' ? 'selected' : '' }}>
                                                        {{ __('DESC') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6">
                                            <div class="form-group">
                                                <select class="form-control select2" id="brand_id" name="brand_id">
                                                    <option value="" selected disabled>{{ __('Brand') }}
                                                    </option>
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->id }}" @selected(request('brand_id') == $brand->id)>
                                                            {{ $brand->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6">
                                            <div class="form-group">
                                                <select class="form-control select2" id="categories" name="category_id">
                                                    <option value="" selected disabled>{{ __('Categories') }}
                                                    </option>
                                                    @foreach ($categories as $cat)
                                                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>
                                                            {{ $cat->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('Product Price List')" />
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="inventoryTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Sku') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Manage Stock') }}</th>
                                                <th>{{ __('Stock Quantity') }}</th>
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
            var inventoryTable = initServerDataTable('#inventoryTable', "{{ route('admin.products.product-inventory') }}", [
                { data: 'sku', name: 'sku', orderable: false, searchable: false },
                { data: 'name', name: 'name', orderable: false, searchable: false },
                { data: 'manage_stock_control', name: 'manage_stock', orderable: false, searchable: false },
                { data: 'stock_qty_control', name: 'stock_qty', orderable: false, searchable: false },
            ], {
                ajax: {
                    url: "{{ route('admin.products.product-inventory') }}",
                    data: function(d) {
                        d.keyword = $('#keyword').val();
                        d.order_by = $('#order_by').val();
                        d.brand_id = $('#brand_id').val();
                        d.category_id = $('#categories').val();
                    }
                }
            });

            $('#keyword').on('keyup', function() {
                inventoryTable.ajax.reload();
            });

            $('#order_by, #brand_id, #categories').on('change', function() {
                inventoryTable.ajax.reload();
            });

            $(document).on('change', '.update-stock', function() {
                var field = $(this).data('field');
                var product_id = $(this).data('product-id');
                var product_variant_id = $(this).data('product-variant-id');
                var is_variant = $(this).data('is-variant');
                var sku = $(this).data('sku');
                if (field == 'manage_stock') {
                    var value = $(this).is(':checked') ? 1 : 0;
                } else {
                    var value = $(this).val();
                }

                if (field !== 'manage_stock' && (value == null || value == '')) {
                    toastr.error("{{ __('Please select a value') }}");
                    return;
                }

                let dataForm = {
                    field: field,
                    value: value,
                    sku: sku,
                    product_id: product_id,
                    _token: '{{ csrf_token() }}',
                };

                if (is_variant) {
                    dataForm.product_variant_id = product_variant_id;
                }

                $.ajax({
                    url: "{{ route('admin.products.product-inventory.store') }}",
                    type: 'POST',
                    data: dataForm,
                    headers: {
                        "Accept": "application/json",
                    },
                    beforeSend: function() {
                        $('.update-stock').attr('disabled', true);
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }

                        setTimeout(() => {
                            $('.update-stock').attr('disabled', false);
                            inventoryTable.ajax.reload(null, false);
                        }, 1000);
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                        $('.update-stock').attr('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
