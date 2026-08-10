<script>
    "use strict";

    const CSRF_TOKEN = "{{ csrf_token() }}";
    const ERROR_TEXT = "{{ __('Something went wrong') }}";
    const EMPTY_PRODUCT_TEXT = "{{ __('No product found') }}";
    const NO_STOCK_PRODUCT_TEXT = "{{ __('Product is Out of stock') }}";
    const EMAIL_NOT_FOUND = "{{ __('Please enter an email address.') }}";
    const TERMS_CHECKER = "{{ __('You must agree to the terms and conditions.') }}";
    const SUBSCRIPTION_SUCCESS = "{{ __('Subscription successful') }}";
    const DEMO_ERROR_TEXT = "{{ __('This Is Demo Version. You Can Not Change Anything') }}";

    const AUTH_STATUS = "{{ Auth::check() ? 1 : 0 }}";
    const HAS_APP = "{{ $setting->has_app }}";
    const isLanguageChangeAvailable = "{{ allLanguages()->where('status', 1)->count() > 1 }}";
    const isCurrencyChangeAvailable = "{{ allCurrencies()->where('status', 'active')->count() > 1 }}";
    const APP_MODE_DEMO = {{ strtolower(config('app.app_mode')) == 'demo' ? 'true' : 'false' }};
    const GTM_ON =
        {{ getSettingStatus('googel_tag_status') && getSettingStatus('marketing_status', 'int') ? 'true' : 'false' }};
    const FB_PIXEL_ON =
        {{ getSettingStatus('pixel_status') && getSettingStatus('marketing_status', 'int') ? 'true' : 'false' }};

    const setLanguageRoute = "{{ route('set-language') }}";
    const setCurrencyRoute = "{{ route('set-currency') }}";
    const ADD_TO_WISHLIST_ROUTE = "{{ route('website.wishlist.store') }}";
    const VIEW_SINGLE_PRODUCT = "{{ route('website.product-modal') }}";
    const ADD_TO_CART_ROUTE = "{{ route('website.cart.store') }}";
    const CHECKOUT_ROUTE = "{{ route('website.checkout') }}";

    const CURRENCY_CODE = '{{ getSessionCurrency() }}';
    const CURRENCY_ICON = '{{ session()->get('currency_icon') }}';
    const CURRENCY_RATE = '{{ session()->get('currency_rate', 1) }}';
    const CURRENCY_POSITION = '{{ session()->get('currency_position') }}';
</script>
