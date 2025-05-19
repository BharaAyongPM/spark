@php use Illuminate\Support\Str; @endphp
<nav class="sidebar sidebar-offcanvas dynamic-active-class-disabled" id="sidebar">
    <ul class="nav">
        <!-- USER PROFILE -->
        <li class="nav-item nav-profile not-navigation-link">
            <div class="nav-link">
                <div class="user-wrapper">
                    <div class="profile-image">
                        <img src="{{ auth()->user()->avatar ? asset('storage/avatar/' . auth()->user()->avatar) : asset('admin/assets/images/faces/face8.jpg') }}"
                            alt="profile image">
                    </div>
                    <div class="text-wrapper">
                        <p class="profile-name">{{ auth()->user()->name }}</p>
                        <small class="designation text-muted">
                            {{ auth()->user()->roles->pluck('name_roles')->first() ?? 'User' }}
                        </small>
                    </div>
                </div>
            </div>
        </li>

        {{-- MENU UNTUK ADMIN --}}
        @if (auth()->user()->roles->contains('name_roles', 'ADMIN'))
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/dashboard') ? 'active bg-gradient-success text-primary' : '' }}"
                    href="/admin/dashboard">
                    <i class="menu-icon mdi mdi-view-dashboard"></i>
                    <span class="menu-title">DASHBOARD</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/users*') ? 'active bg-gradient-success text-primary' : '' }}"
                    href="{{ route('admin.users') }}">
                    <i class="menu-icon mdi mdi-account-multiple"></i>
                    <span class="menu-title">DATA USER</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/fields*') ? 'active bg-gradient-success text-primary' : '' }}"
                    href="{{ route('admin.fields') }}">
                    <i class="menu-icon mdi mdi-soccer-field"></i>
                    <span class="menu-title">DATA LAPANGAN</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/orders*') ? 'active bg-gradient-success text-primary' : '' }}"
                    href="{{ route('admin.orders') }}">
                    <i class="menu-icon mdi mdi-cart"></i>
                    <span class="menu-title">DATA PESANAN</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/facilities*') ? 'active bg-gradient-success text-primary' : '' }}"
                    href="/admin/facilities">
                    <i class="menu-icon mdi mdi-tools"></i>
                    <span class="menu-title">DATA FASILITAS</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/field_types*') ? 'active bg-gradient-success text-primary' : '' }}"
                    href="/admin/field_types">
                    <i class="menu-icon mdi mdi-view-list"></i>
                    <span class="menu-title">DATA TIPE LAPANGAN</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('vendor/diskon*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('discounts.index') }}">
                    <i class="fas fa-tags"></i>
                    <span>Manajemen Diskon</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/withdraws') ? 'active' : '' }}"
                    href="{{ route('admin.withdraws.index') }}">
                    <i class="menu-icon mdi mdi-cash"></i>
                    <span class="menu-title">DATA WITHDRAW</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/settings*') ? 'active bg-gradient-success text-primary' : '' }}"
                    href="{{ route('settings.index') }}">
                    <i class="menu-icon mdi mdi-settings"></i>
                    <span class="menu-title">DATA SETING</span>
                </a>
            </li>
        @endif

        {{-- MENU UNTUK PEMILIK --}}
        @if (auth()->user()->roles->contains('name_roles', 'PEMILIK'))
            <li class="nav-item">
                <a class="nav-link {{ request()->is('vendor/dashboard') ? 'active bg-gradient-success text-primary' : '' }}"
                    href="{{ route('vendor.dashboard') }}">
                    <i class="menu-icon mdi mdi-view-dashboard"></i>
                    <span class="menu-title">DASHBOARD</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('vendor/fields*') ? 'active bg-gradient-success text-primary' : '' }}"
                    href="{{ route('vendor.fields.index') }}">
                    <i class="menu-icon mdi mdi-soccer-field"></i>
                    <span class="menu-title">DATA LAPANGAN</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('vendor/orders*') ? 'active bg-gradient-success text-primary' : '' }}"
                    href="{{ route('vendor.orders') }}">
                    <i class="menu-icon mdi mdi-calendar-clock"></i>
                    <span class="menu-title">DATA PEMESANAN</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('vendor/harga*') ? 'active bg-gradient-success text-primary' : '' }}"
                    href="{{ route('vendor.harga.index') }}">
                    <i class="menu-icon mdi mdi-cash-multiple"></i>
                    <span class="menu-title">DATA HARGA</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('vendor/indexvendor*') ? 'active bg-gradient-success text-primary' : '' }}"
                    href="{{ route('vendor.indexvendor') }}">
                    <i class="menu-icon mdi mdi-account"></i>
                    <span class="menu-title">DATA USER</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('vendor/jamoperasional*') ? 'active bg-gradient-success text-primary' : '' }}"
                    href="{{ route('vendor.jamoperasional.index') }}">
                    <i class="menu-icon mdi mdi-clock-outline"></i>
                    <span class="menu-title">DATA JADWAL</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('vendor.wasitphoto.index') }}">
                    <i class="mdi mdi-account-multiple"></i>
                    <span class="menu-title">Wasit & Fotografer</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('vendor/withdraw*') ? 'active bg-gradient-success text-primary' : '' }}"
                    href="{{ route('vendor.withdraw.index') }}">
                    <i class="menu-icon mdi mdi-cash"></i>
                    <span class="menu-title">WITHDRAW</span>
                </a>
            </li>
        @endif

    </ul>
</nav>
