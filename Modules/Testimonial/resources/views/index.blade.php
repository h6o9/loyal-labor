@extends('admin.master_layout')
@section('title')
    <title>{{ __('Testimonials') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Testimonials') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Testimonials') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Testimonials')" />
                                <div>
                                    @adminCan('testimonial.create')
                                        <x-admin.add-button :href="route('admin.testimonial.create')" />
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped" id="testimonialsTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Designation') }}</th>
                                                <th>{{ __('Image') }}</th>
                                                @adminCan('testimonial.update')
                                                    <th>{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('testimonial.edit') || checkAdminHasPermission('testimonial.delete'))
                                                    <th>{{ __('Action') }}</th>
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
    @adminCan('testimonial.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        "use strict";

        $(document).ready(function() {
            initServerDataTable('#testimonialsTable', "{{ route('admin.testimonial.index') }}", [
                { data: 'name', name: 'translation.name' },
                { data: 'designation', name: 'translation.designation', orderable: false },
                { data: 'image', name: 'image', orderable: false, searchable: false },
                @adminCan('testimonial.update')
                { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                @endadminCan
                @if (checkAdminHasPermission('testimonial.edit') || checkAdminHasPermission('testimonial.delete'))
                { data: 'action', name: 'action', orderable: false, searchable: false },
                @endif
            ]);
        });

        @adminCan('testimonial.delete')

        function deleteData(id) {
            $("#deleteForm").attr("action", "{{ url('/admin/testimonial/') }}" + "/" + id)
        }
        @endadminCan

        @adminCan('testimonial.update')

        function changeStatus(id) {
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/testimonial/status-update') }}" + "/" + id,
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
