@extends('admin.master_layout')

@section('title')
    <title>{{ __('Label List') }}</title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Label List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Label List') => '#',
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
                                <x-admin.form-title :text="__('Label List')" />
                                @adminCan('product.label.create')
                                    <div>
                                        <x-admin.add-button data-bs-toggle="modal" data-bs-target="#add-label"
                                            href="javascript:;" text="{{ __('Add Label') }}" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped" id="labelsTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Name') }}</th>
                                                @adminCan('product.label.update')
                                                    <th>{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('product.label.edit') || checkAdminHasPermission('product.label.delete'))
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
@endsection

@push('modals')
    @adminCan('product.label.create')
        @include('product::products.label.create-model')
    @endadminCan
@endpush

@push('js')

    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $('[name="name"]').on('input', function() {
                    var name = $(this).val();
                    var slug = convertToSlug(name);
                    $("[name='slug']").val(slug);
                });
            });
        })(jQuery);
    </script>

    <script>
        $(document).ready(function() {
            var labelsTable = initServerDataTable('#labelsTable', "{{ route('admin.label.index') }}", [
                { data: 'name', name: 'name', orderable: false, searchable: false },
                @adminCan('product.label.update')
                { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                @endadminCan
                @if (checkAdminHasPermission('product.label.edit') || checkAdminHasPermission('product.label.delete'))
                { data: 'action', name: 'action', orderable: false, searchable: false },
                @endif
            ], {
                ajax: {
                    url: "{{ route('admin.label.index') }}",
                    data: function(d) {
                        d.keyword = $('#keyword').val();
                        d.order_by = $('#order_by').val();
                    }
                }
            });

            $('#keyword').on('keyup', function() {
                labelsTable.ajax.reload();
            });

            $('#order_by').on('change', function() {
                labelsTable.ajax.reload();
            });
        });
    </script>

    <script>
        @adminCan('product.label.delete')
        "use strict"

        function deleteData(id) {
            let url = "{{ route('admin.label.destroy', ':id') }}";
            url = url.replace(':id', id);

            $("#deleteForm").attr("action", url);
        }
        @endadminCan

        @adminCan('product.label.update')
        "use strict"

        function changeStatus(id) {
            let url = "{{ route('admin.label.status', ':id') }}";
            url = url.replace(':id', id);
            $.ajax({
                type: "patch",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                headers: {
                    "accept": "application/json",
                },
                url,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: function(err) {
                    handleFetchError(err);
                }
            });
        }
        @endadminCan
    </script>
@endpush
