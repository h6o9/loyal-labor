@if (Module::isEnabled('KnowYourClient') && Route::has('seller.kyc.index'))
    <li class="menu-header">{{ __('Shop Verification') }}</li>

    <li class="{{ isRoute(['seller.kyc.*'], 'active') }}">
        <a class="nav-link" href="{{ route('seller.kyc.index') }}">
            <i
                class="far fa-id-card"></i>
            <span>{{ __('KYC Verification') }}</span>
        </a>
    </li>
@endif
