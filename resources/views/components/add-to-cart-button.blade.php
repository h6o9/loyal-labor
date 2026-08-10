@props([
    'product' => null,
    'quantity' => 1,
    'icon' => true,
    'label' => true,
])

@if ($product)
    @php
        $stockAvailable = true;

        if ($product->allow_checkout_when_out_of_stock != 0) {
            $stockAvailable = true;
        } else {
            if ($product->manage_stock) {
                if ($product->stock_status == 'out_of_stock') {
                    $stockAvailable = false;
                }

                if ($product->stock_qty < $quantity) {
                    $stockAvailable = false;
                }

                if ($product->has_default_variant && $product->default_variant_stock_qty < $quantity) {
                    $stockAvailable = false;
                }
            } else {
                $stockAvailable = true;
            }
        }

        if ($stockAvailable) {
            $class = ['add_to_cart'];
        } else {
            $class = ['add_to_cart', 'disabled'];
        }
    @endphp

    <a data-product-id="{{ $product->id }}" data-stock-qty="{{ $product->stock_qty ?? null }}"
        data-stock-managed="{{ $product->manage_stock ?? false }}"
        data-allow-checkout-without-stock="{{ $product->allow_checkout_when_out_of_stock != 0 ? 1 : 0 }}"
        data-stock-status="{{ !$product->manage_stock ? 'in_stock' : $product->stock_status }}"
        data-quantity="{{ $quantity }}" data-variant-price="{{ $product->default_variant_price ?? null }}"
        data-variant-discount_price="{{ $product->default_variant_discount_price ?? null }}"
        data-sku="{{ $product->sku }}" data-variant-sku="{{ $product->default_variant_sku ?? null }}" href="javascript:;"
        role="button" tabindex="-1" rel="nofollow" {{ $attributes->class($class) }}>
        @if ($icon)
            <img class="img-fluid w-100" src="{{ asset('website/images/cart.webp') }}"
                alt="{{ __('add to cart icon') }}">
        @endif
        @if ($label)
            <b>{{ $stockAvailable ? __('Add To Cart') : __('Out Of Stock') }}</b>
        @endif
    </a>
@endif
