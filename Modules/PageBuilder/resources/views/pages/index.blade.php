@extends('admin.master_layout')
@section('title')
    <title>{{ __('Custom Page List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Custom Page List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Custom Page List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Custom Page List')" />
                                @adminCan('page.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.custom-pages.create')" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped" id="pagesTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('SN') }}</th>
                                                <th width="30%">{{ __('Title') }}</th>
                                                <th width="15%">{{ __('Slug') }}</th>
                                                @adminCan('page.update')
                                                    <th width="15%">{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('page.edit') || checkAdminHasPermission('page.delete'))
                                                    <th width="15%">{{ __('Action') }}</th>
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
    @adminCan('page.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    @include('admin.partials.system-records-toast')
    <script>
        $(document).ready(function() {
            var pagesTable = initServerDataTable('#pagesTable', "{{ route('admin.custom-pages.index') }}", [
                { data: 'title_link', name: 'title' },
                { data: 'slug', name: 'slug' },
                @adminCan('page.update')
                { data: 'status_toggle', name: 'status', orderable: false, searchable: false },
                @endadminCan
                @if (checkAdminHasPermission('page.edit') || checkAdminHasPermission('page.delete'))
                { data: 'action', name: 'action', orderable: false, searchable: false },
                @endif
            ], {
                drawCallback: function() {
                    // Toggles initialized by initSimpleAjaxTable via initBootstrapToggles
                }
            });
        });

        @adminCan('page.update')

        function changeStatus(id) {
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/custom-pages/status-update') }}" + "/" + id,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: function(err) {
                    if (err.responseJSON && err.responseJSON.message) {
                        toastr.error(err.responseJSON.message);
                    } else {
                        toastr.error("{{ __('Something went wrong, please try again') }}");
                    }
                }
            });
        }
        @endadminCan
    </script>
@endpush
