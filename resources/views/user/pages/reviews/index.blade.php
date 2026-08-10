@extends('user.layout.app')

@section('title')
    {{ __('Reviews') }} || {{ $setting->app_name }}
@endsection

@section('user-breadcrumb')
    @include('components::breadcrumb', ['title' => __('Reviews'), 'image' => 'reviews'])
@endsection

@section('user-content')
    <div class="wsus__dashboard_contant">
        <div class="wsus__dashboard_contant_top">
            <div class="wsus__dashboard_heading">
                <h5>{{ __('Reviews') }}</h5>
                </span>
            </div>
        </div>

        <div class="wsus__dash_order_table">
            <div class="wsus__product_details_review">
                @forelse($reviews as $review)
                    <div class="wsus__product_single_review">
                        <div class="img">
                            <img class="img-fluid w-100"
                                src="{{ $review?->user?->image ? asset($review->user->image) : asset($setting->default_user_image) }}"
                                alt="comment">
                        </div>
                        <div class="text">
                            <h5>{{ $review->user->name ?? __('Anonymous') }}
                                <span class="review_icon">
                                    @for ($i = 0; $i < $review->rating; $i++)
                                        <i class="fas fa-star" aria-hidden="true"></i>
                                    @endfor
                                    @for ($i = 0; $i < 5 - $review->rating; $i++)
                                        <i class="fal fa-star" aria-hidden="true"></i>
                                    @endfor
                            </h5>
                            <span class="date">{{ formattedDateTime($review->created_at) }}</span>
                            <p>
                                {{ $review->review }}
                            </p>
                            <div class="buttons">
                                @if ($review?->product)
                                    <a class="common_btn btn_info"
                                        href="{{ route('website.product', ['product' => $review->product->slug ?? 404]) }}">
                                        {{ __('View Product') }}
                                    </a>
                                @endif
                                @if ($review?->order)
                                    <a class="common_btn btn_success"
                                        href="{{ route('website.user.invoice', ['uuid' => $review?->order?->uuid ?? 0]) }}">
                                        {{ __('View Order') }}
                                    </a>
                                @endif
                                <a class="common_btn btn_danger delete_review" data-id="{{ $review->id }}"
                                    href="javascript:void(0)">
                                    {{ __('Delete Review') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="wsus__product_single_review">
                        <h4>{{ __('No reviews yet') }}</h4>
                    </div>
                @endforelse
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        {{ $reviews->onEachSide(0)->links('components::pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wsus__delete_modal">
        <div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"
            tabindex="-1">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="wsus__delete_modal">
                            <div class="wsus__delete_modal_text">
                                <h4>{{ __('Are you sure?') }}</h4>
                                <p>{{ __('Do you really want to delete this?') }}</p>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="wsus__delete_modal_btn">
                            <form id="deleteForm" action="" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn" data-bs-dismiss="modal" type="button">{{ __('Cancel') }}</button>
                                <button class="btn btn-danger" type="submit">{{ __('Delete') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        "use strict";

        $(document).on('click', '.delete_review', function() {
            var id = $(this).data('id');
            var url = '{{ route('website.user.reviews.delete', ':id') }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
            $('#deleteModal').modal('show');
        });
    </script>
@endpush
