        @php
            $pendingCount = pendingWithdrawRequests();
        @endphp

        <li
            class="nav-item dropdown {{ isRoute(['admin.withdraw-method.*', 'admin.withdraw-list', 'admin.show-withdraw', 'admin.pending-withdraw-list'], 'active') }}">
            <a class="nav-link has-dropdown" href="#"><i
                    class="far fa-newspaper"></i><span
                    class="{{ $pendingCount > 0 ? 'beep-warning' : '' }}">{{ __('Withdraw Payment') }}</span></a>

            <ul class="dropdown-menu">

                <li class="{{ isRoute('admin.withdraw-list', 'active') }}"><a class="nav-link"
                        href="{{ route('admin.withdraw-list') }}">{{ __('Withdraw list') }}</a></li>

                <li class="{{ isRoute('admin.pending-withdraw-list', 'active') }}"><a
                        class="nav-link {{ $pendingCount > 0 ? 'beep beep-sidebar' : '' }}"
                        href="{{ route('admin.pending-withdraw-list') }}">{{ __('Pending Withdraw') }}</a></li>

                <li class="{{ isRoute('admin.withdraw-method.*', 'active') }}"><a class="nav-link"
                        href="{{ route('admin.withdraw-method.index') }}">{{ __('Withdraw Method') }}</a></li>

            </ul>
