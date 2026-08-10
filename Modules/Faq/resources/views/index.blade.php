@extends('admin.master_layout')
@section('title')
    <title>{{ __('FAQ List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('FAQ List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('FAQ List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('FAQ List')" />
                                @adminCan('faq.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.faq.create')" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped" id="faqsTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Question') }}</th>
                                                <th>{{ __('Answer') }}</th>
                                                <th>{{ __('Group') }}</th>
                                                @adminCan('faq.update')
                                                    <th>{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('faq.edit') || checkAdminHasPermission('faq.delete'))
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
    @adminCan('faq.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            initServerDataTable('#faqsTable', "{{ route('admin.faq.index') }}", [
                { data: 'question', name: 'translation.question' },
                { data: 'answer', name: 'translation.answer', orderable: false },
                { data: 'group', name: 'group' },
                @adminCan('faq.update')
                { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                @endadminCan
                @if (checkAdminHasPermission('faq.edit') || checkAdminHasPermission('faq.delete'))
                { data: 'action', name: 'action', orderable: false, searchable: false },
                @endif
            ]);
        });

        @adminCan('faq.delete')
        "use strict";

        function deleteData(id) {
            $("#deleteForm").attr("action", "{{ url('/admin/faq/') }}" + "/" + id)
        }
        @endadminCan

        @adminCan('faq.update')

        function changeStatus(id) {
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/faq/status-update') }}" + "/" + id,
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
