@php
    $cronNotWorking =
        $setting->is_queueable == config('services.default_status.active_text') &&
        Cache::get('corn_working') !== 'working';
    $isMissingCredentials = function_exists('checkCredentials') && count(checkCredentials()) > 0;

    $countCron = $cronNotWorking ? 1 : 0 ?? 0;
    $countMissing = $isMissingCredentials ? count(checkCredentials()) : 0 ?? 0;
    $totalNotifications = count($adminNotifications) + $countCron + $countMissing ?? 0;

@endphp
@if ($cronNotWorking || $isMissingCredentials || count($adminNotifications) > 0)
    <li class="dropdown dropdown-list-toggle">
        <a class="nav-link notification-toggle nav-link-lg {{ $cronNotWorking || $isMissingCredentials || count($adminNotifications) > 0 ? 'beep' : '' }}"
            data-bs-toggle="dropdown" href="#" aria-expanded="false"><i class="far fa-bell"></i></a>
        <div class="dropdown-menu dropdown-list dropdown-menu-end">
            <div class="dropdown-header d-flex justify-content-between align-items-center">
                <span>
                    {{ __('Notifications') }}({{ $totalNotifications }})
                </span>
                <div>
                    @if (count($adminNotifications) > 0)
                        <a class="btn btn-success btn-sm p-1" href="{{ route('admin.notifications.mark-as-read') }}"
                            title="{{ __('Mark All As Read') }}"><i class="fas fa-check-circle"></i></a>
                    @endif
                    <a class="btn btn-info btn-sm py-0 px-2" href="{{ route('admin.settings') }}"
                        title="{{ __('Manage Settings') }}"><i class="fas fa-sliders-h"></i></a>
                    <a class="btn btn-warning btn-sm py-0 px-2" href="{{ route('admin.notifications.index') }}"
                        title="{{ __('Admin Notifications') }}"><i class="fas fa-bell"></i></a>
                </div>
            </div>

            <div class="dropdown-list-content dropdown-list-icons" tabindex="4" @style(['overflow: hidden; outline: none;'])>
                @if ($cronNotWorking)
                    <a class="dropdown-item" href="{{ route('admin.general-setting') }}">
                        <div class="dropdown-item-icon bg-danger text-white">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="dropdown-item-desc">
                            {{ __('Corn Job Is Not Running! Many features will be disabled and face errors') }}
                            @if (Cache::has('cronjob_log') && !is_null(Cache::get('cronjob_log')))
                                <div class="time text-primary">{{ formattedDateTime(Cache::get('cronjob_log')) }}</div>
                            @endif
                        </div>
                    </a>
                @endif

                @forelse ($adminNotifications->take(10) as $notification)
                    <a class="dropdown-item dropdown-item-unread"
                        href="{{ route('admin.notifications.show', $notification->id) }}">
                        @php
                            $type = $notification->type == 'order' ? 'warning' : $notification->type;
                        @endphp
                        <div class="dropdown-item-icon bg-{{ $type }} text-white">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="dropdown-item-desc">
                            <span><strong>[{{ str(htmlDecode($notification->title))->limit(40) }}]</strong></span>
                            <p>{{ str(htmlDecode($notification->message))->limit(100) }}</p>
                            <div class="time">{{ $notification->created_at?->diffForHumans() }}</div>
                        </div>
                    </a>
                @empty
                @endforelse

                @if (Module::isEnabled('Installer') &&
                        (function_exists('showUpdateAvailablity') && ($updateAvailablity = showUpdateAvailablity())))
                    @if ($updateAvailablity->status)
                        <a class="dropdown-item" href="{{ $updateAvailablity->url }}">
                            <div class="dropdown-item-icon bg-info text-white">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                {{ $updateAvailablity->message ?? '' }}
                                <div class="time">{{ __('Last Update: ') }}{{ $setting->last_update_date ?? '' }}
                                </div>
                            </div>
                        </a>
                    @endif
                @endif

                @if ($isMissingCredentials)
                    @foreach (checkCredentials() as $key => $checkCrentials)
                        @if ($checkCrentials->status)
                            <a class="dropdown-item"
                                href="{{ !empty($checkCrentials->route) ? route($checkCrentials->route ?? 'admin.settings') : url($checkCrentials->url ?? '#') }}">
                                <div class="dropdown-item-icon bg-warning text-white">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="dropdown-item-desc">
                                    <span><strong>[{{ str($key)->title() }}]</strong>
                                        {{ $checkCrentials->message ?? '' }}</span>
                                    <p>{{ $checkCrentials->description ?? '' }}</p>
                                </div>
                            </a>
                        @endif
                    @endforeach
                @endif
            </div>

            @if (count($adminNotifications) > 0)
                <div class="dropdown-footer text-center">
                    <a href="{{ route('admin.notifications.index') }}">{{ __('View All Notifications') }} <i
                            class="fas fa-chevron-right"></i></a>
                </div>
            @endif
            <div class="nicescroll-rails nicescroll-rails-vr" id="ascrail2002" @style(['width: 9px; z-index: 1000; cursor: default; position: absolute; top: 57.9814px; left: 341px; height: 350px; opacity: 0.3; display: none;'])>
                <div class="nicescroll-cursors position-relative" @style(['position: relative; top: 0px; float: right; width: 7px; height: 176px; background-color: rgb(66, 66, 66); border: 1px solid rgb(255, 255, 255); background-clip: padding-box; border-radius: 5px;'])>
                </div>
            </div>
            <div class="nicescroll-rails nicescroll-rails-hr" id="ascrail2002-hr" @style(['height: 9px; z-index: 1000; top: 398.981px; left: 0px; position: absolute; cursor: default; display: none; width: 341px; opacity: 0.3;'])>
                <div class="nicescroll-cursors" @style(['position: absolute; top: 0px; height: 7px; width: 350px; background-color: rgb(66, 66, 66); border: 1px solid rgb(255, 255, 255); background-clip: padding-box; border-radius: 5px; left: 0px;'])>
                </div>
            </div>
        </div>
    </li>
@endif
