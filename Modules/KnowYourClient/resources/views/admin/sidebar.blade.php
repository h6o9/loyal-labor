@if (Module::isEnabled('KnowYourClient'))
    <li class="nav-item dropdown {{ Route::is('admin.kyc.*') || Route::is('admin.kyc-list*') ? 'active' : '' }}">
        <a class="nav-link has-dropdown" href="#"><i
                class="far fa-id-card"></i><span>{{ __('Manage KYC') }}</span></a>

        <ul class="dropdown-menu">
            <li class="{{ Route::is('admin.kyc.*') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('admin.kyc.index') }}">{{ __('KYC Type') }}</a></li>

            <li class="{{ Route::is('admin.kyc-list*') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('admin.kyc-list.index') }}">{{ __('KYC Applications') }}</a></li>
        </ul>
    </li>
@endif
