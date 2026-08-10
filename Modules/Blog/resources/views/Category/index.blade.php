@extends('admin.master_layout')
@section('title')
    <title>{{ __('Category List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Category List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Category List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Category List')" />
                                @adminCan('blog.category.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.blog-category.create')" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped" id="blogCategoryTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Slug') }}</th>
                                                @adminCan('blog.category.update')
                                                    <th>{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('blog.category.edit') || checkAdminHasPermission('blog.category.delete'))
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
    @adminCan('blog.category.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            initServerDataTable('#blogCategoryTable', "{{ route('admin.blog-category.index') }}", [
                { data: 'title', name: 'title', orderable: false, searchable: false },
                { data: 'slug', name: 'slug' },
                @adminCan('blog.category.update')
                { data: 'status_toggle', name: 'status', orderable: false, searchable: false },
                @endadminCan
                @if (checkAdminHasPermission('blog.category.edit') || checkAdminHasPermission('blog.category.delete'))
                { data: 'action', name: 'action', orderable: false, searchable: false },
                @endif
            ], {
                drawCallback: function() {
                    // Toggles initialized by initSimpleAjaxTable via initBootstrapToggles
                }
            });
        });

        @adminCan('blog.category.update')

        function changeStatus(id) {
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/blog-category/status-update') }}" + "/" + id,
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
