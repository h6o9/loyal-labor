<li
    class="nav-item dropdown {{ isRoute(['admin.orders*', 'admin.order', 'admin.pending-payment', 'admin.rejected-payment', 'admin.pending-orders'], 'active') }}">
    @php
        $pendingStatusCount = orderStatusCount('pending');
        $pendingPaymentCount = orderPaymentStatusCount('pending');
    @endphp
    <a class="nav-link has-dropdown" data-toggle="dropdown" href="#"><i class="fas fa-clipboard-list"></i>
        <span
            class="{{ $pendingStatusCount > 0 || $pendingPaymentCount > 0 ? 'beep-warning' : '' }}">{{ __('Manage Order') }}
        </span>

    </a>
    <ul class="dropdown-menu">

        <li class="{{ isRoute(['admin.orders', 'admin.order', 'admin.orders.invoice'], 'active') }}"><a
                class="nav-link" href="{{ route('admin.orders') }}">{{ __('Order History') }}</a>
        </li>

        <li class="{{ isRoute('admin.pending-orders', 'active') }}"><a
                class="nav-link {{ $pendingStatusCount > 0 ? 'beep beep-sidebar' : '' }}"
                href="{{ route('admin.pending-orders') }}">{{ __('Pending Order') }}</a></li>

        <li class="{{ isRoute('admin.pending-payment', 'active') }}"><a
                class="nav-link {{ $pendingPaymentCount > 0 ? 'beep beep-sidebar' : '' }}"
                href="{{ route('admin.pending-payment') }}">{{ __('Pending Payment') }}</a></li>

        <li class="{{ isRoute('admin.rejected-payment', 'active') }}"><a class="nav-link"
                href="{{ route('admin.rejected-payment') }}">{{ __('Rejected Payment') }}</a></li>

        <li class="{{ isRoute('admin.orders.all-transactions', 'active') }}"><a class="nav-link"
                href="{{ route('admin.orders.all-transactions') }}">{{ __('Transaction History') }}</a>
        </li>

        <li class="{{ isRoute('admin.orders.all-status-updates', 'active') }}">
            <a class="nav-link" href="{{ route('admin.orders.all-status-updates') }}">
                {{ __('Status Update History') }}
            </a>
        </li>
    </ul>
</li>
