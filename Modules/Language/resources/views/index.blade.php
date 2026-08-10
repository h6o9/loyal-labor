@extends('admin.master_layout')
@section('title')
    <title>{{ __('Manage Language') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Manage Language') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Manage Language') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Manage Language')" />
                                <div>
                                    @adminCan('language.create')
                                        <x-admin.add-button :href="route('admin.languages.create')" />
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped" id="languagesTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Code') }}</th>
                                                <th>{{ __('Direction') }}</th>
                                                @adminCan('language.update')
                                                    <th>{{ __('Default') }}</th>
                                                    <th>{{ __('Translations') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('language.edit') || checkAdminHasPermission('language.delete'))
                                                    <th class="text-center">{{ __('Actions') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('language.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    @include('admin.partials.system-records-toast')
    <script>
        $(document).ready(function() {
            initServerDataTable('#languagesTable', "{{ route('admin.languages.index') }}", [
                { data: 'name', name: 'name' },
                { data: 'code', name: 'code' },
                { data: 'direction_label', name: 'direction' },
                @adminCan('language.update')
                { data: 'default_toggle', name: 'is_default', orderable: false, searchable: false },
                { data: 'translations_link', name: 'translations_link', orderable: false, searchable: false },
                { data: 'status_toggle', name: 'status', orderable: false, searchable: false },
                @endadminCan
                @if (checkAdminHasPermission('language.edit') || checkAdminHasPermission('language.delete'))
                { data: 'action', name: 'action', orderable: false, searchable: false },
                @endif
            ], {
                drawCallback: function() {
                    // Toggles initialized by initSimpleAjaxTable via initBootstrapToggles
                }
            });
        });

        @adminCan('language.update')

        function changeStatus(id, type) {
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                    column: type
                },
                url: "{{ url('/admin/languages/update-status') }}" + "/" + id,
                success: function(response) {
                    if (response.status) {
                        toastr.success(response.message);
                        if (type == 'is_default') {
                            window.location.reload();
                        }
                    } else {
                        toastr.warning(response.message);
                        if (!response.status) {
                            window.location.reload();
                        }
                    }
                },
                error: function(err) {
                    if (err.responseJSON && err.responseJSON.message) {
                        toastr.error(err.responseJSON.message);
                    } else {
                        toastr.error("{{ __('Something went wrong, please try again') }}");
                    }
                }
            })
        }
        @endadminCan
    </script>
@endpush