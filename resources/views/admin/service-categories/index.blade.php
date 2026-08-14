@extends('admin.master_layout')

@section('title')
    <title>{{ __('Service Categories') }}</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Service Categories') }}</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>{{ __('Service Categories List') }}</h4>
                            @can('service.categories.create')
                            <a class="btn btn-primary" href="{{ route('admin.service-categories.create') }}">
                                <i class="fa fa-plus"></i> {{ __('Add New') }}
                            </a>
                            @endcan
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="serviceCategoryTable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>{{ __('SN') }}</th>
                                            <th>{{ __('Icon') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Slug') }}</th>
                                            <th>{{ __('Sort Order') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Action') }}</th>
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
@endsection

@push('js')
    @include('admin.partials.system-records-toast')
    <script>
        function changeServiceCategoryStatus(id) {
            $.ajax({
                url: "{{ url('admin/service-categories/change-status') }}/" + id,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message || 'Status updated');
                    }
                },
                error: function () {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Failed to update status');
                    }
                    $('#serviceCategoryTable').DataTable().ajax.reload(null, false);
                }
            });
        }

        $(document).ready(function() {
            initServerDataTable('#serviceCategoryTable', "{{ route('admin.service-categories.index') }}", [
                { data: 'icon_html', name: 'icon', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'slug', name: 'slug' },
                { data: 'sort_order', name: 'sort_order' },
                { data: 'status_toggle', name: 'is_active', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);
        });
    </script>
@endpush
