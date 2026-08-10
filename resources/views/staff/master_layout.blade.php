@php
    $staffUser = Auth::guard('staff')->user();
    $defaultAvatar = $setting->default_avatar ?? 'backend/img/avatar/avatar-1.png';
    $favicon = $setting->favicon ?? 'assets/images/favicon.png';
    $copyrightText = $setting->copyright_text ?? '© ' . date('Y') . ' All Rights Reserved.';
    $version = $setting->version ?? '1.0.0';
    $staffAvatar = ($staffUser && !empty($staffUser->image))
        ? asset($staffUser->image)
        : asset($defaultAvatar);
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <link type="image/x-icon" href="{{ asset($favicon) }}" rel="shortcut icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('title')
    @include('admin.partials.styles')
    @stack('css')
    <style>
        /* Staff header: dark text on white navbar (was white-on-white = invisible) */
        .main-navbar .staff-panel-title {
            margin: 0;
            padding: 0;
            color: #34395e !important;
            font-size: 20px;
            font-weight: 600;
            white-space: nowrap;
        }
        .main-navbar .dropdown_user img {
            width: 36px;
            height: 36px;
            object-fit: cover;
            border: 2px solid #e3e6ef;
        }
        .main-navbar .dropdown_user .staff-header-name {
            color: #34395e !important;
            font-weight: 600;
            margin-left: 6px;
        }
    </style>
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar px-3 py-2">
                <div class="me-2 form-inline">
                    <ul class="navbar-nav d-flex align-items-center">
                        <li><a class="nav-link nav-link-lg" data-toggle="sidebar" href="#"><i
                                    class="fas fa-bars"></i></a></li>
                        <p class="staff-panel-title">Loy Madok Panel</p>
                    </ul>
                </div>
                <div class="mr-auto me-md-auto search-box position-relative">
                    <div class="position-absolute d-none rounded-2" id="admin_menu_list">
                        <a class="not-found-message d-none" href="javascript:;">{{ __('Not Found!') }}</a>
                    </div>
                </div>

                <ul class="navbar-nav align-items-center">
                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle nav-link-lg nav-link-user dropdown_user d-flex align-items-center"
                            data-bs-toggle="dropdown" href="javascript:;">
                            <img class="me-1 rounded-circle" src="{{ $staffAvatar }}"
                                alt="{{ $staffUser->name ?? 'Staff' }}"
                                onerror="this.onerror=null;this.src='{{ asset('backend/img/avatar/avatar-1.png') }}';">
                            <div class="d-none d-sm-inline-block staff-header-name">
                                {{ $staffUser->name ?? __('Staff') }}
                            </div>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            @if($staffUser)
                                <div class="dropdown-item-text px-3 py-2 border-bottom">
                                    <strong>{{ $staffUser->name }}</strong><br>
                                    <small class="text-muted">{{ $staffUser->email }}</small>
                                </div>
                                <a class="dropdown-item has-icon d-flex align-items-center {{ isRoute(['staff.edit-profile'], 'text-primary') }}"
                                    href="{{ route('staff.edit-profile') }}">
                                    <i class="far fa-user"></i> {{ __('Profile') }}
                                </a>
                                <a class="dropdown-item has-icon d-flex align-items-center {{ isRoute(['staff.dashboard'], 'text-primary') }}"
                                    href="{{ route('staff.dashboard') }}">
                                    <i class="fas fa-chart-line"></i> {{ __('Dashboard') }}
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item has-icon d-flex align-items-center" href="javascript:;"
                                    onclick="event.preventDefault(); $('#staff-logout-form').trigger('submit');">
                                    <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                </a>
                            @else
                                <a class="dropdown-item has-icon d-flex align-items-center text-muted" href="javascript:;">
                                    <i class="fas fa-exclamation-circle"></i> {{ __('Not logged in') }}
                                </a>
                            @endif
                        </div>
                    </li>
                </ul>
            </nav>

            @include('staff.sidebar')

            @yield('staff-content')

            <footer class="main-footer">
                <div class="footer-left">
                    Home-Services-Technicians-Plumbers
                </div>
            </footer>

        </div>
    </div>

    @if($staffUser)
        <form class="d-none" id="staff-logout-form" action="{{ route('staff.logout') }}" method="POST">
            @csrf
        </form>
    @endif

    <x-admin.delete-modal />

    @stack('modals')

    @include('admin.partials.javascripts')
    @include('admin.js-variables')
    @stack('js')

</body>

</html>
