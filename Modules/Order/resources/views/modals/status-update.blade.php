<div class="modal fade" id="update-status" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header p-3">
                <h5 class="modal-title">{{ __('Update Status') }}</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="status-update-form" action="{{ route('admin.orders.status.update', ['id' => $order->id]) }}"
                    method="post">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label" for="order_status">{{ __('Order Status') }}</label>
                        <select class="form-select" id="order_status" name="order_status">
                            <option value="">{{ __('Select Status') }}</option>
                            @foreach ($orderStatuses as $status)
                                <option value="{{ $status->value }}" @selected($order->order_status->value == $status->value)>
                                    {{ $status->getLabel() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="comment">{{ __('Comment') }}</label>
                        <textarea class="form-control h-50" id="comment" name="comment" rows="3">{{ old('comment') }}</textarea>
                    </div>
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
                                        @php($orderStatusHistories = $order->orderStatusHistory)
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
                <button class="btn btn-primary" form="status-update-form" type="submit">{{ __('Save Data') }}</button>
            </div>
        </div>
    </div>
</div>
