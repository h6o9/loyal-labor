<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}"><img class="w-75" src="{{ asset($setting->logo) ?? '' }}"
                    alt="{{ $setting->app_name ?? '' }}"></a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard') }}"><img src="{{ asset($setting->favicon) ?? '' }}"
                    alt="{{ $setting->app_name ?? '' }}"></a>
        </div>

        <ul class="sidebar-menu">
            @adminCan('dashboard.view')
                <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
            @endadminCan

            @if (Module::isEnabled('GlobalSetting'))
                @include('globalsetting::sidebar')
            @endif

            @if (checkAdminHasPermission('admin.view') || checkAdminHasPermission('role.view'))
                <li class="menu-header">{{ __('Administration Settings') }}</li>
                <li
                    class="nav-item dropdown {{ Route::is('admin.admin.*') || Route::is('admin.role.*') ? 'active' : '' }}">
                    <a class="nav-link has-dropdown" href="#"><i
                            class="fas fa-shield-alt"></i><span>{{ __('Admin & Roles') }}</span></a>
                    <ul class="dropdown-menu">
                        @adminCan('admin.view')
                            <li class="{{ Route::is('admin.admin.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.admin.index') }}">{{ __('Manage Admin') }}</a>
                            </li>
                        @endadminCan
                        @adminCan('role.view')
                            <li class="{{ Route::is('admin.role.*') ? 'active' : '' }}">
                                <a class="nav-link"
                                    href="{{ route('admin.role.index') }}">{{ __('Role & Permissions') }}</a>
                            </li>
                        @endadminCan
                    </ul>
                </li>
            @endif
            <!-- @if (Module::isEnabled('GlobalSetting') && checkAdminHasPermission('setting.view'))
                <li class="menu-header">{{ __('System Settings') }}</li>
                <li class="{{ Route::is('admin.addons.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.addons.view') }}"><i class="fas fa-plug"></i>
                        <span>{{ __('Manage Addons') }}</span>
                    </a>
                </li>
                <li class="{{ Route::is('admin.system-update.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.system-update.index') }}"><i
                            class="fas fa-arrow-circle-up"></i>
                        <span>{{ __('System Update') }}</span>
                    </a>
                </li>
            @endif -->
        </ul>
    </aside>
</div>
