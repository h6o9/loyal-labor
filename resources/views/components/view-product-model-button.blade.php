@props([
    'product' => null,
])

<a class="view_single_product_modal" data-id="{{ $product->id }}" href="javascript:;" role="button" tabindex="-1"
    rel="nofollow">
    <img
        class="img-fluid w-100" src="{{ asset('website/images/view.webp') }}" alt="icon">
</a>
