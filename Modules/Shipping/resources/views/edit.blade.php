@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Shipping Rule') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit Shipping Rule') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Shipping Rule List') => route('admin.shipping.index'),
                __('Edit Shipping Rule') => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4 class="section_title">{{ __('Edit Shipping Rule') }}</h4>
                                <div>
                                    <a class="btn btn-primary" href="{{ route('admin.shipping.index') }}"><i
                                            class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <form class="Edit_flash_sales_table" action="{{ route('admin.shipping.update', $rule->id) }}"
                            method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-8 offset-2">
                                                    <div class="form-group">
                                                        <label for="name">{{ __('Name') }}<span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="name" name="name"
                                                            type="text" value="{{ old('name', $rule->name) }}">
                                                        @error('name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-8 offset-2">
                                                    <div class="form-group">
                                                        <label for="type">{{ __('Type') }}<span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-select" id="type" name="type">
                                                            <option value=""
                                                                {{ $rule->type == null ? 'selected' : '' }}>
                                                                {{ __('Select Type') }}</option>
                                                            <option data-unit="{{ currency_icon() }}"
                                                                value="based_on_price"
                                                                {{ old('type', $rule->type) == 'based_on_price' ? 'selected' : '' }}>
                                                                {{ __('Based on order\'s total amount') }}</option>
                                                            <option data-unit="g" value="based_on_weight"
                                                                {{ old('type', $rule->type) == 'based_on_weight' ? 'selected' : '' }}>
                                                                {{ __('Based on order\'s total weight (g)') }}</option>
                                                        </select>
                                                        @error('type')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-8 offset-2">
                                                    <div
                                                        class="rule-from-to-inputs {{ $rule->type == 'based_on_price' || $rule->type == 'based_on_weight' ? '' : 'd-none' }}">
                                                        <div class="mb-3 position-relative">
                                                            <label class="form-label rule-from-to-label"></label>

                                                            <div class="d-flex align-items-center gap-3">
                                                                <div class="w-100">
                                                                    <div class="input-group input-group-flat">
                                                                        <span
                                                                            class="input-group-text unit-item-label">{{ $rule->type == 'based_on_price' ? currency_icon() : 'g' }}</span>
                                                                        <input
                                                                            class="form-control input-mask-number input-sync-item"
                                                                            name="from" data-target=".from-value-label"
                                                                            type="text"
                                                                            value="{{ old('from', $rule->from) }}">
                                                                    </div>
                                                                </div>
                                                                <span>-</span>
                                                                <div class="w-100">
                                                                    <div class="input-group input-group-flat">
                                                                        <span
                                                                            class="input-group-text unit-item-label">{{ $rule->type == 'based_on_price' ? currency_icon() : 'g' }}</span>
                                                                        <input
                                                                            class="form-control input-mask-number input-sync-item input-to-value-field"
                                                                            name="to" data-target=".to-value-label"
                                                                            type="text"
                                                                            value="{{ old('to', $rule->to) }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 offset-2">
                                                    <div class="form-group">
                                                        <label for="price">{{ __('Shipping Fee') }}<span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="price" name="price"
                                                            type="text" value="{{ old('price', $rule->price) }}">
                                                        @error('price')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-8 offset-2">
                                                    <div class="form-group">
                                                        <x-admin.form-switch name="status" label="{{ __('Status') }}"
                                                            :checked="$rule->status" />
                                                    </div>
                                                </div>
                                                <div class="col-md-8 offset-2">
                                                    <div class="form-group">
                                                        <x-admin.form-switch name="location"
                                                            label="{{ __('Add Location') }}" :checked="$rule->items" />
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-md-8 offset-2 country-list {{ $rule->items ? '' : 'd-none' }}">
                                                    <div class="form-group">
                                                        <label for="country_id">{{ __('Country') }}</label>
                                                        <select class="form-control select2" id="country_id"
                                                            name="country_id[]" multiple>
                                                            <option value="" disabled>{{ __('Select Country') }}
                                                            </option>
                                                            @foreach ($countries as $country)
                                                                <option value="{{ $country->id }}"
                                                                    {{ in_array($country->id, $rule->items?->country_id ?? []) ? 'selected' : '' }}>
                                                                    {{ $country->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-md-8 offset-2 state-list {{ $rule->items?->country_id ? '' : 'd-none' }}">
                                                    <div class="form-group">
                                                        <label for="state_id">{{ __('State') }}</label>
                                                        <select class="form-control select2" id="state_id"
                                                            name="state_id[]" multiple>
                                                            @foreach ($states as $state)
                                                                <option value="{{ $state->id }}"
                                                                    {{ in_array($state->id, $rule->items?->state_id ?? []) ? 'selected' : '' }}>
                                                                    {{ $state->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-md-8 offset-2 city-list {{ $rule->items?->state_id ? '' : 'd-none' }}">
                                                    <div class="form-group">
                                                        <label for="city_id">{{ __('City') }}</label>
                                                        <select class="form-control select2" id="city_id"
                                                            name="city_id[]" multiple>
                                                            @foreach ($cities as $city)
                                                                <option value="{{ $city->id }}"
                                                                    {{ in_array($city->id, $rule->items?->city_id ?? []) ? 'selected' : '' }}>
                                                                    {{ $city->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 offset-2">
                                                    <x-admin.save-button :text="__('Save')">
                                                    </x-admin.save-button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <x-admin.preloader />
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            'use strict';

            $('#type').on('change', function() {
                var type = $(this).val();
                const selected = $(this).find('option:selected');
                const unit = selected.data('unit');
                const selectedText = selected.text();
                if (!$('.rule-from-to-inputs').hasClass('d-none')) {
                    $('.rule-from-to-inputs').addClass('d-none');
                }
                if (type == 'based_on_weight') {
                    $('.rule-from-to-inputs').removeClass('d-none');
                } else if (type == 'based_on_price') {
                    $('.rule-from-to-inputs').removeClass('d-none');
                }
                $('.rule-from-to-label').text(selectedText);
                $('.unit-item-label').text(unit);
            })

            $('[name="location"]').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.country-list').removeClass('d-none');
                } else {
                    $('.country-list').addClass('d-none');
                    $('.state-list').addClass('d-none');
                    $('.city-list').addClass('d-none');
                }
            })

            $('[name="country_id[]"]').on('change', function() {
                $('.preloader_area').removeClass('d-none');
                var country_id = $(this).val();
                $.ajax({
                    url: "{{ route('admin.get.all.states.by.country', ':id') }}".replace(':id',
                        country_id),
                    success: function(response) {
                        $('[name="state_id[]"]').html('');
                        $('[name="state_id[]"]').append(
                            '<option value="" disabled>{{ __('Select State') }}</option>'
                        );
                        response.data.forEach(function(state) {
                            $('[name="state_id[]"]').append('<option value="' + state
                                .id +
                                '">' + state.name + '</option>');
                        })
                        $('.preloader_area').addClass('d-none');
                        $('.state-list').removeClass('d-none');
                    },
                    error: function(err) {
                        $('.preloader_area').addClass('d-none');
                        handleFetchError(err)
                    }
                })
            })

            $('[name="state_id[]"]').on('change', function() {
                $('.preloader_area').removeClass('d-none');
                var state_id = $(this).val();
                $.ajax({
                    url: "{{ route('admin.get.all.cities.by.state', ':id') }}".replace(':id',
                        state_id),

                    success: function(response) {
                        $('[name="city_id[]"]').html('');
                        $('[name="city_id[]"]').append(
                            '<option value="" disabled>{{ __('Select City') }}</option>'
                        );
                        response.data.forEach(function(city) {
                            $('[name="city_id[]"]').append('<option value="' + city.id +
                                '">' + city.name + '</option>');
                        })
                        $('.preloader_area').addClass('d-none');
                        $('.city-list').removeClass('d-none');
                    },
                    error: function(err) {
                        $('.preloader_area').addClass('d-none');
                        handleFetchError(err)
                    }
                })
            })
        })
    </script>
@endpush
