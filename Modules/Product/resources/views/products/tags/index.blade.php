@extends('admin.master_layout')
@section('title')
    <title>{{ __('Tags List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Tags List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Tags List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-0">
                                <form action="" method="GET" onsubmit="return false;">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group search-wrapper">
                                                <input class="form-control" id="keyword" name="keyword" type="text"
                                                    value="{{ request()->get('keyword') }}" placeholder="Search..."
                                                    autocomplete="off">

                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <select class="form-select" id="order_by" name="order_by">
                                                    <option value="">{{ __('Order By') }}</option>
                                                    <option value="asc"
                                                        {{ request('order_by') == 'asc' ? 'selected' : '' }}>
                                                        {{ __('ASC') }}
                                                    </option>
                                                    <option value="desc"
                                                        {{ request('order_by') == 'desc' ? 'selected' : '' }}>
                                                        {{ __('DESC') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Tags List')" />
                                @adminCan('product.brand.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.product.tags.create')" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped" id="tagsTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Name') }}</th>
                                                @if (checkAdminHasPermission('product.brand.edit') || checkAdminHasPermission('product.brand.delete'))
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

    @adminCan('product.brand.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            var tagsTable = initServerDataTable('#tagsTable', "{{ route('admin.product.tags.index') }}", [
                { data: 'name', name: 'name', orderable: false, searchable: false },
                @if (checkAdminHasPermission('product.brand.edit') || checkAdminHasPermission('product.brand.delete'))
                { data: 'action', name: 'action', orderable: false, searchable: false },
                @endif
            ], {
                ajax: {
                    url: "{{ route('admin.product.tags.index') }}",
                    data: function(d) {
                        d.keyword = $('#keyword').val();
                        d.order_by = $('#order_by').val();
                    }
                }
            });

            $('#keyword').on('keyup', function() {
                tagsTable.ajax.reload();
            });

            $('#order_by').on('change', function() {
                tagsTable.ajax.reload();
            });
        });

        @adminCan('product.brand.delete')
        "use strict"

        function deleteData(id) {
            let url = "{{ route('admin.product.tags.destroy', ':id') }}";
            url = url.replace(':id', id);

            $("#deleteForm").attr("action", url);
        }
        @endadminCan
    </script>
@endpush
