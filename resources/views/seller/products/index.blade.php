@extends('seller.layouts.master')

@section('title')
    <title>{{ __('Product List') }}</title>
@endsection

@section('seller-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Product List') }}" :list="[
                __('Dashboard') => route('seller.dashboard'),
                __('Product List') => '#',
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-0">
                                <form id="product_filter_form" action="" method="GET">
                                    <div class="row">
                                        <div class="col-xxl-2 col-md-3">
                                            <div class="form-group search-wrapper">
                                                <input class="form-control" name="keyword" type="text"
                                                    value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}..." autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-xxl-2 col-md-3">
                                            <div class="form-group">
                                                <select class="form-control" id="order_by" name="order_by">
                                                    <option value="">{{ __('Order By') }}</option>
                                                    <option value="asc"
                                                        {{ request('order_by') == 'asc' ? 'selected' : '' }}>
                                                        {{ __('Name - ASC') }}
                                                    </option>
                                                    <option value="desc"
                                                        {{ request('order_by') == 'desc' ? 'selected' : '' }}>
                                                        {{ __('Name - DESC') }}
                                                    </option>
                                                    <option value="id_asc"
                                                        {{ request('order_by') == 'id_asc' ? 'selected' : '' }}>
                                                        {{ __('Created At - ASC') }}
                                                    </option>
                                                    <option value="id_desc"
                                                        {{ request('order_by') == 'id_desc' ? 'selected' : '' }}>
                                                        {{ __('Created At - DESC') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-2 col-md-3">
                                            <div class="form-group">
                                                <select class="form-control" id="status" name="status">
                                                    <option value="">{{ __('Status') }}</option>
                                                    <option value="published" @selected(request('status') == 'published')>
                                                        {{ __('Published') }}
                                                    </option>
                                                    <option value="hidden" @selected(request('status') == 'hidden')>
                                                        {{ __('Hidden') }}
                                                    </option>
                                                    <option value="approved" @selected(request('status') == 'approved')>
                                                        {{ __('Approved') }}
                                                    </option>
                                                    <option value="pending" @selected(request('status') == 'pending')>
                                                        {{ __('Pending') }}
                                                    </option>
                                                    <option value="flash_deal" @selected(request('status') == 'flash_deal')>
                                                        {{ __('Flash Deal') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-2 col-md-3">
                                            <div class="form-group">
                                                <select class="form-control select2" id="brand_id" name="brand_id">
                                                    <option value="" selected disabled>{{ __('Brand') }}
                                                    </option>
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->id }}" @selected($brand->id == request('brand_id'))>
                                                            {{ $brand->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-2 col-md-3">
                                            <div class="form-group">
                                                <select class="form-control select2" id="categories" name="category_id">
                                                    <option value="" selected disabled>{{ __('Categories') }}
                                                    </option>
                                                    @foreach ($categories as $cat)
                                                        <option value="{{ $cat->id }}" @selected($cat->id == request('category_id'))>
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
                        <div class="card mt-5">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Product List')" />

                                <div>
                                    <x-admin.add-button :href="route('seller.product.create')" />
                                </div>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table product_list_table min-height-600" id="productsTable"
                                        style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Photo') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Stock') }}</th>
                                                <th>{{ __('Original Price') }}</th>
                                                <th>{{ __('After Disc. P.') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Approved') }}</th>
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

    <!-- Modal -->
    <div class="modal fade" id="canNotDeleteModal" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    {{ __('You can not delete this product. Because there are one or more order has been created in this product.') }}
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger" data-bs-dismiss="modal" type="button">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="productView" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            'use strict';
            $('.productView').on('click', function() {
                var id = $(this).data('id');
                let url = '{{ route('seller.product.view', ':id') }}';
                url = url.replace(':id', id);
                $.ajax({
                    type: "GET",
                    url,
                    success: function(response) {
                        $('#productView .modal-content').html(response.product);
                        $('#productView').modal('show');
                    }
                });
            })

            $('input[name="select"]').on('click', function() {
                var total = $('input[name="select"]').length;
                var number = $('input[name="select"]:checked').length;
                if (total == number) {
                    $('#checkbox-all').prop('checked', true);
                } else {
                    $('#checkbox-all').prop('checked', false);
                }
                $('.number').text(number);

                if (number > 0) {
                    $('.delete-section').removeClass('d-none');
                    $('.delete-section').addClass('d-flex');
                } else {
                    $('.delete-section').addClass('d-none');
                    $('.delete-section').removeClass('d-flex');
                }
            });

            // delete all selected
            $('.delete-button').on('click', function() {
                var ids = [];
                $('input[name="select"]:checked').each(function() {
                    ids.push($(this).attr('id').split('-')[1]);
                });
            });

            var productsTable = initServerDataTable('#productsTable', {
                url: "{{ route('seller.product.index') }}",
                data: function(d) {
                    d.keyword = $('#product_filter_form [name="keyword"]').val();
                    d.order_by = $('#order_by').val();
                    d.status = $('#status').val();
                    d.brand_id = $('#brand_id').val();
                    d.category_id = $('#categories').val();
                }
            }, [
                { data: 'photo', name: 'photo', orderable: false, searchable: false },
                { data: 'name', name: 'name', orderable: false, searchable: false },
                { data: 'stock', name: 'stock', orderable: false, searchable: false },
                { data: 'current_price', name: 'current_price', orderable: false, searchable: false },
                { data: 'discounted_price_col', name: 'discounted_price_col', orderable: false, searchable: false },
                { data: 'status_toggle', name: 'status', orderable: false, searchable: false },
                { data: 'approved_badge', name: 'is_approved', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);

            $('#product_filter_form').on('submit', function(e) {
                e.preventDefault();
                productsTable.ajax.reload();
            });

            $('#product_filter_form').on('change', function(e) {
                e.preventDefault();
                productsTable.ajax.reload();
            });
        });

        function deleteData(id) {
            var id = id;
            var url = '{{ route('seller.product.destroy', ':id') }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
            $('#deleteModal').modal('show');
        }

        function status(id) {
            handleStatus("{{ route('seller.product.status', ':id') }}".replace(':id', id));

            let status = $('[data-status=' + id + ']').text()
            // remove whitespaces using regex
            status = status.replaceAll(/\s/g, '');
            $('[data-status=' + id + ']').text(status != 'Hidden' ? 'Hidden' : 'Published')
        }
    </script>
@endpush

@push('css')
    <style>
        .min-height-600 {
            min-height: 600px !important;
        }
    </style>
@endpush
