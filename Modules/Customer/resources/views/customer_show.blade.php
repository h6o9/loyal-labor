@extends('admin.master_layout')
@section('title')
    <title>{{ __('Customer Details') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Customer Details') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Customer Details') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card shadow">
                            <img class="profile_img w-100"
                                src="{{ !empty($user?->image) ? asset($user?->image) : asset($setting->default_user_image) }}">

                            <div class="container my-3">
                                <h4>{{ html_decode($user->name) }}</h4>

                                @if ($user->phone)
                                    <p class="title">{{ html_decode($user->phone) }}</p>
                                @endif

                                <p class="title">{{ html_decode($user->email) }}</p>

                                <p class="title">{{ __('Joined') }} : {{ formattedDateTime($user->created_at) }}</p>

                                @if ($user->is_banned == 'yes')
                                    <p class="title">{{ __('Banned') }} : <b>{{ __('Yes') }}</b></p>
                                @else
                                    <p class="title">{{ __('Banned') }} : <b>{{ __('No') }}</b></p>
                                @endif

                                @if ($user->email_verified_at)
                                    <p class="title">{{ __('Email verified') }} : <b>{{ __('Yes') }}</b> </p>
                                @else
                                    <p class="title">{{ __('Email verified') }} : <b>{{ __('None') }}</b> </p>
                                    @adminCan('customer.update')
                                        <x-admin.button class="mb-3" data-bs-toggle="modal" data-bs-target="#verifyModal"
                                            variant="success" :text="__('Send Verify Link to Mail')" />
                                    @endadminCan
                                @endif

                                @adminCan('customer.update')
                                    <x-admin.button class="sendMail mb-3" data-bs-toggle="modal" data-bs-target="#sendMailModal"
                                        :text="__('Send Mail To Customer')" />

                                    @if ($user->is_banned == 'yes')
                                        <x-admin.button class="mb-3" data-bs-toggle="modal" data-bs-target="#bannedModal"
                                            variant="warning" :text="__('Remove From Banned')" />
                                    @else
                                        <x-admin.button class="mb-3" data-bs-toggle="modal" data-bs-target="#bannedModal"
                                            variant="warning" :text="__('Ban Customer')" />
                                    @endif
                                @endadminCan
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('Banned History')" />
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th width="30%">{{ __('Subject') }}</th>
                                            <th width="30%">{{ __('Description') }}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($banned_histories as $banned_history)
                                            <tr>
                                                <td>{{ $banned_history->subject }}</td>
                                                <td>{!! nl2br($banned_history->description) !!}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        {{-- profile information card area --}}
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <x-admin.form-title :text="__('Profile Information')" />

                                @adminCan('customer.delete')
                                    <x-admin.delete-button class="mb-3" :id="$user->id" onclick="deleteData"
                                        :text="__('Delete Account')" />
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <form
                                    action="{{ checkAdminHasPermission('customer.update') ? route('admin.customer-info-update', $user->id) : '' }}"
                                    method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

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
                                        <div class="col-12 text-center">
                                            <button class="btn btn-lg btn-success"
                                                type="submit">{{ __('Update') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- change password card area --}}
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('Change Password')" />
                            </div>
                            <div class="card-body">
                                <form
                                    action="{{ checkAdminHasPermission('customer.update') ? route('admin.customer-password-change', $user->id) : '' }}"
                                    method="post">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <x-admin.form-input id="password" name="password" type="password"
                                                label="{{ __('Password') }}" placeholder="{{ __('Enter Password') }}"
                                                required="true" />
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <x-admin.form-input id="password_confirmation" name="password_confirmation"
                                                type="password" label="{{ __('Confirm Password') }}"
                                                placeholder="{{ __('Enter Confirm Password') }}" required="true" />
                                        </div>
                                        @adminCan('customer.update')
                                            <div class="col-md-12 mt-4">
                                                <x-admin.update-button id="update-btn-2" :text="__('Change Password')" />
                                            </div>
                                        @endadminCan
                                    </div>
                                </form>
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
                                    <x-admin.form-input id="banned_user_subject" name="subject" value="{{ old('subject') }}"
                                        label="{{ __('Subject') }}" placeholder="{{ __('Enter Subject') }}"
                                        required="true" />
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
@adminCan('customer.delete')
    @push('js')
        <script>
            function deleteData(id) {
                $("#deleteForm").attr("action", "{{ url('/admin/customer-delete/') }}" + "/" + id)
            }

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
@endadminCan
