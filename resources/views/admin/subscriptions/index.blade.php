@extends('admin.master_layout')

@section('title')
    <title>{{ __('Subscription Plans') }}</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Subscription Plans') }}</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>{{ __('Subscription Plans List') }}</h4>
                            @can('subscriptions.create')
                            <!-- <a class="btn btn-primary" href="{{ route('admin.subscriptions.create') }}">
                                <i class="fa fa-plus"></i> {{ __('Add New') }}
                            </a> -->
                            @endcan
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="subscriptionTable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>{{ __('SN') }}</th>
                                            <th>{{ __('Plan Name') }}</th>
                                            <th>{{ __('Duration') }}</th>
                                            <th>{{ __('Price (PKR)') }}</th>
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
        function changeSubscriptionStatus(id) {
            $.ajax({
                url: "{{ url('admin/subscriptions/change-status') }}/" + id,
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
                    $('#subscriptionTable').DataTable().ajax.reload(null, false);
                }
            });
        }

        $(document).ready(function() {
            initServerDataTable('#subscriptionTable', "{{ route('admin.subscriptions.index') }}", [
                { data: 'name', name: 'name' },
                { data: 'duration_months', name: 'duration_months' },
                { data: 'price_pkr', name: 'price_pkr' },
                { data: 'status_toggle', name: 'is_active', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);
        });
    </script>
@endpush
