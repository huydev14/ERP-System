<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item {{ request()->routeIs('dashboard') ? 'menu-open' : '' }}">
            <a href="/" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <x-icon-home class="nav-icon" />
                <p>Home</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link">
                <x-icon-wallet class="nav-icon" />
                <p>Tài chính
                    <x-icon-chevron-left class="right" />
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="pages/layout/top-nav.html" class="nav-link">
                        <p>Bảng kế toán</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/layout/top-nav-sidebar.html" class="nav-link">
                        <p>Bảng lương</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/layout/boxed.html" class="nav-link">
                        <p>Chi tiêu</p>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Nhập hàng --}}
        <li class="nav-item">
            <a href="#" class="nav-link">
                <x-icon-truck class="nav-icon" />
                <p>Nhập hàng
                    <x-icon-chevron-left class="right" />
                </p>
            </a>

            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="pages/charts/chartjs.html" class="nav-link">
                        <p>Xếp lô</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/charts/flot.html" class="nav-link">
                        <p>Quản lý đơn hàng</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/charts/inline.html" class="nav-link">
                        <p>Khách hàng</p>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Bán hàng --}}
        <li class="nav-item">
            <a href="#" class="nav-link">
                <x-icon-shopping class="nav-icon" />
                <p> Bán hàng
                    <x-icon-chevron-left class="right" />
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="pages/UI/general.html" class="nav-link">
                        <p>Bán hàng</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/UI/general.html" class="nav-link">
                        <p>Phiếu hàng</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/UI/icons.html" class="nav-link">
                        <p>Tồn kho</p>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Hành chính nhân sự --}}
        <li class="nav-item">
            <a href="#" class="nav-link">
                <x-icon-people-building class="nav-icon" />
                <p>HCNS
                    <x-icon-chevron-left class="right" />
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="pages/forms/general.html" class="nav-link">
                        <p>Tuyển dụng</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/forms/advanced.html" class="nav-link">
                        <p>Cơ sở vật chất</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/forms/editors.html" class="nav-link">
                        <p>Chấm công</p>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Account --}}
        <li class="nav-item menu-open {{ request()->routeIs('users.*') ? 'menu-is-opening ' : '' }}">
            <a href="#" class="nav-link">
                <x-icon-people-setting class="nav-icon" />
                <p>Tài khoản
                    <x-icon-chevron-left class="right" />
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item ">
                    <a href="{{ route('users.index') }}"
                        class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                        <p>Quản lý tài khoản</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('roles.index') }}"
                        class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                        <p>Roles & Permissions</p>
                    </a>
                </li>
            </ul>
        </li>

        {{-- System configuration --}}
        <li class="nav-item menu-open {{ request()->routeIs('users.*') ? 'menu-is-opening ' : '' }}"">
            <a href="#" class="nav-link">
                <x-icon-setting class="nav-icon" />
                <p>Hệ thống
                    <x-icon-chevron-left class="right" />
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('audit-logs.index') }}"
                        class="nav-link {{ request()->routeIs('audit-logs.index') ? 'active' : '' }}">
                        <p>Audit logs</p>
                    </a>
                </li>
            </ul>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href=""
                        class="nav-link ">
                        <p>Cài đặt</p>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
