@extends('admin.master_layout')
@section('title')
    <title>{{ __('Blog List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Blog List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Blog List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-1 ">
                                <form id="blog-filter" action="javascript:;">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
                                            <div class="input-group">
                                                <x-admin.form-input name="keyword" value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}" />
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="show_homepage"
                                                name="show_homepage">
                                                <x-admin.select-option value="" text="{{ __('Show Homepage') }}" />
                                                <x-admin.select-option value="1" :selected="request('show_homepage') == '1'"
                                                    text="{{ __('Yes') }}" />
                                                <x-admin.select-option value="0" :selected="request('show_homepage') == '0'"
                                                    text="{{ __('No') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="is_popular" name="is_popular">
                                                <x-admin.select-option value="" text="{{ __('Select Popular') }}" />
                                                <x-admin.select-option value="1" :selected="request('is_popular') == '1'"
                                                    text="{{ __('Yes') }}" />
                                                <x-admin.select-option value="0" :selected="request('is_popular') == '0'"
                                                    text="{{ __('No') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="status" name="status">
                                                <x-admin.select-option value="" text="{{ __('Select Status') }}" />
                                                <x-admin.select-option value="1" :selected="request('status') == '1'"
                                                    text="{{ __('Yes') }}" />
                                                <x-admin.select-option value="0" :selected="request('status') == '0'"
                                                    text="{{ __('No') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="order_by" name="order_by">
                                                <x-admin.select-option value="" text="{{ __('Order By') }}" />
                                                <x-admin.select-option value="1" :selected="request('order_by') == '1'"
                                                    text="{{ __('ASC') }}" />
                                                <x-admin.select-option value="0" :selected="request('order_by') == '0'"
                                                    text="{{ __('DESC') }}" />
                                            </x-admin.form-select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Blog List')" />
                                @adminCan('blog.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.blogs.create')" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped" id="blogPostTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="30%">{{ __('Title') }}</th>
                                                <th width="15%">{{ __('Category') }}</th>
                                                <th width="10%">{{ __('Show Homepage') }}</th>
                                                <th width="10%">{{ __('Popular') }}</th>
                                                @adminCan('blog.update')
                                                    <th width="15%">{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('blog.edit') || checkAdminHasPermission('blog.delete'))
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
    @adminCan('blog.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        "use strict";

        $(document).ready(function() {
            var blogPostTable = initServerDataTable('#blogPostTable', {
                url: "{{ route('admin.blogs.index') }}",
                data: function(d) {
                    d.keyword = $('#blog-filter [name="keyword"]').val();
                    d.show_homepage = $('#show_homepage').val();
                    d.is_popular = $('#is_popular').val();
                    d.status = $('#status').val();
                    d.order_by = $('#order_by').val();
                }
            }, [
                { data: 'title', name: 'title', orderable: false, searchable: false },
                { data: 'category_name', name: 'category.title', orderable: false, searchable: false },
                { data: 'show_homepage_badge', name: 'show_homepage' },
                { data: 'is_popular_badge', name: 'is_popular' },
                @adminCan('blog.update')
                { data: 'status_toggle', name: 'status', orderable: false, searchable: false },
                @endadminCan
                @if (checkAdminHasPermission('blog.edit') || checkAdminHasPermission('blog.delete'))
                { data: 'action', name: 'action', orderable: false, searchable: false },
                @endif
            ], {
                searching: false,
                drawCallback: function() {
                    // Toggles initialized by initSimpleAjaxTable via initBootstrapToggles
                }
            });

            $('#blog-filter').on('submit', function(e) {
                e.preventDefault();
                blogPostTable.ajax.reload();
            });

            $('#blog-filter select').on('change', function() {
                blogPostTable.ajax.reload();
            });
        });

        @adminCan('blog.update')

        function changeStatus(id) {
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/blogs/status-update') }}" + "/" + id,
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
