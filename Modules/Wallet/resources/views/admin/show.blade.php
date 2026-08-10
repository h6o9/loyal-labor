@extends('admin.master_layout')
@section('title')
    <title>{{ __('Wallet Details') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Wallet Details') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Wallet Details') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">

                                        <tr>
                                            <th>{{ __('User') }}</th>
                                            <td><a
                                                    href="{{ route('admin.customer-show', $wallet_history->user_id) }}">{{ $wallet_history?->user?->name }}</a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                {{ __('Seller') }}
                                            </th>
                                            <td>
                                                {{ $wallet_history->vendor->shop_name ?? '' }}
                                            </td>
                                        </tr>

                                        @if ($wallet_history->transaction_type == 'credit' && $wallet_history?->order)
                                            <tr>
                                                <th>
                                                    {{ __('Order') }}
                                                </th>
                                                <td>
                                                    <a href="{{ route('admin.order', $wallet_history->order->order_id) }}"
                                                        target="_blank">#{{ $wallet_history->order?->order_id }}</a>
                                                </td>
                                            </tr>
                                        @endif

                                        @if ($wallet_history->transaction_type == 'credit' && $wallet_history->orderDetails)
                                            <tr>
                                                <th>
                                                    {{ __('Product') }}
                                                </th>
                                                <td>
                                                    <a href="{{ route('admin.product.show', ['product' => $wallet_history->orderDetails?->product_id]) }}"
                                                        target="_blank" rel="noopener noreferrer">
                                                        {{ $wallet_history->orderDetails?->product_name }}
                                                        ({{ $wallet_history->orderDetails?->product_sku }})
                                                    </a>
                                                </td>
                                            </tr>
                                        @elseif ($wallet_history->transaction_type == 'debit' && $wallet_history->withdrawRequest)
                                            <tr>
                                                <th>
                                                    {{ __('Withdraw Request') }}
                                                </th>
                                                <td>
                                                    <a href="{{ route('admin.show-withdraw', $wallet_history->withdrawRequest->id) }}"
                                                        target="_blank" rel="noopener noreferrer">
                                                        {{ __('Show Withdraw') }}</a>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif

                                        <tr>
                                            <th>{{ __('Transaction Type') }}</th>
                                            <td>
                                                @if ($wallet_history->transaction_type == 'credit')
                                                    <span class="badge bg-success"><i class="fas fa-plus"></i>
                                                        {{ __('Credit') }}</span>
                                                @else
                                                    <span class="badge bg-danger"><i class="fas fa-minus"></i>
                                                        {{ __('Debit') }}</span>
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>{{ __('Gateway') }}</th>
                                            <td>{{ __($wallet_history->payment_gateway) }}</td>
                                        </tr>

                                        <tr>
                                            <th>{{ __('Amount') }}</th>
                                            <td>{{ currency($wallet_history->amount) }}</td>
                                        </tr>

                                        <tr>
                                            <th>{{ __('Status') }}</th>

                                            <td>
                                                @if ($wallet_history->payment_status == 'completed')
                                                    <div class="badge bg-success">{{ __('Completed') }}</div>
                                                @elseif ($wallet_history->payment_status == 'rejected')
                                                    <div class="badge bg-danger">{{ __('Rejected') }}</div>
                                                @else
                                                    <div class="badge bg-danger">{{ __('Pending') }}</div>
                                                @endif
                                            </td>

                                        </tr>

                                        <tr>
                                            <th>{{ __('Transaction ID') }}</th>
                                            <td>{{ $wallet_history->transaction_id }}</td>

                                        </tr>

                                        <tr>
                                            <th>{{ __('Deposit At') }}</th>
                                            <td>{{ formattedDateTime($wallet_history->created_at) }}</td>
                                        </tr>

                                    </table>
                                </div>

                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <div>
                                    @if ($wallet_history->transaction_type == 'credit' && $wallet_history->payment_status == 'pending')
                                        <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectWallet"
                                            href="javascript:;">{{ __('Make a reject') }}</a>
                                    @endif

                                    @if (
                                        ($wallet_history->transaction_type == 'credit' && $wallet_history->payment_status == 'rejected') ||
                                            $wallet_history->payment_status == 'pending')
                                        <a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveRefund"
                                            href="javascript:;">{{ __('Make an approved') }}</a>
                                    @endif
                                </div>

                                <a class="btn btn-danger delete"
                                    data-url="{{ route('admin.delete-wallet-history', $wallet_history->id) }}"
                                    href="">{{ __('Delete History') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="delete" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <form action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Delete refund request') }}</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger">{{ __('Are You Sure to Delete this wallet history?') }}</p>
                    </div>
                    <div class="modal-footer">
                        <x-admin.button data-bs-dismiss="modal" variant="danger" text="{{ __('Close') }}" />
                        <x-admin.button type="submit" text="{{ __('Yes, Delete') }}" />
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!--Wallet Reject Modal -->
    <div class="modal fade" id="rejectWallet" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Payment Request Rejected') }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form action="{{ route('admin.rejected-wallet-request', $wallet_history->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">{{ __('Subject') }}</label>
                                <input class="form-control" name="subject" type="text">
                            </div>
                            @php($default_value = '[[name]]')
                            <div class="form-group">
                                <label for="">{{ __('Description') }} <span class="fa fa-info-circle text--primary"
                                        data-toggle="tooltip" data-placement="top"
                                        title="Don't remove the [[name]] keyword, user name will be dynamic using it">
                                </label>
                                <textarea class="form-control text-area-5" name="description" cols="30" rows="10">{{ 'Dear ' . $default_value }}</textarea>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-admin.button data-bs-dismiss="modal" variant="danger" text="{{ __('Close') }}" />
                    <x-admin.button type="submit" text="{{ __('Save Data') }}" />
                </div>
                </form>
            </div>
        </div>
    </div>

    <!--Refund Approved Modal -->
    <div class="modal fade" id="approveRefund" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Wallet payment request approval') }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form action="{{ route('admin.approved-wallet-request', $wallet_history->id) }}" method="POST">
                            @csrf
                            <p>
                                {{ __('The credit will added to seller wallet.') }}
                            </p>
                            <p>
                                {{ __('Are you sure want to approve this wallet payment request?') }}
                            </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-admin.button data-bs-dismiss="modal" variant="danger" text="{{ __('Close') }}" />
                    <x-admin.button type="submit" text="{{ __('Yes, Sure') }}" />
                </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            $(function() {
                'use strict'

                $('.delete').on('click', function(e) {
                    e.preventDefault();
                    const modal = $('#delete');
                    modal.find('form').attr('action', $(this).data('url'));
                    modal.modal('show');
                })
            })
        </script>
    @endpush
@endsection
