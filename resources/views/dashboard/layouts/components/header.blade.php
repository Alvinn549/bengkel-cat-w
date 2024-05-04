<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ route('home') }}" target="_blank" class="logo d-flex align-items-center">
            <img src="{{ asset('assets/dashboard/img/logo.png') }}" class="me-3" alt="">
            @php
                $profil_bengkel = \App\Models\Settings::first();
                $master_nama = $profil_bengkel->master_nama ?? '';
                $words = explode(' ', $master_nama);
                $acronym = '';

                foreach ($words as $w) {
                    $acronym .= strtoupper($w[0]);
                }
            @endphp
            <span class="d-none d-lg-block">
                @if (auth()->user()->role == 'admin')
                    {{ $acronym }}
                @endif
            </span>
        </a>
        @if (auth()->user()->role == 'admin')
            <i class="bi bi-list toggle-sidebar-btn"></i>
        @endif
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            @if (auth()->user()->role == 'admin')
                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                        data-bs-toggle="dropdown">
                        @if (auth()->user()->admin->foto)
                            <div class="foto-profil">
                                <img src="{{ asset('storage/' . auth()->user()->admin->foto) }}" alt="Profile">
                            </div>
                        @else
                            <img src="{{ asset('assets/dashboard/img/man.png') }}" alt="Profile"
                                class="rounded-circle">
                        @endif
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->user()->admin->nama }}</span>
                    </a>
                    <!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>{{ auth()->user()->admin->nama }}</h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('profil.index') }}">
                                <i class="bi bi-person"></i>
                                <span>Profile Saya</span>
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" onclick="confirmLogout();"
                                href="#">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Keluar</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->
            @elseif (auth()->user()->role == 'pelanggan')
                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                        data-bs-toggle="dropdown">
                        @if (auth()->user()->pelanggan->foto)
                            <div class="foto-profil">
                                <img src="{{ asset('storage/' . auth()->user()->pelanggan->foto) }}" alt="Profile">
                            </div>
                        @else
                            <img src="{{ asset('assets/dashboard/img/man.png') }}" alt="Profile"
                                class="rounded-circle">
                        @endif
                        <span
                            class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->user()->pelanggan->nama }}</span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>{{ auth()->user()->pelanggan->nama }}</h6>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('profil.index') }}">
                                <i class="bi bi-person"></i>
                                <span>Profile Saya</span>
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" onclick="confirmLogout();"
                                href="#">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Keluar</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->
            @elseif (auth()->user()->role == 'pekerja')
                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                        data-bs-toggle="dropdown">
                        @if (auth()->user()->pekerja->foto)
                            <div class="foto-profil">
                                <img src="{{ asset('storage/' . auth()->user()->pekerja->foto) }}" alt="Profile">
                            </div>
                        @else
                            <img src="{{ asset('assets/dashboard/img/man.png') }}" alt="Profile"
                                class="rounded-circle">
                        @endif
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->user()->pekerja->nama }}</span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>{{ auth()->user()->pekerja->nama }}</h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('profil.index') }}">
                                <i class="bi bi-person"></i>
                                <span>Profile Saya</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" onclick="confirmLogout();"
                                href="#">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Keluar</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->
            @endif
        </ul>
    </nav><!-- End Icons Navigation -->
</header>
