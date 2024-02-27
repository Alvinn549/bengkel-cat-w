<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : 'collapsed' }}"
                href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        @if (auth()->user()->role == 'admin')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Components</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="#">
                            <i class="bi bi-circle"></i><span>Alerts</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-circle"></i><span>Accordion</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Components Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-bar-chart"></i><span>Charts</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="#">
                            <i class="bi bi-circle"></i><span>Chart.js</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-circle"></i><span>ApexCharts</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-circle"></i><span>ECharts</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Charts Nav -->

            <li class="nav-heading">Pages</li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin*') ? 'active' : 'collapsed' }}"
                    href="{{ route('admin.index') }}">
                    <i class="bi bi-person"></i>
                    <span>Admin</span>
                </a>
            </li><!-- End Admin Page Nav -->

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pelanggan*') ? 'active' : 'collapsed' }}"
                    href="{{ route('pelanggan.index') }}">
                    <i class="bi bi-card-list"></i>
                    <span>Pelanggan</span>
                </a>
            </li><!-- End Pelanggan Page Nav -->

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kendaraan*') ? 'active' : 'collapsed' }}"
                    href="{{ route('kendaraan.index') }}">
                    <i class="bi bi-card-list"></i>
                    <span>Kendaraan</span>
                </a>
            </li><!-- End Kendaraan Page Nav -->

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pekerja*') ? 'active' : 'collapsed' }}"
                    href="{{ route('pekerja.index') }}">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Pekerja</span>
                </a>
            </li><!-- End Login Page Nav -->
        @elseif (auth()->user()->role == 'pelanggan')

        @elseif (auth()->user()->role == 'pekerja')
        @endif
    </ul>
</aside>
