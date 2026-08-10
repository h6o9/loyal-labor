@use('Modules\Order\app\Http\Enums\PaymentStatus', 'PaymentStatusEnum')

<div class="modal fade" id="update-payment-status" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header p-3">
                <h5 class="modal-title">{{ __('Update Payment Status') }}</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="payment-status-update-form"
                    action="{{ route('admin.orders.payment.status.update', ['id' => $order->id]) }}" method="post">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label" for="payment_status">
                            {{ __('Payment Status') }}
                        </label>
                        <select class="form-select" id="payment_status" name="payment_status">
                            <option value="">{{ __('Select Status') }}</option>
                            @foreach ($paymentStatuses as $status)
                                <option value="{{ $status->value }}" @selected($order->paymentDetails->payment_status->value == $status->value)>
                                    {{ $status->getLabel() }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($order->payment_method == 'hand_cash')
                        <input name="payment_method" type="hidden" value="hand_cash">
                        <div class="mb-2 d-none" id="cash_paid_amount_div">
                            <label class="form-label" for="cash_paid_amount">{{ __('Paid Amount') }}</label>
                            <br>
                            <div class="input-group">
                                <div class="input-group-text">
                                    {{ str($order->payable_currency)->upper() }}
                                </div>
                                <input class="form-control" id="cash_paid_amount" name="paid_amount" type="number"
                                    value="{{ $order->payable_amount }}">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label" for="payment-status-update-form-comment"
                                form="payment-status-update-form">{{ __('Comment') }}</label>
                            <textarea class="form-control h-50" id="payment-status-update-form-comment" name="comment" rows="3"
                                form="payment-status-update-form"">{{ old('comment') }}</textarea>
                        </div>
                    @else
                        <div class="mb-2">
                            <label class="form-label" for="payment-status-update-form-comment"
                                form="payment-status-update-form">{{ __('Comment') }}</label>
                            <textarea class="form-control h-50" id="payment-status-update-form-comment" name="comment"
                                form="payment-status-update-form" rows="3">{{ old('comment') }}</textarea>
                        </div>
                    @endif
                    <div class="mb-2">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('By') }}</th>
                                        <th>{{ __('Updated At') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!isset($orderStatusHistories))
                                        @php($orderStatusHistories = $order->paymentStatusHistory)
                                    @endif

                                    @forelse ($orderStatusHistories as $orderStatusHistory)
                                        <tr>
                                            <td>{{ $orderStatusHistory->to_status_enum->getLabel() }}</td>
                                            <td>
                                                @if ($orderStatusHistory->change_by == 'admin')
                                                    {{ $orderStatusHistory->changedByAdmin->name ?? '' }}
                                                @elseif($orderStatusHistory->change_by == 'user')
                                                    {{ $orderStatusHistory->changedByUser->name ?? '' }}
                                                @else
                                                    {{ $orderStatusHistory->change_by }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ formattedDateTime($orderStatusHistory->updated_at ? $orderStatusHistory->updated_at : $orderStatusHistory->created_at) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="4">
                                                {{ __('No Order Status History Found') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" type="button">{{ __('Close') }}</button>
                <button class="btn btn-primary" form="payment-status-update-form"
                    type="submit">{{ __('Save Data') }}</button>
            </div>
        </div>
    </div>
</div>
