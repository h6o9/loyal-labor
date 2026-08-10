@extends('admin.master_layout')
@section('title')
    <title>{{ __('Coupon Histories') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Coupon Histories') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Coupon Histories') => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-sm-4">
                    <div class="col-12">
                        <div class="card ">
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="couponHistoryTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Coupon Code') }}</th>
                                                <th>{{ __('Discount Amount') }}</th>
                                                <th>{{ __('Date') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($coupon_histories as $index => $coupon_history)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $coupon_history?->coupon_code }}</td>
                                                    <td>

                                                        {{ currency($coupon_history?->discount_amount) }}

                                                    </td>
                                                    <td>{{ formattedDateTime($coupon_history?->created_at) }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Coupon')" route="admin.coupon.index" create="no"
                                                    :message="__('No data found!')" colspan="7"></x-empty-table>
                                            @endforelse
                                        </tbody>
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
    <script>
        $(document).ready(function() {
            if ($('#couponHistoryTable tbody tr').length && !$('#couponHistoryTable tbody tr td').first().attr('colspan')) {
                $('#couponHistoryTable').DataTable({
                    order: [],
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ],
                    language: {
                        emptyTable: '{{ __('No records found.') }}',
                        zeroRecords: '{{ __('No matching records found.') }}'
                    }
                });
            }
        });
    </script>
@endpush
