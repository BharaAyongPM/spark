@props(['activePage'])

<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 d-flex text-wrap align-items-center" href=" # ">
            <img src="{{ asset('assets/img/logos/logorsip.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-2 font-weight-bold text-white">SPARX ADMIN</span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @if (auth()->user()->roles->contains('name_roles', 'ADMIN'))
                <li class="nav-item">
                    <a class="nav-link text-white {{ $activePage == 'dashboard' ? 'active bg-gradient-success' : '' }}"
                        href="#">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">DASHBOARD</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white {{ $activePage == 'log absensi' ? ' active bg-gradient-success' : '' }} "
                        href="{{ route('admin.users') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">DATA USER</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ $activePage == 'absensitambahan' ? ' active bg-gradient-success' : '' }} "
                        href="{{ route('admin.fields') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">DATA LAPANGAN</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ $activePage == 'absensiemas' ? ' active bg-gradient-success' : '' }} "
                        href="{{ route('admin.orders') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">DATA PESANAN</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ $activePage == 'absensiemas' ? ' active bg-gradient-success' : '' }} "
                        href="/admin/facilities">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">DATA FASILITAS</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ $activePage == 'absensiemas' ? ' active bg-gradient-success' : '' }} "
                        href="/admin/field_types">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">DATA TIPE LAPANGAN</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ $activePage == 'absensiemas' ? ' active bg-gradient-success' : '' }} "
                        href="{{ route('settings.index') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">DATA SETING</span>
                    </a>
                </li>
            @endif
            @if (auth()->user()->roles->contains('name_roles', 'PEMILIK'))
                <li class="nav-item">
                    <a class="nav-link text-white {{ $activePage == 'dashboard' ? 'active bg-gradient-success' : '' }}"
                        href="{{ route('vendor.dashboard') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">DASHBOARD</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white {{ $activePage == 'log absensi' ? ' active bg-gradient-success' : '' }} "
                        href="{{ route('vendor.fields.index') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">DATA LAPANGAN</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ $activePage == 'absensitambahan' ? ' active bg-gradient-success' : '' }} "
                        href="#">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">DATA PEMESANAN</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ $activePage == 'absensiemas' ? ' active bg-gradient-success' : '' }} "
                        href="{{ route('vendor.harga.index') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">DATA HARGA</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('vendor.wasitphoto.index') }}">
                        <i class="mdi mdi-account-multiple"></i>
                        <span class="menu-title">Wasit & Fotografer</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ $activePage == 'absensiemas' ? ' active bg-gradient-success' : '' }} "
                        href="{{ route('vendor.indexvendor') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">DATA USER</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ $activePage == 'absensiemas' ? ' active bg-gradient-success' : '' }} "
                        href="{{ route('vendor.jamoperasional.index') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">DATA JADWAL</span>
                    </a>
                </li>
            @endif

        </ul>
    </div>
    <style>
        .badge2 {
            background-color: red;
            color: white;
            padding: 5px;
            border-radius: 50%;
            font-size: 12px;
        }
    </style>
</aside>
