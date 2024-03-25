@if (auth()->user()->role == 'admin' || auth()->user()->role == 'pekerja')
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
                <li class="nav-heading">Pages</li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pelanggan*') ? 'active' : 'collapsed' }}"
                        href="{{ route('pelanggan.index') }}">
                        <i class="bi bi-people"></i>
                        <span>Pelanggan</span>
                    </a>
                </li><!-- End Pelanggan Page Nav -->

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs(['kendaraan*', 'perbaikan*']) ? 'active' : 'collapsed' }}"
                        href="{{ route('kendaraan.index') }}">
                        <i class="bx bxs-car"></i>
                        <span>Kendaraan</span>
                    </a>
                </li><!-- End Kendaraan Page Nav -->

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('transaksi*') ? 'active' : 'collapsed' }}"
                        href="{{ route('transaksi.index') }}">
                        <i class="bi bi-credit-card-2-back-fill"></i>
                        <span>Transaksi</span>
                    </a>
                </li><!-- End Transaksi Page Nav -->

                <li class="nav-item {{ request()->routeIs('laporan*') ? 'active' : 'collapsed' }}">
                    <a class="nav-link " data-bs-target="#menu-laporan" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-archive"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="menu-laporan"
                        class="nav-content {{ request()->routeIs('laporan*') ? 'active' : 'collapse' }} "
                        data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="#" class="">
                                <i class="bi bi-circle"></i><span>Pelanggan</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="">
                                <i class="bi bi-circle"></i><span>Kendaraan</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="">
                                <i class="bi bi-circle"></i><span>Transaksi</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="">
                                <i class="bi bi-circle"></i><span>Pekerja</span>
                            </a>
                        </li>
                    </ul>
                </li><!-- End Menu Laporan Nav -->

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs(['admin*', 'pekerja*']) ? 'active' : 'collapsed' }}"
                        data-bs-target="#master-user" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-file-earmark-person"></i><span>Master User</span><i
                            class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="master-user"
                        class="nav-content {{ request()->routeIs(['admin*', 'pekerja*']) ? 'active' : 'collapse' }} "
                        data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="{{ route('admin.index') }}"
                                class="{{ request()->routeIs('admin*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>Admin</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pekerja.index') }}"
                                class="{{ request()->routeIs('pekerja*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>Pekerja</span>
                            </a>
                        </li>
                    </ul>
                </li><!-- End Master User Nav -->

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs(['tipe*', 'merek*']) ? 'active' : 'collapsed' }}"
                        data-bs-target="#master-kategori" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-menu-button-wide"></i><span>Master Kategori</span><i
                            class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="master-kategori"
                        class="nav-content {{ request()->routeIs(['tipe*', 'merek*']) ? 'active' : 'collapse' }} "
                        data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="{{ route('tipe.index') }}"
                                class="{{ request()->routeIs('tipe*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>Tipe</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('merek.index') }}"
                                class="{{ request()->routeIs('merek*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>Merek</span>
                            </a>
                        </li>
                    </ul>
                </li><!-- End Master Kategori Nav -->

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('settings*') ? 'active' : 'collapsed' }}"
                        href="{{ route('settings.index') }}">
                        <i class="bx bxs-store"></i>
                        <span>Pengaturan</span>
                    </a>
                </li><!-- End Settings Page Nav -->
            @elseif (auth()->user()->role == 'pekerja')
            @endif
        </ul>
    </aside>
@endif
