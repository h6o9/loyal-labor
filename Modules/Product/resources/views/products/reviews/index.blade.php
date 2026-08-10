@extends('admin.master_layout')
@section('title')
    <title>{{ __('Product Reviews') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Product Review') }}" :list="[
                'Dashboard' => route('admin.dashboard'),
                'Product Review' => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="reviewsTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="15%">{{ __('Name') }}</th>
                                                <th width="50%">{{ __('Product') }}</th>
                                                <th width="5%">{{ __('Rating') }}</th>
                                                <th width="10%">{{ __('Status') }}</th>
                                                <th width="10%">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection

@push('js')
    <script>
        "use strict";

        $(document).ready(function() {
            initServerDataTable('#reviewsTable', "{{ route('admin.product-review') }}", [
                { data: 'user_name', name: 'user.name', orderable: false, searchable: false },
                { data: 'product_name', name: 'product.name', orderable: false, searchable: false },
                { data: 'rating', name: 'rating' },
                { data: 'status_badge', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ], {
                ajax: {
                    url: "{{ route('admin.product-review') }}",
                    data: function(d) {
                        d.product = "{{ request()->get('product') }}";
                    }
                }
            });
        });

        function status(id) {
            handleStatus("{{ route('admin.product-review.status', ':id') }}".replace(':id', id));

            let status = $('[data-status=' + id + ']').text()
            // remove whitespaces using regex
            status = status.replaceAll(/\s/g, '');
            $('[data-status=' + id + ']').text(status != 'Hidden' ? 'Hidden' : 'Published');
        }

        function deleteData(id) {
            var id = id;
            var url = '{{ route('admin.product-review.delete', ':id') }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
            $('#deleteModal').modal('show');
        }
    </script>
@endpush
