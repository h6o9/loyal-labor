@php
    session()->put('tax_amount', WsusCart::getCartTotalTax());
    session()->put('amount', WsusCart::getCartSubTotal());
@endphp

<ul class="d-flex flex-wrap justify-content-between">
    <li><a class="common_btn" href="{{ route('website.products') }}">{{ __('Continue Shopping') }}</a></li>
    @if (!empty($changedItems))
        <li><a class="common_btn update_cart" href="{{ route('website.cart.resync') }}">{{ __('Update Cart') }}</a></li>
    @endif
</ul>
<div class="list_item">
    <p class="subtotal">{{ __('subtotal') }} <span>{{ currency(WsusCart::getCartSubTotal()) }}</span></p>
    <p class="discount">{{ __('Coupon Discount') }} <span>{{ currency(WsusCart::getCouponDiscount()) }}</span></p>
    <p class="tax">{{ __('Tax') }} <span>{{ currency(WsusCart::getCartTotalTax()) }}</span></p>
    <p class="total">{{ __('Total') }} <span>{{ currency(WsusCart::getCartTotal()) }}</span></p>
    <small
        class="text-start">({{ session('free_shipping') ? __('Free shipping') : __('Shipping fees not included') }})</small>
    @if (!empty($changedItems))
        <a class="common_btn show_update_price_alert" href="javascript:;">{{ __('Proceed to Checkout') }}</a>
    @else
        <a class="common_btn" href="{{ route('website.checkout') }}">{{ __('Proceed to Checkout') }}</a>
    @endif
</div>
