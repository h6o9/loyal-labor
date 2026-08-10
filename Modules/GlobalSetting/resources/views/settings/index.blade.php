@extends('admin.master_layout') @section('title')
    <title>{{ __('General Setting') }}</title>
    @endsection @section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('General Setting') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('General Setting') => '#',
            ]" />
            <div class="section-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills flex-column" id="generalTab" role="tablist">
                                    @include('globalsetting::settings.tabs.navbar')
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content" id="myTabContent2">
                                    @include('globalsetting::settings.sections.general')
                                    @include('globalsetting::settings.sections.website')
                                    @include('globalsetting::settings.sections.logo-favicon')
                                    @include('globalsetting::settings.sections.cookie')
                                    @include('globalsetting::settings.sections.custom-paginate')
                                    @include('globalsetting::settings.sections.default-avatar')
                                    @include('globalsetting::settings.sections.breadcrump')
                                    @include('globalsetting::settings.sections.copyright')
                                    @include('globalsetting::settings.sections.search-engine-crawling')
                                    @include('globalsetting::settings.sections.maintenance-mode')
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
    <script src="{{ asset('backend/js/jquery.uploadPreview.min.js') }}"></script>
    <script>
        $.uploadPreview({
            input_field: "#image-upload",
            preview_box: "#image-preview",
            label_field: "#image-label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#logo-dark-upload",
            preview_box: "#logo-dark-preview",
            label_field: "#logo-dark-label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#favicon-upload",
            preview_box: "#favicon-preview",
            label_field: "#favicon-label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#breadcrumb_image_upload",
            preview_box: "#breadcrumb_image_preview",
            label_field: "#breadcrumb_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#default_avatar_upload",
            preview_box: "#default_avatar_preview",
            label_field: "#default_avatar_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#maintenance_image_upload",
            preview_box: "#maintenance_image_preview",
            label_field: "#maintenance_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });

        //Tab active setup locally
        $(document).ready(function() {
            activeTabSetupLocally('generalTab');

            $('#admin_login_prefix').on('input', function() {
                let newPrefix = $(this).val().trim();

                if (newPrefix === '') {
                    newPrefix = 'admin';
                }

                const baseUrl = "{{ url('/') }}";
                const newUrl = `${baseUrl}/${newPrefix}/login`;

                $('#url_to_change')
                    .attr('href', newUrl)
                    .text(newUrl);
            });
        });

        //Maintenance mode toggler
        function changeMaintenanceModeStatus() {
            var isDemo = "{{ strtoupper(config('app.app_mode')) ?? 'LIVE' }}"
            if (isDemo == 'DEMO') {
                toastr.error("{{ __('This Is Demo Version. You Can Not Change Anything') }}");
                return;
            }
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/update-maintenance-mode-status') }}",
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: function(err) {
                    console.log(err);

                }
            })
        }
    </script>

    <script>
        function copyText() {
            var textToCopy = document.getElementById("copyCronText");
            var range = document.createRange();
            range.selectNode(textToCopy);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            document.execCommand("copy");

            toastr.success("{{ __('Copied to clipboard') }}");
        }
    </script>
@endpush
