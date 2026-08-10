@extends('admin.master_layout')
@section('title')
    <title>{{ __('Assign Role') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Assign Role') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Manage Roles') => route('admin.role.index'),
                __('Assign Role') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Assign Role')" />
                                <div>
                                    @adminCan('role.view')
                                        <x-admin.back-button :href="route('admin.role.index')" />
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <form role="form" action="{{ route('admin.role.assign.update') }}" method="POST">
                                    <div class="row">
                                        @method('PUT')
                                        @csrf
                                        <div class="form-group col-md-6">
                                            <x-admin.form-select id="user" name="user_id"
                                                label="{{ __('Select Admin') }}" class="select2" required="true">
                                                <x-admin.select-option value="" text="{{ __('Select Admin') }}" />
                                                @foreach ($admins as $admin)
                                                    <x-admin.select-option value="{{ $admin->id }}"
                                                        text="{{ $admin->name }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <x-admin.form-select id="role" name="role[]" label="{{ __('Role') }}"
                                                class="select2" required="true" multiple>
                                                <x-admin.select-option value="" disabled text="{{ __('Select Role') }}" />
                                                @foreach ($roles as $role)
                                                    <x-admin.select-option value="{{ $role->name }}"
                                                        text="{{ $role->name }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-12">
                                            <x-admin.update-button :text="__('Update')" />
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
@endsection

@push('js')
    <script>
        "use strict"
        $('#user').on('change', function(e) {
            var id = $(this).val();
            if (id) {
                $.ajax({
                    type: "post",
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    url: "{{ url('/admin/role/assign') }}" + "/" + id,
                    beforeSend: function() {
                        $('#update-btn').prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#role').empty();
                            $('#role').append(response.data);
                        }
                        $('#update-btn').prop('disabled', false);
                    },
                    error: function(err) {
                        $('#update-btn').prop('disabled', false);
                        toastr.error("{{ __('Failed!') }}")
                        console.log(err);
                    },
                })
            }
        });
    </script>
@endpush
