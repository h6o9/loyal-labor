@extends('installer::app')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <p>Enter Database Details</p>
            <div>
                <a class="btn btn-outline-primary" href="{{ route('setup.requirements') }}">&laquo; Back</a>
            </div>
        </div>
        <form id="database_migrate_form" autocomplete="off">
            <div class="card-body">
                <div class="mb-3">
                    <label>Host <span class="text-danger">*</span></label>
                    <input class="form-control" id="host" name="host" type="text"
                        value="{{ old('host') ?: '127.0.0.1' }}" placeholder="Enter Database Host">
                </div>
                <div class="mb-3">
                    <label>Port <span class="text-danger">*</span></label>
                    <input class="form-control" id="port" name="port" type="text"
                        value="{{ old('port') ?: '3306' }}" placeholder="Enter Database Port. Default Is 3306">
                </div>
                <div class="mb-3">
                    <label>Database Name <span class="text-danger">*</span></label>
                    <input class="form-control" id="database" name="database" type="text" value="{{ old('database') }}"
                        placeholder="Enter Database Name Here">
                    <div class="my-3 d-none" id="reset_database_switcher">
                        <input class="form-check-input" id="reset_database" name="reset_database" type="checkbox"
                            role="switch" {{ old('reset_database') ? 'checked' : '' }}>
                        <label class="text-danger" for="reset_database"><b><small>Database not empty. Are you sure
                                    want to clean this
                                    database?</small></b> </label>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Database User <span class="text-danger">*</span></label>
                    <input class="form-control" id="user" name="user" type="text" value="{{ old('user') }}"
                        autocomplete="off" placeholder="Enter Database User Here">
                </div>
                <div class="mb-3">
                    <label>Database User Password @if (isset($isLocalHost) && !$isLocalHost)
                            <span class="text-danger">*</span>
                        @endif
                    </label>
                    <input class="form-control" id="password" name="password" type="password" value="{{ old('password') }}"
                        autocomplete="new-password" placeholder="Enter Database Password Here">
                </div>
                <div class="mb-3">
                    <b class="text-success">If you prefer a fresh installation without any dummy data, simply toggle the
                        "Dummy Data" switch.</b>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between ">
                <input class="form-check-input" id="fresh_install" name="fresh_install" type="checkbox" role="switch"
                    {{ old('fresh_install') ? 'checked' : '' }}>
                <button class="btn btn-lg btn-primary" id="submit_btn" type="button"
                    onclick="checkDummyDataStatus();">Setup
                    Database</button>
            </div>
        </form>
        <div class="card-footer text-center">
            <p>For script support, contact us at <a href="https://websolutionus.com/page/support" target="_blank"
                    rel="noopener noreferrer">@websolutionus</a>. We're here to help. Thank you!</p>
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{ asset('backend/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/iziToast.min.css') }}" rel="stylesheet">
    <style>
        .form-switch {
            padding-left: 0px !important;
        }

        .form-check {
            padding-left: 0px !important;
        }

        .toggle.btn.btn-lg {
            width: 212px;
        }

        .iziToast-message {
            line-height: 1.6 !important;
        }

        .success_toast_button {
            background-color: #28a745 !important;
            color: white !important;
            font-size: 18px !important;
            padding: 10px 20px !important;
            border-radius: 5px !important;
            border: none !important;
            cursor: pointer !important;
            transition: 0.3s ease-in-out !important;
        }

        .success_toast_button:hover {
            background-color: #218838 !important;
        }

        .cancel_toast_button {
            background-color: #dc3545 !important;
            color: white !important;
            font-size: 18px !important;
            padding: 10px 20px !important;
            border-radius: 5px !important;
            border: none !important;
            cursor: pointer !important;
            transition: 0.3s ease-in-out !important;
        }

        .cancel_toast_button:hover {
            background-color: #c82333 !important;
        }

        .iziToast-buttons {
            display: flex !important;
            justify-content: space-between !important;
            gap: 10px !important;
            width: 100% !important;
        }

        .iziToast-buttons button {
            flex: 1 !important;
            text-align: center !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('backend/js/bootstrap-toggle.jquery.min.js') }}"></script>
    <script src="{{ asset('backend/js/iziToast.min.js') }}"></script>
    <script>
        "use strict";

        $('#reset_database').bootstrapToggle({
            onlabel: 'Yes',
            offlabel: 'No',
            onstyle: 'danger',
            offstyle: 'secondary',
            size: 'sm'
        });

        $('#fresh_install').bootstrapToggle({
            onlabel: 'Fresh Install',
            offlabel: 'With Dummy Data',
            onstyle: 'success',
            offstyle: 'warning',
            size: 'lg'
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('submit', '#database_migrate_form', async function(e) {
                e.preventDefault();
                let submit_btn, host, port, database, username, password, fresh_install, reset_database;
                submit_btn = $('#submit_btn');
                host = $('#host').val();
                port = $('#port').val();
                database = $('#database').val();
                username = $('#user').val();
                password = $('#password').val();
                fresh_install = $('#fresh_install');
                reset_database = $('#reset_database');

                if ($.trim(host) === '') {
                    toastr.warning("Host is required");
                } else if ($.trim(port) === '') {
                    toastr.warning("Port is required");
                } else if ($.trim(database) === '') {
                    toastr.warning("Database Name is required");
                } else if ($.trim(username) === '') {
                    toastr.warning("Username is required");
                } else {
                    submit_btn.html(
                        'Migrating... <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                    ).prop('disabled', true);
                    try {
                        let data = {
                            host: host,
                            port: port,
                            database: database,
                            user: username,
                            password: password,
                        };
                        if (fresh_install.is(':checked')) {
                            data.fresh_install = fresh_install.val();
                        }
                        if (reset_database.is(':checked')) {
                            data.reset_database = reset_database.val();
                        }
                        const res = await makeAjaxRequest(data,
                            "{{ route('setup.database.submit') }}");
                        $('#reset_database').bootstrapToggle('off');
                        $('#reset_database_switcher').addClass('d-none');
                        if (res.success) {
                            toastr.success(res.message);
                            submit_btn.addClass('btn-success').html('Redirecting...');
                            window.location.href = "{{ route('setup.account') }}";
                        } else if (res.create_database) {
                            toastr.error(res.message);
                            submit_btn.html('Setup Database').prop('disabled', false);
                        } else if (res.reset_database) {
                            $('#reset_database_switcher').removeClass('d-none');
                            toastr.error(res.message);
                            submit_btn.html('Setup Database').prop('disabled', false);
                        } else {
                            submit_btn.html('Setup Database').prop('disabled', false);
                            toastr.error(res.message);
                        }

                    } catch (error) {
                        submit_btn.html('Setup Database').prop('disabled', false);
                        $.each(error.errors, function(index, value) {
                            toastr.error(value);
                        });
                    }
                }
            });
        });

        function checkDummyDataStatus() {
            let fresh_install = $('#fresh_install');

            if (!fresh_install.is(':checked')) {
                iziToast.question({
                    timeout: false,
                    theme: 'light',
                    icon: 'icon-info',
                    title: 'Notice',
                    message: 'Since you have chosen to proceed with dummy data, we will add some sample data for demonstration purposes. This is completely safe. If you prefer a clean installation without dummy data, please cancel this action and simply toggle the "Dummy Data" switch then proceed',
                    position: 'center',
                    layout: 2,
                    maxWidth: 500,
                    buttons: [
                        ['<button class="success_toast_button"><b>Setup With Dummy Data</b></button>',
                            function(
                                instance,
                                toast) {
                                instance.hide({
                                    transitionOut: 'fadeOut'
                                }, toast, 'button');
                                $('#database_migrate_form').submit();
                            },
                            true
                        ],
                        ['<button class="cancel_toast_button">Cancel</button>', function(instance,
                            toast) {

                            instance.hide({
                                transitionOut: 'fadeOut'
                            }, toast, 'button');

                        }]
                    ]
                });
            } else {
                $('#database_migrate_form').submit();
            }
        }
    </script>
@endpush
