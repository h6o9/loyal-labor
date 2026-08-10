@extends('seller.layouts.master')

@section('title')
    <title>{{ __('Create Product Variant') }}</title>
@endsection

@section('seller-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Create Product Variant') }}" :list="[
                __('Dashboard') => route('seller.dashboard'),
                __('Product List') => route('seller.product.index'),
                __('Product Variant List') => route('seller.product.product-variant', $product->id),
                __('Create Product Variant') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Create Product Variant') }}</h4>
                                <div>
                                    <a class="btn btn-primary"
                                        href="{{ route('seller.product.product-variant', $product->id) }}"><i
                                            class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('seller.product.product-variant.store', $product->id) }}"
                                    method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="name">{{ __('Attribute') }}<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control select2" id="attribute" name="attribute"
                                                        multiple>
                                                        <option value="">{{ __('Select Attribute') }}</option>
                                                        @foreach ($attributes as $attribute)
                                                            <option value="{{ $attribute->id }}">{{ $attribute->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    @error('attribute')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="attributes_values row">

                                                </div>

                                                <div class="attributes_variations row" style="padding: 0 15px;">

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="color-image mt-5">

                                    </div>
                                    <div class="row">
                                        <div class="text-center offset-md-2 col-md-8">
                                            <x-admin.save-button :text="__('Save')">
                                            </x-admin.save-button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('components.admin.preloader')
@endsection

@push('js')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $('[name="attribute"]').on('change', function() {

                    var attribute = $(this).val();
                    if (attribute) {
                        $('.preloader_area').removeClass('d-none');
                        $.ajax({
                            url: "{{ route('seller.attribute.get.value') }}",
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                attribute: attribute,
                                product_id: "{{ $product->id }}"
                            },
                            success: function(response) {
                                $('.attributes_values').html('');
                                $.each(response.data, function(index, attribute) {
                                    let html = `
                                    <div class="col-4 mb-2">
                                        <input type="text" name='choice[]' value="${attribute.name}" class="form-control" readonly>
                                    </div>
                                    <div class="col-8 mb-2">
                                        <select name="choice_options_${attribute.id}[]" class="form-control select2 attr-multi-value" multiple data-attr-slug="${attribute.slug}" data-attr-id="${attribute.id}"=>
                                `;
                                    $.each(attribute.values, function(index,
                                        value) {
                                        html +=
                                            `<option value="${value.name}" data-id=${value.id}>${value.name}</option>`;
                                    });

                                    html += `
                                        </select>
                                    </div>
                                `;

                                    // Append the generated HTML to a container
                                    $('.attributes_values').append(html);
                                    $('.attributes_variations').html('');
                                });

                                // After appending all select elements, initialize Select2
                                $('.select2').select2();
                                $('.preloader_area').addClass('d-none')
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                console.log(xhr);
                                $('.preloader_area').addClass('d-none')
                            }
                        });

                    }

                })
                $(document).on('change', '.attr-multi-value', function() {

                    const attrSlug = $(this).data('attr-slug');
                    const attrVal = $(this).val();

                    // Initialize an empty array to store selected values
                    var selectedValues = [];

                    // Loop through each select element
                    $('.attr-multi-value').each(function(index, select) {
                        // Get the selected options for the current select element
                        var selectedOptions = $(select).val();

                        // If options are selected, add them to the selectedValues array
                        if (selectedOptions && selectedOptions.length > 0) {
                            selectedValues.push(selectedOptions);
                        }
                    });

                    if (selectedValues.length == 0) {
                        $('.attributes_variations').html('');
                        return;
                    }

                    // color and color image
                    let colorImage = '';

                    // Create a variation table HTML
                    var variationTableHTML =
                        '<table class="table border"><thead><tr><th>Variant</th><th>Selling Price</th><th>Offer Type</th><th>Offer Price</th><th>Is Default</th><th>Stock/{{ optional($product->unit)->name ?: __('Unit') }}</th><th>SKU</th></tr></thead><tbody>';
                    // Generate rows for each combination of selected values
                    selectedValues = cartesian(selectedValues);
                    $.each(selectedValues, function(index, combination) {

                        // Create a row for the combination
                        variationTableHTML += '<tr>';
                        variationTableHTML += '<td>' + combination.join('-') +
                            `<input type="hidden" name="variant[]" value="${combination.join('-')}">` +
                            '</td>'; // Variant column (joined if there are multiple)
                        variationTableHTML +=
                            `<td >
                            <div class="d-flex justify-content-between align-items-center my-3">
                                <input type="text" class="form-control selling-price" name="price[${index}]" placeholder="{{ __('Enter Selling Price') }}" value="{{ $product->price }}">
                            </div>
                        </td>
                        <td >
                            <div class="d-flex justify-content-between align-items-center my-3">
                                <select class="form-select offer-type" data-index="${index}" name="offer_Type[${index}]" placeholder="{{ __('Select Offer Price Type') }}">
                                    <option value="">{{ __('Select Offer Price Type') }}</option>
                                    <option value="fixed" selected>{{ __('Fixed') }}</option>
                                    <option value="percentage">{{ __('Percentage') }}</option>
                                </select>
                            </div>
                        </td>
                        <td >
                            <div class="d-flex justify-content-between align-items-center my-3">
                                <input type="number" data-index="${index}" class="form-control offer-price" name="offer_price[${index}]" placeholder="Enter Offer Price" value="">
                            </div>
                        </td>
                        <td >
                            <div class="variation_radio d-flex justify-content-between align-items-center my-3">
                                <input type="radio" class="form-radio is-default" name="is_default" ${index== 0 ? 'checked' :''} value="${index}">
                            </div>
                        </td>
                        <td >
                            <div class="d-flex justify-content-between align-items-center my-3">
                                <input type="number" class="form-control stock_qty" name="stock_qty[${index}]" value="" placeholder="{{ __('Enter Stock Qty') }}">
                            </div>
                        </td>
                    `;
                        const sku = "{{ $product->sku }}";
                        // Selling Price column with input
                        variationTableHTML +=
                            `<td><input type="text" class="form-control sku" name="sku[]" placeholder="Enter SKU" value="${combination.join('-').toUpperCase()}-${sku.toUpperCase()}"></td>`; // SKU column with input
                        variationTableHTML += '</tr>';
                    });

                    variationTableHTML += '</tbody></table>';

                    // Append the variation table HTML to a container
                    $('.attributes_variations').html(variationTableHTML);

                    if (attrSlug == 'color' || attrSlug == 'colors') {
                        colorImage += `<div class="row">
                                            <div class="col-md-12 mb-2">
                                                <h4>Select Color Image</h4>
                                            </div>
                                        </div>`

                        $.each(attrVal, function(index, val) {
                            colorImage += `
                                <div class="row">
                                    <div class="col-md-6 row">
                                        <div class="form-group">
                                            <input type="text" name="color_image[]" class="form-control"
                                                value="${val}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="file" name="image[]" class="form-control"
                                                accept="image/*">
                                        </div>
                                    </div>
                                </div>`
                        })

                        $('.color-image').html(colorImage)
                    }
                });

                // reset table if no attribute selected
                $(document).on('change', '#attribute', function() {
                    if ($(this).val() == '') {
                        $('.attributes_variations').html('');
                        $('.attributes_values').html('');
                    }
                });

                $(document).on('change', '.offer-type', function() {
                    const index = $(this).data('index');

                    if ($(this).val() == 'percentage') {
                        $('.offer-price').eq(index).attr('placeholder', 'Enter Percentage');
                    } else {
                        $('.offer-price').eq(index).attr('placeholder', 'Enter Offer Price');
                    }
                });
            });

            function cartesian(arrays) {
                var result = [];
                var max = arrays.length - 1;

                function helper(arr, i) {
                    for (var j = 0, l = arrays[i].length; j < l; j++) {
                        var a = arr.slice(0); // clone arr
                        a.push(arrays[i][j]);
                        if (i == max)
                            result.push(a);
                        else
                            helper(a, i + 1);
                    }
                }
                helper([], 0);
                return result;
            }

        })(jQuery);
    </script>
@endpush
