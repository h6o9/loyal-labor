@extends('admin.master_layout')
@section('title')
    <title>
        {{ __('Product Variant List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Product Variant List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Product List') => route('admin.product.index'),
                __('Product Variant List') => '#',
            ]" />
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                @php
                                    $title = $product->name . ' ' . __('Variant List');
                                @endphp

                                <a href="{{ route('admin.product.show', $product->id) }}">
                                    <h4>{{ $title }}</h4>
                                </a>
                                @adminCan('product.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.product.product-variant.create', $product->id)" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body text-center">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('SN') }}</th>
                                                <th width="30%">{{ __('Sku') }}</th>
                                                <th width="10%">{{ __('Price') }}</th>
                                                <th width="10%">{{ __('Offer Price') }}</th>
                                                <th width="5%">{{ __('Stock') }}</th>
                                                <th width="25%">{{ __('Attributes') }}</th>
                                                <th width="15%">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($variants as $index => $variant)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $variant['sku'] }} @if ($variant['is_default'] == 1)
                                                            <span class="badge badge-success">{{ __('Default') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ currency($variant['price']) }}</td>
                                                    <td>
                                                        {{ $variant['offer_price_type'] == 'fixed'
                                                            ? currency($variant['offer_price'])
                                                            : currency($variant['discounted_price']?->discounted_price) . '(' . $variant['offer_price'] . '%)' }}
                                                    </td>
                                                    <td>{{ $variant['stock_qty'] }} {{ optional($product->unit)->name }}
                                                    </td>
                                                    <td>
                                                        @foreach ($variant['attributes'] as $attr)
                                                            {{ $attr['attribute_value'] }} @if (!$loop->last)
                                                                {{ __(' , ') }}
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-primary btn-sm"
                                                            href="{{ route('admin.product.product-variant.edit', $variant['id']) }}"><i
                                                                class="fa fa-edit" aria-hidden="true"></i></a>

                                                        <button class="btn btn-danger btn-sm" type="button"
                                                            onclick="deleteData({{ $variant['id'] }})">
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Variant Image')" />
                            </div>
                            <div class="card-body text-center">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('SN') }}</th>
                                                <th width="30%">{{ __('Color') }}</th>
                                                <th width="10%">{{ __('image') }}</th>
                                                <th width="15%">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($variantImages as $index => $image)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $image->attributeValue?->name }}</td>
                                                    <td>
                                                        <img class="img-fluid w-50" src="{{ asset($image->image) }}"
                                                            alt="">
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm" type="button"
                                                            onclick="deleteImage({{ $image->id }})">
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
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
        $(document).ready(function() {
            'use strict';
        });

        function deleteData(id) {
            var id = id;
            var url = '{{ route('admin.product.product-variant.delete', ':id') }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
            $('#deleteModal').modal('show');
        }

        function deleteImage(id) {
            var url = '{{ route('admin.product.product-variant-image.delete', ':id') }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
            $('#deleteModal').modal('show');
        }
    </script>
@endpush
