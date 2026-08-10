@extends('admin.master_layout')
@section('title')
    <title>{{ __('Tax List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Tax List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Tax List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Tax List')" />
                                @adminCan('tax.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.tax.create')" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped" id="taxesTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Percentage') }}</th>
                                                @adminCan('tax.update')
                                                    <th>{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('tax.edit') || checkAdminHasPermission('tax.delete'))
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
    @adminCan('tax.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            initServerDataTable('#taxesTable', "{{ route('admin.tax.index') }}", [
                { data: 'title', name: 'translation.title' },
                { data: 'percentage', name: 'percentage' },
                @adminCan('tax.update')
                { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                @endadminCan
                @if (checkAdminHasPermission('tax.edit') || checkAdminHasPermission('tax.delete'))
                { data: 'action', name: 'action', orderable: false, searchable: false },
                @endif
            ]);
        });

        @adminCan('tax.delete')

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('/admin/tax/') }}' + "/" + id)
        }
        @endadminCan

        @adminCan('tax.update')

        function changeStatus(id) {
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/tax/status-update') }}" + "/" + id,
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
            })
        }
        @endadminCan
    </script>
@endpush
