@php
    $averageRating = round($product->reviews->avg('rating'), 1);
    $fullStars = floor($averageRating);
    $hasHalfStar = $averageRating - $fullStars >= 0.5;
@endphp

@if ($product?->reviews?->count() > 0)
    <span class="rating">
        @for ($i = 0; $i < $fullStars; $i++)
            <i class="fas fa-star" aria-hidden="true"></i>
        @endfor

        @if ($hasHalfStar)
            <i class="fas fa-star-half-alt" aria-hidden="true"></i>
        @endif

        @for ($i = 0; $i < 5 - $fullStars - ($hasHalfStar ? 1 : 0); $i++)
            <i class="far fa-star" aria-hidden="true"></i>
        @endfor

        <b>({{ $product->reviews->count() ?? 0 }} {{ __('Reviews') }})</b>
    </span>
@endif

<h6 id="show_price"
    data-original-price="@if ($product->discounted_price->is_discounted) {{ currency($product->discounted_price->discounted_price) }}<del>{{ currency($product->discounted_price->price) }}</del>@else{{ currency($product->discounted_price->price) }} @endif">
    @if ($product->discounted_price->is_discounted)
        {{ currency($product->discounted_price->discounted_price) }}
        <del>{{ currency($product->discounted_price->price) }}</del>
    @else
        {{ currency($product->discounted_price->price) }}
    @endif
</h6>
<p>{{ $product->short_description }}</p>

@if ($product->hasVariant)
    @foreach ($product->attribute_and_values as $attributes)
        <ul
            class="{{ in_array($attributes['attribute_slug'], ['color', 'colors']) ? 'details_color' : 'details_size' }} d-flex flex-wrap">
            @foreach ($attributes['attribute_values'] as $value)
                @if (in_array($attributes['attribute_slug'], ['color', 'colors']))
                    <li class="attr{{ $value['is_default'] ? ' active selectedAttr' : '' }}"
                        data-id="{{ $value['id'] }}" data-image="{{ $value['image'] }}"
                        style="background: {{ $value['value'] }};">
                    </li>
                @else
                    <li class="attr{{ $value['is_default'] ? ' active selectedAttr' : '' }}"
                        data-id="{{ $value['id'] }}">
                        {{ $value['value'] }}</li>
                @endif
            @endforeach
        </ul>
    @endforeach
@endif

<form class="wsus__modal_filter mt_25 add_to_cart_form" id="add_to_cart_form_p{{ $product->id }}"
    data-product-id="{{ $product->id }}" action="javascript:;">
    <input name="product_id" type="hidden" value="{{ $product->id }}">
    <input id="sku" name="sku" type="hidden" value="{{ $product->sku }}">
    <input id="modal_price" name="price" type="hidden"
        value="{{ $product->discounted_price->discounted_price ?? $product->price }}">
    <input id="variant_price" name="variant_price" type="hidden"
        value="{{ $product->discounted_price->is_discounted ? $product->discounted_price->discounted_price : $product->discounted_price->price }}">
    <input id="is_variant" name="is_variant" type="hidden" value="0">
    <input id="variant_sku" name="variant_sku" type="hidden" value="{{ $product->sku }}">
    <div class="number">
        <input name="qty" type="number" value="1" placeholder="1" accept="number">
        <ul>
            <li>
                <button class="increase-btn" type="button"><i class="fal fa-angle-up" aria-hidden="true"></i></button>
            </li>
            <li>
                <button class="decrease-btn" type="button"><i class="fal fa-angle-down"
                        aria-hidden="true"></i></button>
            </li>
        </ul>
    </div>
    <a class="wishlist add_to_wishlist" data-id="{{ $product->id }}" href="javascript:;">
        <img class="img-fluid w-100" src="{{ asset('website/images/react.webp') }}" alt="{{ __('Wishlist') }}"
            loading="lazy">
    </a>
    <button class="add_cart" data-product-id="{{ $product->id }}">
        <img class="img-fluid w-100" src="{{ asset('website/images/cart.webp') }}" alt="{{ __('Add To Cart') }}"
            loading="lazy">
        {{ __('Add To Cart') }}
    </button>
</form>
<ul class="ul_3 details">
    <li>{{ __('SKU') }}: <span class="sku"> {{ $product->sku }}</span></li>
    <li>{{ __('Categories') }}:
        <span>{{ $product?->categories->pluck('name')->implode(', ') ?? '' }}</span>
    </li>
    <li>{{ __('Tags') }}: <span>{{ $product?->tags->pluck('name')->implode(', ') ?? '' }}</span></li>
</ul>
