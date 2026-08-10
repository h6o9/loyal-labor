@extends('admin.master_layout')
@section('title')
    <title>{{ __('Contact Message') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Contact Message') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Contact Message') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.update-general-setting') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-5">
                                            <x-admin.form-input type="email" id="contact_message_receiver_mail"
                                                name="contact_message_receiver_mail"
                                                label="{{ __('Contact Message Receiver Email') }}"
                                                placeholder="{{ __('Enter Email') }}" class="mb-2"
                                                value="{{ $setting->contact_message_receiver_mail }}" required="true" />
                                            <x-admin.update-button :text="__('Update')" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive table-invoice">
                            <table class="table table-striped" id="contactMessagesTable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('SN') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Created at') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('contact.message.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection
@push('js')
    @include('admin.partials.system-records-toast')
    <script>
        $(document).ready(function() {
            initServerDataTable('#contactMessagesTable', "{{ route('admin.contact-messages') }}", [
                { data: 'name_decoded', name: 'name' },
                { data: 'email_link', name: 'email' },
                { data: 'created_at_formatted', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);
        });
    </script>
@endpush
