@php
    [$notifications, $totalNotifications] = vendorNotifications();
@endphp

<li class="dropdown dropdown-list-toggle">
    <a class="nav-link notification-toggle nav-link-lg {{ count($notifications) > 0 ? 'beep' : '' }}"
        data-bs-toggle="dropdown" href="#" aria-expanded="false"><i class="far fa-bell"></i></a>
    <div class="dropdown-menu dropdown-list dropdown-menu-end">
        <div class="dropdown-header d-flex justify-content-between">
            <span>
                {{ __('Notifications') }}({{ Number::abbreviate($totalNotifications) }})
            </span>
            <div>
                <a class="btn btn-warning btn-sm" href="{{ route('seller.all-notifications') }}"
                    title="{{ __('All Notifications') }}"><i class="fas fa-bell"></i></a>
            </div>
        </div>

        <div class="dropdown-list-content dropdown-list-icons" tabindex="4" @style(['overflow: hidden; outline: none;'])>
            @forelse ($notifications as $notification)
                <a class="dropdown-item dropdown-item-unread"
                    href="{{ route('admin.notifications.show', $notification->id) }}">
                    <div
                        class="dropdown-item-icon bg-{{ $notification->type == 'order_status' ? 'success' : 'info' }} text-white">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="dropdown-item-desc">
                        <span><strong>[{{ str(htmlDecode($notification->type))->replace('_', ' ')->upper() }}]</strong></span>
                        <p>#{{ $notification->order->order_id ?? 'N/A' }}
                            {{ str(htmlDecode($notification->type))->replace('_', ' ')->lower() }}
                            {{ __('has been updated to') }} {{ $notification->to_status_enum->getLabel() ?? 'N/A' }}
                        </p>
                        <div class="time">{{ $notification->created_at?->diffForHumans() }}</div>
                    </div>
                </a>
            @empty
                <p class="dropdown-item">{{ __('No Notifications Found') }}</p>
            @endforelse
        </div>

        <div class="dropdown-footer text-center">
            <a href="{{ route('seller.all-notifications') }}">{{ __('View All Notifications') }} <i
                    class="fas fa-chevron-right"></i></a>
        </div>

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
