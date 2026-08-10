@extends('admin.master_layout')

@section('title')
    <title>{{ __('Shop Details') }} | {{ $user->seller->shop_name ?? '' }}</title>
@endsection

@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Shop Details') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Shop Details') => '#',
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
                                    <h4>{{ __('Total Sale/Earning') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalSoldProduct }} / {{ defaultCurrency($totalEarning) }}
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
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Shop Profile') }}</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.manage-seller.shop.profile.store', $seller->id) }}"
                                    enctype="multipart/form-data" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>{{ __('Current Banner Image') }}</label>
                                            <div>
                                                <img id="preview-banner-img"
                                                    src="{{ $seller->banner_image ? asset($seller->banner_image) : asset($setting->default_avatar) }}"
                                                    alt="" width="300px">
                                            </div>
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('New Banner Image') }}</label>
                                            <input class="form-control" name="banner_image" type="file">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Shop Name') }} <span class="text-danger">*</span></label>
                                            <input class="form-control" name="shop_name" type="text"
                                                value="{{ $seller->shop_name }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Email') }} <span class="text-danger">*</span></label>
                                            <input class="form-control" name="email" type="email"
                                                value="{{ $seller->email }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Phone') }}</label>
                                            <input class="form-control" name="phone" type="text"
                                                value="{{ $seller->phone }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Current Logo') }}</label>
                                            <div>
                                                <img id="preview-logo-img"
                                                    src="{{ asset($seller->logo_image ? $seller->logo_image : $setting->default_avatar) }}"
                                                    alt="" width="300px">
                                            </div>
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('New Logo Image') }}</label>
                                            <input class="form-control" name="logo_image" type="file">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Opens at') }}</label>
                                            <input class="form-control clockpicker" name="opens_at" data-align="top"
                                                data-autoclose="true" type="time" value="{{ $seller->open_at }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Closed at') }}</label>
                                            <input class="form-control clockpicker" name="closed_at" data-align="top"
                                                data-autoclose="true" type="time" value="{{ $seller->closed_at }}">

                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Address') }}</label>
                                            <input class="form-control" name="address" type="text"
                                                value="{{ $seller->address }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Seo Title') }}</label>
                                            <input class="form-control" name="seo_title" type="text"
                                                value="{{ $seller->seo_title }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Seo Description') }}</label>
                                            <textarea class="form-control text-area-5" id="" name="seo_description" cols="30" rows="10">{{ $seller->seo_description }}</textarea>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="status">{{ __('Status') }}</label>
                                            <select class="form-select select2" id="status" name="status">
                                                <option value="1" @selected($seller->status == 1)>
                                                    {{ __('Active') }}</option>
                                                <option value="0" @selected($seller->status == 0)>
                                                    {{ __('Inactive') }}</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="is_verified">{{ __('KYC Verified?') }}</label>
                                            <select class="form-select select2" id="is_verified" name="is_verified">
                                                <option value="1" @selected($seller->is_verified == 1)>
                                                    {{ __('Yes') }}</option>
                                                <option value="0" @selected($seller->is_verified == 0)>
                                                    {{ __('No') }}</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="is_featured">{{ __('Is Featured') }}</label>
                                            <select class="form-select select2" id="is_featured" name="is_featured">
                                                <option value="1" @selected($seller->is_featured == 1)>
                                                    {{ __('Yes') }}</option>
                                                <option value="0" @selected($seller->is_featured == 0)>
                                                    {{ __('No') }}</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="top_rated">{{ __('Is Top Rated') }}</label>
                                            <select class="form-select select2" id="top_rated" name="top_rated">
                                                <option value="1" @selected($seller->top_rated == 1)>
                                                    {{ __('Yes') }}</option>
                                                <option value="0" @selected($seller->top_rated == 0)>
                                                    {{ __('No') }}</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button class="btn btn-primary">{{ __('Save Changes') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light">
                                <div>
                                    <a class="btn btn-sm btn-success"
                                        href="{{ route('admin.manage-seller.send-verify-link', $user->id) }}">
                                        {{ __('Send Shop Verify Link') }}
                                    </a>
                                </div>
                                <div>
                                    <a class="btn btn-warning btn-sm"
                                        href="{{ route('admin.customer-show', $user->id) }}"><i
                                            class="fas fa-key me-1"></i>
                                        {{ __('Change Password') }}</a>
                                </div>
                                <div>
                                    <a class="btn btn-sm btn-info"
                                        href="{{ route('admin.seller.products.index', ['vendor_id' => $user->seller->id ?? 404]) }}"><i
                                            class="fas fa-boxes me-1"></i>
                                        {{ __('Products') }}</a>
                                </div>
                                <div>
                                    <a class="btn btn-sm btn-info"
                                        href="{{ route('admin.orders', ['vendor_id' => $user->seller->id ?? 404]) }}"><i
                                            class="fas fa-shopping-cart me-1"></i>
                                        {{ __('Orders') }}</a>
                                </div>
                                <div>
                                    <a class="btn btn-sm btn-info"
                                        href="{{ route('admin.wallet-history', ['vendor_id' => $user->seller->id ?? 404]) }}"><i
                                            class="fas fa-dollar-sign me-1"></i>
                                        {{ __('Wallet History') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-12 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Edit Profile') }}</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.manage-seller.profile.store', $user->id) }}"
                                    enctype="multipart/form-data" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>{{ __('User Image') }}</label>
                                            <div>
                                                <img id="preview-user-img"
                                                    src="{{ asset($user->image ? $user->image : $setting->default_user_image) }}"
                                                    alt="" width="300px">
                                            </div>
                                        </div>

                                        <div class="form-group col-12">
                                            <label class="form-label" for="image">{{ __('New Image') }}</label>
                                            <input class="form-control" id="image" name="image" type="file"
                                                accept="image/*">
                                        </div>

                                        <div class="form-group col-6">
                                            <label class="form-label" for="name">{{ __('Name') }}<span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control" id="name" name="name" type="text"
                                                value="{{ $user->name }}">
                                        </div>

                                        <div class="form-group col-6">
                                            <label class="form-label" for="email">{{ __('Email') }} <span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control" id="email" name="email" type="email"
                                                value="{{ $user->email }}">
                                        </div>

                                        <div class="form-group col-6">
                                            <label class="form-label" for="phone">{{ __('Phone') }}</label>
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
                                            <label class="form-label" for="gender">{{ __('Gender') }}</label>
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
                                            <label class="form-label" for="country_id">{{ __('Country') }}</label>
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
                                            <label class="form-label" for="state_id">{{ __('State') }}</label>
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
                                            <label class="form-label" for="city_id">{{ __('City') }}</label>
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
                                            <label class="form-label" for="zip_code">{{ __('Zip Code') }}</label>
                                            <input class="form-control" id="zip_code" name="zip_code" type="text"
                                                value="{{ $user->zip_code }}">
                                        </div>

                                        <div class="form-group col-6">
                                            <label class="form-label" for="address">{{ __('Address') }}</label>
                                            <input class="form-control" id="address" name="address" type="text"
                                                value="{{ $user->address }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label class="form-label" for="about">{{ __('About Me') }}</label>
                                            <textarea class="form-control text-area-5" id="about" name="bio" rows="3"
                                                placeholder="{{ __('Write Something') }}">{{ old('bio', $user->bio) }}</textarea>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="status">{{ __('Status') }}</label>
                                            <select class="form-select select2" id="status" name="status">
                                                <option value="1" @selected($user->status == 'active')>
                                                    {{ __('Active') }}</option>
                                                <option value="0" @selected($user->status == 'inactive')>
                                                    {{ __('Inactive') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button class="btn btn-lg btn-success"
                                                type="submit">{{ __('Update') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light">
                                @if (!$user->email_verified_at)
                                    @adminCan('customer.update')
                                        <x-admin.button data-bs-toggle="modal" data-bs-target="#verifyModal"
                                            variant="success" :text="__('Send Verify Link to Mail')" />
                                    @endadminCan
                                @endif

                                @adminCan('customer.update')
                                    <x-admin.button data-bs-toggle="modal" data-bs-target="#sendMailModal"
                                        :text="__('Send Mail To Customer')" />

                                    @if ($user->is_banned == 'yes')
                                        <x-admin.button data-bs-toggle="modal" data-bs-target="#bannedModal"
                                            variant="warning" :text="__('Remove From Banned')" />
                                    @else
                                        <x-admin.button data-bs-toggle="modal" data-bs-target="#bannedModal"
                                            variant="warning" :text="__('Ban Customer')" />
                                    @endif
                                @endadminCan

                                @adminCan('customer.delete')
                                    <x-admin.button data-bs-toggle="modal" data-bs-target="#deleteModal" variant="danger"
                                        :text="__('Delete Account')" />
                                @endadminCan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @adminCan('customer.update')
        <!-- Start Banned modal -->
        <div class="modal fade" id="bannedModal" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
            tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Banned Request Mail') }}</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form action="{{ route('admin.send-banned-request', $user->id) }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <x-admin.form-input id="banned_user_subject" name="subject"
                                        value="{{ old('subject') }}" label="{{ __('Subject') }}"
                                        placeholder="{{ __('Enter Subject') }}" required="true" />
                                </div>

                                <div class="form-group">
                                    <x-admin.form-textarea id="banned_user_description" name="description"
                                        value="{{ old('description') }}" label="{{ __('Description') }}"
                                        placeholder="{{ __('Enter Description') }}" maxlength="1000" required="true" />
                                </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-admin.button data-bs-dismiss="modal" variant="danger" text="{{ __('Close') }}" />
                        <x-admin.button type="submit" text="{{ __('Send Mail') }}" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Banned modal -->

        <!-- Start Verify modal -->
        <div class="modal fade" id="verifyModal" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
            tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Send verify link to customer mail') }}</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <p>{{ __('Are you sure want to send verify link to customer mail?') }}</p>

                            <form action="{{ route('admin.send-verify-request', $user->id) }}" method="POST">
                                @csrf

                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-admin.button data-bs-dismiss="modal" variant="danger" text="{{ __('Close') }}" />
                        <x-admin.button type="submit" text="{{ __('Send Mail') }}" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Verify modal -->

        <!-- Start Send Mail modal -->
        <div class="modal fade" id="sendMailModal" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
            tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Send Mail To Customer') }}</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form action="{{ route('admin.send-mail-to-customer', $user->id) }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <x-admin.form-input id="mail_send_subject" name="subject" value="{{ old('subject') }}"
                                        label="{{ __('Subject') }}" placeholder="{{ __('Enter Subject') }}"
                                        required="true" />
                                </div>

                                <div class="form-group">
                                    <x-admin.form-textarea id="mail_send_description" name="description"
                                        value="{{ old('description') }}" required="true" label="{{ __('Description') }}"
                                        placeholder="{{ __('Enter Description') }}" maxlength="1000" />
                                </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-admin.button data-bs-dismiss="modal" variant="danger" text="{{ __('Close') }}" />
                        <x-admin.button type="submit" text="{{ __('Send Mail') }}" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Send Mail modal -->
    @endadminCan
@endsection

@push('js')
    <script>
        @adminCan('customer.delete')

        function deleteData(id) {
            $("#deleteForm").attr("action", "{{ url('/admin/customer-delete/') }}" + "/" + id)
        }
        @endadminCan

        $(document).ready(function() {
            $(document).on('change', '[name="country_id"]', function() {
                var country_id = $(this).val();

                $.ajax({
                    url: `{{ route('website.get.all.states.by.country', ':country_id') }}`
                        .replace(':country_id', country_id),
                    beforeSend: function() {
                        $('.preloader_area').removeClass('d-none');
                        $(`[name="state_id"]`).html(
                            '<option value="" selected disabled>{{ __('Select State') }}</option>'
                        );

                        $(`[name="state_id"]`).prop('disabled', true);
                        $(`[name="city_id"]`).prop('disabled', true);
                    },
                    success: function(response) {
                        $(`[name="state_id"]`).html('');
                        $(`[name="city_id"]`).html('');
                        $(`[name="city_id"]`).html(
                            `<option value="" selected disabled>{{ __('Select City') }}</option>`
                        );
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
                        $(`[name="city_id"]`).prop('disabled', false);
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

            $(document).on('change', '[name="banner_image"]', function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#preview-banner-img').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            $(document).on('change', '[name="logo_image"]', function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#preview-logo-img').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            $(document).on('change', '[name="image"]', function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#preview-user-img').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
@endpush
