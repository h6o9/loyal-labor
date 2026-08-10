<li
    class="nav-item dropdown {{ isRoute('admin.wallet-history') || isRoute('admin.pending-wallet-payment') || isRoute('admin.show-wallet-history') || isRoute('admin.rejected-wallet-payment') ? 'active' : '' }}">
    <a class="nav-link has-dropdown" data-toggle="dropdown" href="#"><i class="fas fa-wallet"></i>
        <span>{{ __('Seller Wallets') }} </span>

    </a>
    <ul class="dropdown-menu">
        <li class="{{ isRoute(['admin.wallet-history', 'admin.show-wallet-history']) ? 'active' : '' }}"><a
                class="nav-link" href="{{ route('admin.wallet-history') }}">{{ __('Wallet History') }}</a></li>

        <li class="{{ isRoute('admin.pending-wallet-payment') ? 'active' : '' }}"><a class="nav-link"
                href="{{ route('admin.pending-wallet-payment') }}">{{ __('Pending Request') }}</a></li>

        <li class="{{ isRoute('admin.rejected-wallet-payment') ? 'active' : '' }}"><a class="nav-link"
                href="{{ route('admin.rejected-wallet-payment') }}">{{ __('Rejected Request') }}</a></li>

    </ul>
</li>
