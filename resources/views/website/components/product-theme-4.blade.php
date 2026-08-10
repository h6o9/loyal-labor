@php
    $product = $product->load(['reviews', 'labels.translation', 'variants', 'manageStocks']);
@endphp

<div class="wsus__product_item">
    <div class="img">
        <img class="img-fluid w-100" src="{{ asset($product->thumbnail_image) }}" alt="product">
    </div>
    <span
        class="new">{{ $product->is_flash_deal_active ? __('Flash Sale') : $product->labels->first()->name ?? '' }}</span>
    <div class="text">
        <span>
            <span class="rating">
                @php
                    $averageRating = round($product->reviews->avg('rating'), 1);
                    $fullStars = floor($averageRating);
                    $hasHalfStar = $averageRating - $fullStars >= 0.5;
                @endphp

                {{-- Full stars --}}
                @for ($i = 0; $i < $fullStars; $i++)
                    <i class="fas fa-star" aria-hidden="true"></i>
                @endfor

                {{-- Half star if needed --}}
                @if ($hasHalfStar)
                    <i class="fas fa-star-half-alt" aria-hidden="true"></i>
                @endif

                {{-- Empty stars to make total 5 --}}
                @for ($i = 0; $i < 5 - $fullStars - ($hasHalfStar ? 1 : 0); $i++)
                    <i class="far fa-star" aria-hidden="true"></i>
                @endfor
                <b>({{ $product->reviews->count() ?? 0 }} {{ __('Reviews') }})</b>
            </span>
        </span>
        <a class="title" href="{{ route('website.product', ['product' => $product->slug]) }}"
            tabindex="0">{{ $product->name }}</a>
        <h5>
            @if ($product->discounted_price->is_discounted)
                {{ currency($product->discounted_price->discounted_price) }}
                <del>{{ currency($product->discounted_price->price) }}</del>
            @else
                {{ currency($product->discounted_price->price ?? $product->price) }}
            @endif
        </h5>
        <ul>
            <li>
                <x-add-to-wishlist-button :product="$product" />
            </li>
            <li>
                <x-add-to-cart-button :product="$product" />
            </li>
            <li>
                <x-view-product-model-button :product="$product" />
            </li>
        </ul>
    </div>
</div>
