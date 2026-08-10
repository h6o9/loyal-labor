<li class="nav-item dropdown {{ isRoute(['admin.coupon.*', 'admin.coupon-history'], 'active') }}">
    <a class="nav-link has-dropdown" data-toggle="dropdown" href="#"><i class="fas fa-money-bill-wave"></i>
        <span>{{ __('Manage Coupon') }} </span>

    </a>
    <ul class="dropdown-menu">
        <li class="{{ isRoute(['admin.coupon.*'], 'active') }}"><a class="nav-link"
                href="{{ route('admin.coupon.index') }}">{{ __('Coupon List') }}</a></li>

        <li class="{{ isRoute(['admin.coupon-history'], 'active') }}"><a class="nav-link"
                href="{{ route('admin.coupon-history') }}">{{ __('Coupon History') }}</a></li>
    </ul>
</li>
