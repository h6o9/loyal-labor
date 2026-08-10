@extends('user.layout.app')

@section('title')
    {{ __('Addresses') }} || {{ $setting->app_name }}
@endsection

@section('user-breadcrumb')
    @include('components::breadcrumb', ['title' => __('Addresses'), 'image' => 'Addresses'])
@endsection

@section('user-content')
    <div class="wsus__dashboard_contant">
        <div class="wsus__dashboard_contant_top">
            <div class="wsus__dashboard_heading d-flex justify-content-between">
                <h5>{{ __('Addresses') }}</h5>
                <span class="button-right">
                    <button data-bs-toggle="modal" data-bs-target="#addressModal" type="button"><i class="far fa-plus"></i>
                        {{ __('Add New') }}</button>
                </span>
            </div>
        </div>

        <div class="wsus__dash_order_table">
            <div class="wsus__addresses row">
                @forelse($addresses as $singleAddress)
                    <div class="wsus__product_single_address col-md-6">
                        <div class="text">
                            <p><span>{{ __('Name') }}:</span> {{ $singleAddress->name }}</p>
                            <p><span>{{ __('Email') }}:</span> {{ $singleAddress->email }}</p>
                            <p><span>{{ __('Phone') }}:</span> {{ $singleAddress->phone }}</p>
                            <p><span>{{ __('Country') }}:</span> {{ $singleAddress->country->name ?? '' }}</p>
                            <p><span>{{ __('State') }}:</span> {{ $singleAddress->state->name ?? '' }},</p>
                            <p><span>{{ __('City') }}:</span> {{ $singleAddress->city->name ?? '' }},</p>
                            <p><span>{{ __('Zip') }}:</span> {{ $singleAddress->zip_code ?? '' }}</p>
                            <p><span>{{ __('Street address') }}:</span> {{ $singleAddress->address ?? '' }}</p>
                            <p><span>{{ __('Walk In') }}:</span>
                                {{ $singleAddress->walk_in_customer ? __('Yes') : __('No') }}</p>
                            <p><span>{{ __('Address Type') }}:</span>
                                {{ $singleAddress->type == 'home' ? __('Home') : __('Office') }}</p>
                            <p><span>{{ __('Default') }}:</span>
                                {{ $singleAddress->default == 1 ? __('Yes') : __('No') }}</p>
                            <p><span>{{ __('Status') }}:</span>
                                {{ $singleAddress->status == 1 ? __('Active') : __('Inactive') }}
                            </p>
                        </div>
                        <div class="buttons">
                            <a class="common_btn btn_danger delete_address" data-id="{{ $singleAddress->id }}"
                                href="javascript:void(0)">
                                {{ __('Delete Address') }}
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="wsus__product_single_review">
                        <h4>{{ __('No addresse found yet') }}</h4>
                    </div>
                @endforelse
            </div>
            <div class="row">
                <div class="col-12 wow fadeInUp">
                    {{ $addresses->links('components::pagination') }}
                </div>
            </div>
        </div>
    </div>

    <div class="wsus__delete_modal">
        <div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"
            tabindex="-1">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="wsus__delete_modal">
                            <div class="wsus__delete_modal_text">
                                <h4>{{ __('Are you sure?') }}</h4>
                                <p>{{ __('Do you really want to delete this?') }}</p>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="wsus__delete_modal_btn">
                            <form id="deleteForm" action="" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn" data-bs-dismiss="modal" type="button">{{ __('Cancel') }}</button>
                                <button class="btn btn-danger" type="submit">{{ __('Delete') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wsus__product_modal">
        <div class="modal fade" id="addressModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="wsus__checkout_form" id="address-form"
                            action="{{ route('website.user.address.store') }}" method="POST">
                            @csrf
                            <h4>{{ __('New Address') }}</h4>
                            <div class="row">
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label>{{ __('Name') }}<span class="text-danger">*</span></label>
                                        <input name="name" type="text"
                                            value="{{ old('name', auth()->user()?->name) }}"
                                            placeholder="{{ __('Name') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label>{{ __('Email Address') }}<span class="text-danger">*</span></label>
                                        <input name="email" type="email"
                                            value="{{ old('email', auth()->user()?->email) }}"
                                            placeholder="{{ __('Email') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label>{{ __('Phone') }}<span class="text-danger">*</span></label>
                                        <input name="phone" type="text"
                                            value="{{ old('phone', auth()->user()?->phone) }}"
                                            placeholder="{{ __('Phone') }}">
                                    </div>
                                </div>

                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label for="a_country_id">{{ __('Country Name') }}<span
                                                class="text-danger">*</span></label>
                                        <select class="select_js" id="a_country_id" name="a_country_id">
                                            <option value="" selected disabled>{{ __('Select Country') }}</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label class="type_label" for="a_state_id">{{ __('State Name') }}<span
                                                class="text-danger">*</span></label>
                                        <select class="select_js" id="a_state_id" name="a_state_id">
                                            <option value="" selected disabled>{{ __('Select State') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label class="type_label" for="a_city_id">{{ __('City') }}<span
                                                class="text-danger">*</span></label>
                                        <select class="select_js" id="a_city_id" name="a_city_id">
                                            <option value="" selected disabled>{{ __('Select City') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label>{{ __('Zip / Postal Code') }}<span class="text-danger">*</span></label>
                                        <input name="zip" type="text" placeholder="Zip Code">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label class="type_label">{{ __('Type') }}<span
                                                class="text-danger">*</span></label>
                                        <select class="select_js" name="type">
                                            <option value="">{{ __('Select Type') }}</option>
                                            <option value="home">{{ __('Home') }}</option>
                                            <option value="office">{{ __('Office') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label class="type_label">{{ __('Is Default') }}<span
                                                class="text-danger">*</span></label>
                                        <select class="select_js" name="is_default">
                                            <option value="">{{ __('Select Is Default') }}</option>
                                            <option value="1">{{ __('Yes') }}</option>
                                            <option value="0">{{ __('No') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label class="type_label">{{ __('Visibility') }}<span
                                                class="text-danger">*</span></label>
                                        <select class="select_js" name="status">
                                            <option value="">{{ __('Select Visibility') }}</option>
                                            <option value="1">{{ __('Yes') }}</option>
                                            <option value="0">{{ __('No') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="wsus__checkout_form_input">
                                        <label>{{ __('Street address') }} *</label>
                                        <input name="address" type="text"
                                            placeholder="{{ __('House number and street name') }}">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="common_btn cart_list_btn common_btn_2" data-bs-dismiss="modal"
                            type="button">{{ __('Close') }}</button>
                        <button class="common_btn common_btn_2" form="address-form"
                            type="submit">{{ __('Save') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components::preloader')
@endsection

@push('scripts')
    <script>
        "use strict";

        $(document).on('click', '.delete_address', function() {
            var id = $(this).data('id');
            var url = '{{ route('website.user.address.delete', ':id') }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
            $('#deleteModal').modal('show');
        });

        $(document).on('change', '[name="a_country_id"]', function() {
            var country_id = $(this).val();
            var form = 'address-form';

            $.ajax({
                url: `{{ route('website.get.all.states.by.country', ':country_id') }}`.replace(
                    ':country_id', country_id),
                beforeSend: function() {
                    $('.preloader_area').removeClass('d-none');
                    $(`#${form} [name="a_state_id"]`).prop('disabled', true);
                },
                success: function(response) {
                    $(`#${form} [name="a_state_id"]`).html('');
                    let options =
                        '<option value="" selected disabled>{{ __('Select State') }}</option>';

                    response.data.forEach(function(state) {
                        options += '<option value="' +
                            state.id +
                            '">' + state.name + '</option>';
                    });

                    $(`#${form} [name="a_state_id"]`).html(options).niceSelect('destroy')
                        .niceSelect();
                    $(`#${form} [name="a_state_id"]`).prop('disabled', false);

                    $(`#${form} [name="a_state_id"]`).niceSelect('update');
                },
                error: function(error) {
                    handleError(error);
                },
                complete: function() {
                    $('.preloader_area').addClass('d-none');
                    $(`#${form} [name="a_state_id"]`).prop('disabled', false);
                }
            });
        });

        $(document).on('change', '[name="a_state_id"]', function() {
            var state_id = $(this).val();
            var form = 'address-form';
            $.ajax({
                url: `{{ route('website.get.all.cities.by.state', ':state_id') }}`.replace(':state_id',
                    state_id),

                beforeSend: function() {
                    $('.preloader_area').removeClass('d-none');
                    $(`#${form} [name="a_city_id"]`).prop('disabled', true);
                },
                success: function(response) {
                    $(`#${form} [name="a_city_id"]`).html('');

                    let options =
                        '<option value="" selected disabled>{{ __('Select City') }}</option>';

                    response.data.forEach(function(city) {
                        options += '<option value="' +
                            city.id +
                            '">' + city.name + '</option>';
                    });

                    $(`#${form} [name="a_city_id"]`).html(options).niceSelect(
                            'destroy')
                        .niceSelect();
                    $(`#${form} [name="a_city_id"]`).prop('disabled', false);

                    $(`#${form} [name="a_city_id"]`).niceSelect('update');

                    $('.preloader_area').addClass('d-none');
                },
                error: function(error) {
                    handleError(error);
                },
                complete: function() {
                    $('.preloader_area').addClass('d-none');
                    $(`#${form} [name="a_city_id"]`).prop('disabled', false);
                }
            });
        });
    </script>
@endpush
