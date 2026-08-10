@if (checkAdminHasPermission('setting.view') ||
        checkAdminHasPermission('language.view') ||
        checkAdminHasPermission('sitemap.management'))
    <li class="menu-header">Settings</li>
@endif
@if (checkAdminHasPermission('setting.view'))
    <!-- <li class="{{ Route::is('admin.general-setting') ? 'active' : '' }}"><a class="nav-link"
            href="{{ route('admin.general-setting') }}"><i class="fas fa-cog"></i>
            <span>{{ __('General Settings') }}</span></a></li> -->

    <!-- Email Configuration URL: /admin/email-configuration  (route: admin.email-configuration) -->
    <!-- Live: https://ranglerzbeta.in/homeservices/admin/email-configuration -->
    <!-- <li class="{{ Route::is('admin.email-configuration') || Route::is('admin.edit-email-template') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.email-configuration') }}"><i class="fas fa-envelope"></i>
            <span>{{ __('Email Configuration') }}</span>
        </a>
    </li> -->

    <!-- <li class="{{ Route::is('admin.ecom_setup.index') ? 'active' : '' }}"><a class="nav-link"
            href="{{ route('admin.ecom_setup.index') }}"><i class="fas fa-shopping-cart"></i>
            <span>{{ __('Ecommerce Setup') }}</span></a></li>

    <li class="{{ Route::is('admin.crediential-setting') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.crediential-setting') }}"><i class="fas fa-key"></i>
            <span>{{ __('Credential Settings') }}</span>
        </a>
    </li>
@endif

@if (Module::isEnabled('Language') && checkAdminHasPermission('language.view'))
    @include('language::sidebar')
@endif

@if (checkAdminHasPermission('setting.view'))
    <li class="{{ Route::is('admin.seo-setting') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.seo-setting') }}"><i class="fas fa-search"></i>
            <span>{{ __('SEO Setup') }}</span>
        </a>
    </li>
@endif

@if (Module::isEnabled('Sitemap') && checkAdminHasPermission('sitemap.management'))
    @include('sitemap::sidebar')
@endif

@if (checkAdminHasPermission('setting.view') ||
        checkAdminHasPermission('basic.payment.view') ||
        checkAdminHasPermission('currency.view') ||
        checkAdminHasPermission('tax.view') ||
        checkAdminHasPermission('payment.view'))
    <li class="menu-header">{{ __('Extra Settings') }}</li>
@endif
@if (checkAdminHasPermission('basic.payment.view') || checkAdminHasPermission('payment.view'))
    <li class="{{ Route::is('admin.basicpayment') ? 'active' : '' }}"><a class="nav-link"
            href="{{ route('admin.basicpayment') }}">
            <i class="fas fa-credit-card"></i>
            <span>{{ __('Payment Methods') }}</span>
        </a>
    </li>
@endif

@if (Module::isEnabled('Currency') && checkAdminHasPermission('currency.view'))
    @include('currency::sidebar')
@endif

@if (Module::isEnabled('Tax') && checkAdminHasPermission('tax.view'))
    @include('tax::sidebar')
@endif
@if (checkAdminHasPermission('setting.view'))
    <li class="nav-item dropdown {{ isRoute('admin.custom-code', 'active') }}">
        <a class="nav-link has-dropdown" href="javascript:;">
            <i class="fas fa-code"></i><span>{{ __('Custom Code') }}</span>
        </a>
        <ul class="dropdown-menu">
            <li class="{{ request()->routeIs('admin.custom-code') && request('type') == 'css' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.custom-code', 'css') }}">
                    {{ __('CSS') }}
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.custom-code') && request('type') == 'js' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.custom-code', 'js') }}">
                    {{ __('JS') }}
                </a>
            </li>
        </ul>
    </li>

    <li class="{{ Route::is('admin.cache-clear') ? 'active' : '' }}"><a class="nav-link"
            href="{{ route('admin.cache-clear') }}"><i class="fas fa-sync"></i>
            <span>{{ __('Clear cache') }}</span>
        </a></li>

    <li class="{{ Route::is('admin.database-clear') ? 'active' : '' }}"><a class="nav-link"
            href="{{ route('admin.database-clear') }}"><i class="fas fa-database"></i>
            <span>{{ __('Database Clear') }}</span></a></li>
@endif -->
