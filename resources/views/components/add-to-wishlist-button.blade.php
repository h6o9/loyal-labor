@props([
    'product' => null,
])

<a class="add_to_wishlist" data-id="{{ $product->id }}" href="javascript:;" role="button" tabindex="-1" rel="nofollow"><img
        class="img-fluid w-100" src="{{ asset('website/images/react_2.webp') }}" alt="{{ __('Add To Wishlist') }}"></a>
