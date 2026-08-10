@extends('user.layout.app')

@section('title')
    #{{ $order->order_id }} {{ __('Reviews') }} || {{ $setting->app_name }}
@endsection

@section('user-breadcrumb')
    @include('components::breadcrumb', ['title' => __('Reviews'), 'image' => 'reviews'])
@endsection

@section('user-content')
    <div class="wsus__dashboard_contant">
        <div class="wsus__dashboard_contant_top">
            <div class="wsus__dashboard_heading d-flex justify-content-between">
                <h5>{{ __('Add Reviews') }}</h5>
                <span>
                    <a href="{{ route('website.user.invoice', ['uuid' => $order->uuid]) }}">#{{ $order->order_id }}</a>
                </span>
            </div>
        </div>

        <div class="wsus__dash_order_table">
            <div class="order_single_review_item">
                @forelse($order->items as $orderItem)
                    <div class="order_review_title">
                        @if ($orderItem->product && optional($orderItem?->product)?->status ?? 0 == 1)
                            <a href="{{ route('website.product', ['product' => $orderItem->product->slug]) }}">
                                <h4>{{ $orderItem->product->name }}</h4>
                            </a>
                        @else
                            <h5>{{ $orderItem->product_name }}</h5>
                        @endif
                        @if ($orderItem->review)
                            <span
                                class="badge {{ optional($orderItem?->review)?->status ?? 0 !== 0 ? 'bg-success' : 'bg-danger' }}">{{ optional($orderItem?->review)?->status ?? 0 !== 0 ? __('Approved') : __('Pending') }}
                            </span>
                        @endif
                    </div>
                    <div class="review_add_card">
                        <form id="add-review-form-{{ $orderItem->id }}" method="post">
                            <input name="order_id" type="hidden" value="{{ $order->id }}">
                            <input name="order_details_id" type="hidden" value="{{ $orderItem->id }}">
                            <input name="product_id" type="hidden" value="{{ $orderItem->product_id }}">
                            <div class="form-group mb-3">
                                <label class="form-label" for="add-review-{{ $orderItem->id }}">{{ __('Review') }}<span
                                        class="text-danger">*</span></label>
                                <textarea
                                    class="form-control text-area-5 {{ optional($orderItem?->review)?->status ?? 0 !== 0 ? 'border border-success' : '' }}"
                                    id="add-review-{{ $orderItem->id }}" name="review" cols="30" rows="5">{{ optional($orderItem?->review)?->review ?? '' }}</textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="add-rating-{{ $orderItem->id }}">{{ __('Rating') }}<span
                                        class="text-danger">*</span></label>
                                <select class="select_js" id="add-rating-{{ $orderItem->id }}" name="rating">
                                    <option value="1" @selected(optional($orderItem?->review)?->rating == 1)>{{ __('One Star') }}
                                    </option>
                                    <option value="2" @selected(optional($orderItem?->review)?->rating == 2)>{{ __('Two Star') }}
                                    </option>
                                    <option value="3" @selected(optional($orderItem?->review)?->rating == 3)>{{ __('Three Star') }}
                                    </option>
                                    <option value="4" @selected(optional($orderItem?->review)?->rating == 4)>{{ __('Four Star') }}
                                    </option>
                                    <option value="5" @selected(optional($orderItem?->review)?->rating == 5)>{{ __('Five Star') }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group mb-4">
                                <button class="common_btn add_new_review_button mt-3"
                                    data-form="add-review-form-{{ $orderItem->id }}" type="button">
                                    {{ __('Submit') }}
                                </button>
                        </form>
                    </div>

                @empty
                    <div class="wsus__product_single_review">
                        <h4>{{ __('No Product Found!') }}</h4>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        "use strict";

        $(document).on('click', '.add_new_review_button', function(e) {
            e.preventDefault();

            let $button = $(this);

            let formSelector = $button.data('form');

            let $form = $('#' + formSelector);

            if ($form.length) {
                let formData = $form.serializeArray();

                $.ajax({
                    url: "{{ route('website.user.reviews.store') }}",
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content'),
                        'Accept': 'application/json'
                    },
                    beforeSend: function() {
                        $button.prop('disabled', true);
                    },
                    success: function(response) {
                        toastr.success(response.message ||
                            '{{ __('Review submitted successfully!') }}');
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(function(key) {
                                toastr.error(errors[key][0]);
                            });
                        } else {
                            toastr.error('{{ __('Something went wrong. Please try again.') }}');
                        }
                    },
                    complete: function() {
                        $button.prop('disabled', false);
                    }
                });
            } else {
                toastr.warning("{{ __('You can not submit empty review!') }}");
            }
        });
    </script>
@endpush
