<li
    class="nav-item dropdown {{ isRoute(['admin.all-customers', 'admin.active-customers', 'admin.non-verified-customers', 'admin.banned-customers', 'admin.customer-show', 'admin.send-bulk-mail'], 'active') }}">
    <a class="nav-link has-dropdown" href="javascript:void()">
        <i class="fas fa-users"></i><span>{{ __('Manage Customers') }}</span>
    </a>

    <ul class="dropdown-menu">
        <li class="{{ isRoute(['admin.all-customers', 'admin.customer-show'], 'active') }}">
            <a class="nav-link" href="{{ route('admin.all-customers') }}">
                {{ __('All Customers') }}
            </a>
        </li>

        <li class="{{ isRoute('admin.active-customers', 'active') }}">
            <a class="nav-link" href="{{ route('admin.active-customers') }}">
                {{ __('Active Customer') }}
            </a>
        </li>

        <li class="{{ isRoute('admin.non-verified-customers', 'active') }}">
            <a class="nav-link" href="{{ route('admin.non-verified-customers') }}">
                {{ __('Non verified') }}
            </a>
        </li>

        <li class="{{ isRoute('admin.banned-customers', 'active') }}">
            <a class="nav-link" href="{{ route('admin.banned-customers') }}">
                {{ __('Banned Customer') }}
            </a>
        </li>
        @adminCan('customer.bulk.mail')
            <li class="{{ isRoute('admin.send-bulk-mail', 'active') }}">
                <a class="nav-link" href="{{ route('admin.send-bulk-mail') }}">
                    {{ __('Send bulk mail') }}
                </a>
            </li>
        @endadminCan
    </ul>
</li>
