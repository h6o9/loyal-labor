<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('seller.dashboard') }}"><img class="w-75" src="{{ asset($setting->logo) ?? '' }}"
                    alt="{{ $setting->app_name ?? '' }}"></a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('seller.dashboard') }}"><img src="{{ asset($setting->favicon) ?? '' }}"
                    alt="{{ $setting->app_name ?? '' }}"></a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">{{ __('Dashboard') }}</li>
            <li class="{{ isRoute('seller.dashboard', 'active') }}">
                <a class="nav-link" href="{{ route('seller.dashboard') }}"><i class="fas fa-home"></i>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </li>
            <li class="menu-header">{{ __('Manage Products') }}</li>

            <li class="{{ isRoute(['seller.product.*', 'seller.product-gallery*'], 'active') }}">
                <a class="nav-link" href="{{ route('seller.product.index') }}"><i
                        class="fas fa-newspaper"></i><span>{{ __('Product List') }}</span>
                </a>
            </li>

            <li class="{{ isRoute(['seller.product-review', 'seller.show-product-review'], 'active') }}">
                <a class="nav-link" href="{{ route('seller.product-review') }}"><i
                        class="fas fa-newspaper"></i><span>{{ __('Product Reviews') }}</span>
                </a>
            </li>

            <li class="{{ isRoute(['seller.products.product-prices*'], 'active') }}">
                <a class="nav-link" href="{{ route('seller.products.product-prices') }}">
                    <i
                        class="fas fa-newspaper"></i><span>{{ __('Product Prices') }}</span>
                </a>
            </li>

            <li class="{{ isRoute(['seller.products.product-inventory*'], 'active') }}">
                <a class="nav-link" href="{{ route('seller.products.product-inventory') }}">
                    <i
                        class="fas fa-newspaper"></i><span>{{ __('Product Inventory') }}</span>
                </a>
            </li>

            {{-- <li class="{{ isRoute(['seller.products.product-return-policy*'], 'active') }}">
                <a class="nav-link" href="{{ route('seller.products.product-return-policy') }}">
                    <i
                        class="fas fa-newspaper"></i><span>{{ __('Return Policy') }}</span>
                </a>
            </li> --}}

            <li class="menu-header">{{ __('Manage Orders') }}</li>

            <li class="{{ isRoute(['seller.orders.*'], 'active') }}">
                <a class="nav-link" href="{{ route('seller.orders.index') }}">
                    <i
                        class="fas fa-newspaper"></i>
                    <span>{{ __('All Orders') }}</span>
                </a>
            </li>

            @if (Module::isEnabled('KnowYourClient') && Route::has('seller.kyc.index'))
                @include('knowyourclient::seller.sidebar')
            @endif

            <li class="menu-header">{{ __('Manage Wallet') }}</li>
            <li>
                <a href="javascript:;" role="button" rel="nofollow"><i class="fas fa-money-check-alt"></i>
                    <span>{{ __('Balance') }}</span>
                    {{ currency(auth()->user()->wallet_balance ?? 0) }}</a>
            </li>
            <li class="{{ isRoute(['seller.my-withdraw.*'], 'active') }}">
                <a class="nav-link" href="{{ route('seller.my-withdraw.index') }}">
                    <i
                        class="fas fa-wallet"></i>
                    <span>{{ __('Withdraw Money') }}</span>
                </a>
            </li>
            <li class="menu-header">{{ __('Manage Shop') }}</li>
            <li class="{{ isRoute(['seller.shop-profile'], 'active') }}">
                <a class="nav-link" href="{{ route('seller.shop-profile') }}">
                    <i
                        class="fas fa-wallet"></i>
                    <span>{{ __('Shop Profile') }} @if (auth()->user()->seller->is_verified ?? false)
                            <i class="fas fa-check-circle text-success"></i>
                        @else
                            <i class="fas fa-times-circle text-danger"></i>
                        @endif
                    </span>
                </a>
            </li>
        </ul>
    </aside>
</div>
