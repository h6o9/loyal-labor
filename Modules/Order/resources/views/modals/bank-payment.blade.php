<!--Payment Approved Modal -->
<div class="modal fade" id="approveBankPayment" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
    tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header p-3">
                <h5 class="modal-title p-2">{{ __('Approve Bank Payment') }}</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close">

                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form action="{{ route('admin.orders.bank-payment.accept', $order->id) }}" method="POST">
                        @csrf
                        <input name="payment_type" type="hidden" value="bank">
                        <div class="row">
                            @if ($order->payment_details !== null && ($paymentDetails = json_decode($order->payment_details, true)))
                                @if ($paymentDetails)
                                    @foreach ($paymentDetails as $key => $value)
                                        <div class="form-group col-md-6">
                                            <label
                                                for="id_bank_{{ $key }}">{{ str(__($key))->title()->replace('_', ' ') }}</label>
                                            <input class="form-control" id="id_bank_{{ $key }}"
                                                name="bank_details[{{ $key }}]" type="text"
                                                value="{{ $value }}" readonly>
                                        </div>
                                    @endforeach
                                @endif
                            @endif

                            <div class="form-group col-12">
                                <b>
                                    {{ __('Approve Status') }}
                                </b>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="bank_paid_amount">{{ __('Paid Amount') }}</label>
                                <div class="input-group">
                                    <div class="input-group-text">
                                        {{ str($order->payable_currency)->upper() }}
                                    </div>
                                    <input class="form-control" id="bank_paid_amount" name="paid_amount" type="number"
                                        value="{{ $order->payable_amount }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"
                                    for="bank-payment-status-{{ $order->order_id }}">{{ __('Payment Status') }}</label>
                                <select
                                    class="form-control" id="bank-payment-status-{{ $order->order_id }}"
                                    name="bank_payment_status">
                                    @use('Modules\Order\app\Http\Enums\PaymentStatus', 'PaymentStatusEnum')

                                    @foreach ($paymentStatuses as $status)
                                        @continue($status == PaymentStatusEnum::PENDING || $status == PaymentStatusEnum::PROCESSING || $status == PaymentStatusEnum::FAILED)
                                        <option value="{{ $status->value }}" @selected($order->paymentDetails->payment_status->value == $status->value)>
                                            {{ $status->getLabel() }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 mt-3 mb-3">
                                <h5>
                                    {{ __('Approve Message') }}
                                </h5>
                            </div>

                            <div class="form-group col-12">
                                <label class="form-label" for="bank_subject">{{ __('Subject') }}<span
                                        class="text-danger">*</span></label>
                                <input class="form-control" id="bank_subject" name="subject" type="text">
                            </div>
                            @php($default_value = '[[name]]')
                            <div class="form-group col-12">
                                <label class="form-label" for="">{{ __('Description') }} <span
                                        class="text-danger">*</span> <span
                                        class="fa fa-info-circle text--primary" data-bs-toggle="tooltip"
                                        data-placement="top"
                                        title="Don't remove the [[name]] keyword, user name will be dynamic using it">
                                </label>
                                <textarea class="form-control text-area-5" name="description" cols="30" rows="10">{{ 'Dear ' . $default_value }}</textarea>
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" type="button">{{ __('Close') }}</button>
                <button class="btn btn-primary" type="submit">{{ __('Save Data') }}</button>
            </div>
            </form>
        </div>
    </div>
</div>
