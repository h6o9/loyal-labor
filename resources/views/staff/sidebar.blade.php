<style>
    .main-sidebar {
        height: 100vh;
        overflow-y: auto;
        background: #ffffff;
    }

    #sidebar-wrapper {
        height: 100%;
        overflow-y: auto;
    }

    .sidebar-brand {
        padding: 20px 15px;
        text-align: center;
        border-bottom: 1px solid #dee2e6;
    }

    .sidebar-brand a {
        display: inline-block;
    }

    .sidebar-brand img {
        max-width: 180px;
        border-radius: 8px;
    }

    .sidebar-brand-sm {
        display: none;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0 0 20px 0;
        margin: 0;
    }

    .sidebar-menu .menu-header {
        margin: 15px 0 5px;
        padding: 5px 20px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #6c757d;
        font-weight: 600;
    }

    .sidebar-menu li {
        position: relative;
        list-style: none;
    }

    .sidebar-menu li a {
        display: flex;
        align-items: center;
        padding: 10px 20px;
        color: #333333;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.2s;
    }

    .sidebar-menu li a:hover {
        background: #e9ecef;
        color: #000;
    }

    .sidebar-menu li a.active {
        background: #007bff;
        color: white;
    }

    .sidebar-menu li a i {
        width: 24px;
        margin-right: 10px;
        text-align: center;
    }

    .sidebar-menu li a span {
        flex: 1;
        white-space: nowrap;
    }

    /* Silver tree lines REMOVED */
    .sidebar-menu li::before {
        display: none;
    }

    /* Dropdown Styles */
    .dropdown-parent {
        position: relative;
    }

    .dropdown-toggle {
        cursor: pointer;
    }

    .dropdown-arrow {
        margin-left: auto;
        transition: transform 0.25s ease;
        font-size: 12px;
    }

    .dropdown-toggle[aria-expanded="true"] .dropdown-arrow {
        transform: rotate(90deg);
    }

    .submenu {
        list-style: none;
        padding-left: 0;
        margin: 0;
        display: none;
        background: #f8f9fa;
    }

    .submenu li a {
        padding: 8px 20px 8px 54px;
        font-size: 13px;
        color: #555555;
    }

    .submenu li a i {
        width: 20px;
        font-size: 13px;
    }

    .submenu li a.active {
        background: #007bff;
        color: white;
    }

    /* Badge styles */
    .badge-danger {
        background-color: #dc3545;
        color: white;
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 30px;
        margin-left: auto;
    }

    .ml-auto {
        margin-left: auto;
    }

    /* Custom scrollbar */
    .main-sidebar::-webkit-scrollbar {
        width: 5px;
    }

    .main-sidebar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .main-sidebar::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 5px;
    }
</style>

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<div class="main-sidebar">
    <aside id="sidebar-wrapper">

        <!-- Logo -->
        <div class="sidebar-brand">
            <a href="{{ route('staff.dashboard') }}">
                <img class="w-75" src="{{ asset('public/backend/img/admin-auth-bg.png') }}">
            </a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('staff.dashboard') }}">
                <img src="{{ asset('public/backend/img/admin-auth-bg.png') }}">
            </a>
        </div>

        <ul class="sidebar-menu">

            <!-- Dashboard -->
            <li class="{{ isRoute('staff.dashboard', 'active') }}">
                <a href="{{ route('staff.dashboard') }}">
                    <i class="fas fa-home"></i>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </li>

            <!-- ================= SHOP MANAGEMENT DROPDOWN ================= -->
            @if(auth('staff')->user()->hasPermission('shop_management', 'can_view'))
                <li class="menu-header">{{ __('Shop Management') }}</li>

                <li class="dropdown-parent">
                    <a href="javascript:void(0);" class="dropdown-toggle" aria-expanded="false">
                        <i class="fas fa-store"></i>
                        <span>{{ __('Shop Operations') }}</span>
                        <i class="fas fa-chevron-right dropdown-arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li class="{{ isRoute('staff.shop.index', 'active') }}">
                            <a href="{{ route('staff.shop.index') }}">
                                <i class="fas fa-list"></i>
                                <span>{{ __('Shop List') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            <!-- ================= MY JOBS SECTION (SEPARATE) ================= -->
            <li class="menu-header">{{ __('Jobs Management') }}</li>

            <li class="dropdown-parent">
                <a href="javascript:void(0);" class="dropdown-toggle" aria-expanded="false">
                    <i class="fas fa-briefcase"></i>
                    <span>{{ __('Jobs') }}</span>
                    <i class="fas fa-chevron-right dropdown-arrow"></i>
                </a>

                <ul class="submenu">
                    <li class="{{ isRoute('staff.jobs.index', 'active') }}">
                        <a href="{{ route('staff.jobs.index') }}">
                            <i class="fas fa-tasks"></i>
                            <span>{{ __('My Jobs') }}</span>

                            @php
                                $pendingJobsCount = \App\Models\StaffJob::where('assigned_to', Auth::guard('staff')->id())
                                    ->where('status', 'pending')
                                    ->count();
                            @endphp

                            @if($pendingJobsCount > 0)
                                                            <span class="badge-danger" style="
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    width:26px;
                                    height:26px;
                                    background-color:#dc3545;
                                    color:#fff;
                                    font-size:12px;
                                    font-weight:600;
                                    border-radius:50%;
                                    margin-left:auto;
                                    flex:0 0 36px;
                                    align-self:center;
                                  ">{{ $pendingJobsCount }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
</div>

<script>
    $(document).ready(function () {

        // Close all dropdowns function
        function closeAllDropdowns() {
            $('.submenu').slideUp(200);
            $('.dropdown-toggle').attr('aria-expanded', 'false');
        }

        // Toggle dropdown on click with smooth slide animation
        $('.dropdown-toggle').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var $this = $(this);
            var $parent = $this.closest('.dropdown-parent');
            var $submenu = $parent.find('.submenu');
            var isExpanded = $this.attr('aria-expanded') === 'true';

            if (isExpanded) {
                // Close this dropdown
                $submenu.slideUp(200);
                $this.attr('aria-expanded', 'false');
            } else {
                // Close all other dropdowns (accordion behavior)
                $('.submenu').not($submenu).slideUp(200);
                $('.dropdown-toggle').not($this).attr('aria-expanded', 'false');

                // Open this dropdown with smooth slide
                $submenu.slideDown(250);
                $this.attr('aria-expanded', 'true');
            }
        });

        // Keep dropdown open if it contains active menu item
        function openParentDropdownForActiveLink() {
            $('.submenu li.active, .submenu li a.active').each(function () {
                var $parent = $(this).closest('.dropdown-parent');
                var $toggle = $parent.find('.dropdown-toggle');
                var $submenu = $parent.find('.submenu');

                if ($submenu.length && $submenu.is(':hidden')) {
                    $submenu.slideDown(200);
                    $toggle.attr('aria-expanded', 'true');
                }
            });
        }

        // Call on load
        openParentDropdownForActiveLink();

    });
</script>