@extends('seller.layouts.master')

@section('title')
    <title>{{ __('My Profile') }}</title>
@endsection

@section('seller-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Seller Profile') }}" :list="[
                'Dashboard' => route('seller.dashboard'),
                'My Profile' => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-5">
                    <div class="col-md-3">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-coins"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Product Sale') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalSoldProduct }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <a href="{{ route('seller.my-withdraw.index') }}">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-danger">
                                    <i class="far fa-newspaper"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Total Withdraw') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ defaultCurrency($totalWithdraw) }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="far fa-file"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Current Balance') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ defaultCurrency($user->wallet_balance) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('seller.product.index') }}">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-success">
                                    <i class="fas fa-circle"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Total Products') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ $seller?->products->count() ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-5">
                        <div class="card profile-widget">
                            <div class="profile-widget-header">
                                <img class="rounded-circle profile-widget-picture"
                                    src="{{ asset($user->image ? $user->image : $setting->default_user_image) }}"
                                    alt="image">
                                <div class="profile-widget-items">
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">{{ __('Joined at') }}</div>
                                        <div class="profile-widget-item-value">{{ formattedDate($user->created_at) }}
                                        </div>
                                    </div>
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">{{ __('Balance') }}</div>
                                        <div class="profile-widget-item-value">
                                            {{ defaultCurrency($user->wallet_balance) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-widget-description">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <td>{{ __('Name') }}</td>
                                            <td>{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Email') }}</td>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Phone') }}</td>
                                            <td>{{ $user->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('User Status') }}</td>
                                            <td>
                                                @if ($user->status == 'active')
                                                    <span class="badge badge-success">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>{{ __('Shop Status') }}</td>
                                            <td>
                                                @if ($seller->is_verified == 1)
                                                    <span class="badge badge-success">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h1>{{ __('Seller Action') }}</h1>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <a class="btn btn-success m-1"
                                                    href="{{ route('seller.shop-profile') }}">{{ __('Go To Shop') }}</a>
                                            </div>
                                            <div class="col-md-4">
                                                <a class="btn btn-primary m-1"
                                                    href="{{ route('seller.product-review') }}">{{ __('My Reviews') }}</a>
                                            </div>
                                            <div class="col-md-4">
                                                <a class="btn btn-warning m-1"
                                                    href="{{ route('seller.change-password') }}">{{ __('Change Password') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-12 col-lg-7">
                        <div class="card">
                            <form action="{{ route('seller.update-seller-profile') }}" enctype="multipart/form-data"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-header">
                                    <h4>{{ __('Edit Profile') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label class="form-label" for="image">{{ __('New Image') }}</label>
                                            <input class="form-control" id="image" name="image" type="file"
                                                accept="image/*">
                                        </div>

                                        <div class="form-group col-6">
                                            <label class="form-label" for="name">{{ __('Name') }} <span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control" id="name" name="name" type="text"
                                                value="{{ $user->name }}">
                                        </div>

                                        <div class="form-group col-6">
                                            <label class="form-label" for="email">{{ __('Email') }} <span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control" id="email" name="email" type="email"
                                                value="{{ $user->email }}" readonly>
                                        </div>

                                        <div class="form-group col-6">
                                            <label class="form-label" for="phone">{{ __('Phone') }} <span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control" id="phone" name="phone" type="text"
                                                value="{{ $user->phone }}">
                                        </div>

                                        <div class="form-group col-6">
                                            <label class="form-label" for="birthday">{{ __('Birthday') }}</label>
                                            <input class="form-control" id="birthday" name="birthday" type="date"
                                                value="{{ old('birthday', $user->birthday) }}"
                                                placeholder="{{ __('Enter birthday date') }}">
                                        </div>
                                        <div class="form-group col-6">
                                            <label class="form-label" for="gender">{{ __('Gender') }}<span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select select2" id="gender" name="gender">
                                                <option value="">{{ __('Select gender') }}</option>
                                                <option value="male" @selected($user->gender == 'male')>
                                                    {{ __('Male') }}</option>
                                                <option value="female" @selected($user->gender == 'female')>
                                                    {{ __('Female') }}</option>
                                                <option value="other" @selected($user->gender == 'other')>
                                                    {{ __('Other') }}</option>
                                            </select>

                                        </div>

                                        <div class="form-group col-6">
                                            <label class="form-label" for="country_id">{{ __('Country') }} <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select select2" id="country_id" name="country_id">
                                                <option value="">{{ __('Select Country') }}</option>
                                                @if ($user->country_id)
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}"
                                                            {{ $user->country_id == $country->id ? 'selected' : '' }}>
                                                            {{ $country->name }}</option>
                                                    @endforeach
                                                @else
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}">{{ $country->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div class="form-group col-6">
                                            <label class="form-label" for="state_id">{{ __('State') }} </label>
                                            <select class="form-select select2" id="state_id" name="state_id">
                                                <option value="">{{ __('Select State') }}</option>
                                                @if ($user->state_id != 0)
                                                    @foreach ($states as $state)
                                                        <option value="{{ $state->id }}"
                                                            {{ $user->state_id == $state->id ? 'selected' : '' }}>
                                                            {{ $state->name }}</option>
                                                    @endforeach
                                                @else
                                                    @foreach ($states as $state)
                                                        <option value="{{ $state->id }}">{{ $state->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div class="form-group col-6">
                                            <label class="form-label" for="city_id">{{ __('City') }} </label>
                                            <select class="form-select select2" id="city_id" name="city_id">
                                                <option value="">{{ __('Select City') }}</option>
                                                @if ($user->city_id != 0)
                                                    @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}"
                                                            {{ $user->city_id == $city->id ? 'selected' : '' }}>
                                                            {{ $city->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div class="form-group col-6">
                                            <label class="form-label" for="zip_code">{{ __('Zip Code') }} <span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control" id="zip_code" name="zip_code" type="text"
                                                value="{{ $user->zip_code }}">
                                        </div>

                                        <div class="form-group col-6">
                                            <label class="form-label" for="address">{{ __('Address') }} <span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control" id="address" name="address" type="text"
                                                value="{{ $user->address }}">
                                        </div>
                                        <div class="form-group col-12">
                                            <label class="form-label" for="about">{{ __('About Me') }}</label>
                                            <textarea class="form-control text-area-5" id="about" name="bio" rows="3"
                                                placeholder="{{ __('Write Something') }}">{{ old('bio', $user->bio) }}</textarea>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
                                </div>

                            </form>
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
            $(document).on('change', '[name="country_id"]', function() {
                var country_id = $(this).val();

                $.ajax({
                    url: `{{ route('website.get.all.states.by.country', ':country_id') }}`.replace(
                        ':country_id', country_id),
                    beforeSend: function() {
                        $('.preloader_area').removeClass('d-none');
                        $(`[name="state_id"]`).html(
                            '<option value="" selected disabled>{{ __('Select State') }}</option>'
                        );

                        $(`[name="state_id"]`).prop('disabled', true);
                    },
                    success: function(response) {
                        $(`[name="state_id"]`).html('');
                        let options =
                            '<option value="" selected disabled>{{ __('Select State') }}</option>';

                        response.data.forEach(function(state) {
                            options += '<option value="' +
                                state.id +
                                '">' + state.name + '</option>';
                        })
                        $(`[name="state_id"]`).html(options);
                    },
                    error: function(error) {
                        handleError(error);
                    },
                    complete: function() {
                        $('.preloader_area').addClass('d-none');
                        $(`[name="state_id"]`).prop('disabled', false);
                    }
                });
            });

            $(document).on('change', '[name="state_id"]', function() {
                var state_id = $(this).val();
                $.ajax({
                    url: `{{ route('website.get.all.cities.by.state', ':state_id') }}`.replace(
                        ':state_id', state_id),
                    ,
                    beforeSend: function() {
                        $('.preloader_area').removeClass('d-none');
                        $(`[name="city_id"]`).html(
                            '<option value="" selected disabled>{{ __('Select City') }}</option>'
                        );

                        $(`[name="city_id"]`).prop('disabled', true);
                    },
                    success: function(response) {
                        $(`[name="city_id"]`).html('');

                        let options =
                            '<option value="" selected disabled>{{ __('Select City') }}</option>';

                        response.data.forEach(function(city) {
                            options += '<option value="' +
                                city.id +
                                '">' + city.name + '</option>';
                        })
                        $(`[name="city_id"]`).html(options);
                    },
                    error: function(error) {
                        handleError(error);
                    },
                    complete: function() {
                        $('.preloader_area').addClass('d-none');
                        $(`[name="city_id"]`).prop('disabled', false);
                    }
                });
            });

        });
    </script>
@endpush
