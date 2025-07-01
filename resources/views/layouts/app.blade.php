<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Penjualan FOGU')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Another+Shabby&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('styles')

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            background-color: #f4f6f9;
        }
        .sidebar {
            width: 250px;
            background-color: #800000;
            color: #fff;
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            transition: width 0.3s ease;
            z-index: 1000;
        }
        .sidebar.closed {
            width: 60px;
        }
        .sidebar.no-transition {
            transition: none !important;
        }
        .sidebar-header {
            padding: 20px;
            font-size: 1.2rem;
            background-color: #600000;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }
        .sidebar-header strong {
            font-family: 'Another Shabby', cursive;
            font-size: 1.8rem;
        }
        .sidebar.closed .sidebar-header strong,
        .sidebar.closed .nav-menu span,
        .sidebar.closed .user-profile-block .user-info,
        .sidebar.closed .user-profile-block .chevron {
            display: none;
        }
        .sidebar a {
            color: white;
            padding: 15px 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            position: relative;
        }
        .sidebar a i {
            margin-right: 10px;
        }
        .sidebar a:hover {
            background-color: #9a0000;
        }

        .nav-menu a.active {
            color: #ffadad;
        }

        .nav-menu a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: #ffadad;
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }

        .main-content {
            margin-left: 60px;
            width: calc(100% - 60px);
            padding: 20px;
            flex: 1;
            transition: margin-left 0.3s ease, width 0.3s ease;
        }
        .main-content.sidebar-open {
            margin-left: 250px;
            width: calc(100% - 250px);
        }
        .page-wrapper {
            width: 100%;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }
        #toggleBtn {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }
        .profile-container {
            position: relative;
            flex-shrink: 0;
        }
        .user-profile-block {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            cursor: pointer;
            border-bottom: 1px solid #a040406c;
            background-color: #800000;
            transition: background-color 0.2s;
        }
        .user-profile-block:hover {
            background-color: #9a0000;
        }
        .sidebar.closed .user-profile-block {
            justify-content: center;
        }
        .user-profile-block .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 12px;
        }
        .sidebar.closed .user-profile-block .avatar {
            margin-right: 0;
        }
        .user-profile-block .user-info {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .user-profile-block .user-role {
            font-size: 14px;
            font-weight: bold;
            line-height: 1.2;
        }
        .user-profile-block .user-email {
            font-size: 12px;
            color: #ccc;
            line-height: 1.2;
        }
        .user-profile-block .chevron {
            font-size: 12px;
            color: #ccc;
            transition: transform 0.2s;
        }
        .user-profile-block.open .chevron {
            transform: rotate(180deg);
        }

        .profile-dropdown {
            display: none;
            list-style: none;
            margin: 0;
            padding: 5px 0;
            background-color: #9a0000;
            z-index: 1001;
        }

        .sidebar:not(.closed) .profile-dropdown {
            width: 100%;
        }

        .sidebar.closed .profile-dropdown {
            position: absolute;
            top: 0;
            left: 100%;
            margin-left: 10px;
            width: 200px;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .profile-dropdown.show {
            display: block;
        }

        .profile-dropdown a, .profile-dropdown button {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-family: 'Segoe UI', sans-serif;
            font-size: 14px;
        }
        .profile-dropdown a:hover, .profile-dropdown button:hover {
            background-color: #b00000;
        }
        .profile-dropdown form {
            margin: 0;
        }
        .nav-menu {
            overflow-y: auto;
        }
    </style>
</head>
<body>

    <div class="sidebar closed" id="sidebar">
        <div class="sidebar-header">
            <button id="toggleBtn"><i class="fa fa-bars"></i></button>
            <div><strong><i>TOVA</i></strong></div>
        </div>
        @auth
        <div class="profile-container">
            <div class="user-profile-block" id="profile-trigger">
                @if (Auth::user()->foto)
                    <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto Profil" class="avatar">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=9a0000&color=fff" alt="Avatar" class="avatar">
                @endif
                <div class="user-info">
                    <span class="user-role">{{ Auth::user()->name }}</span>
                    <span class="user-email">{{ Auth::user()->email }}</span>
                </div>
                <i class="fas fa-chevron-down chevron"></i>
            </div>
            
            <ul class="profile-dropdown" id="profile-dropdown">
                <li><a href="{{ route('settings.edit') }}"><i class="fa fa-user-cog fa-fw"></i> <span>Akun</span></a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"><i class="fa fa-sign-out-alt fa-fw"></i> <span>Keluar</span></button>
                    </form>
                </li>
            </ul>
        </div>
        @endauth
        <div class="nav-menu">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fa-solid fa-chart-line"></i> <span>Dashboard</span></a>
            <a href="{{ route('supplier.index') }}" class="{{ request()->routeIs('supplier.*') ? 'active' : '' }}"><i class="fa-solid fa-truck"></i> <span>Data Supplier</span></a>
            <a href="{{ route('barang.index') }}" class="{{ request()->routeIs('barang.*') ? 'active' : '' }}"><i class="fa-solid fa-box"></i> <span>Data Barang</span></a>
            <a href="{{ route('penjualan.create') }}" class="{{ request()->routeIs('penjualan.create') ? 'active' : '' }}"><i class="fa-solid fa-cash-register"></i> <span>Input Penjualan</span></a>
            <a href="{{ route('pembelian.create') }}" class="{{ request()->routeIs('pembelian.create') ? 'active' : '' }}"><i class="fa-solid fa-dolly"></i> <span>Input Pembelian</span></a>
            <a href="{{ route('penjualan.index') }}" class="{{ request()->routeIs('penjualan.index') ? 'active' : '' }}"><i class="fa-solid fa-receipt"></i> <span>Riwayat Penjualan</span></a>
        </div>
    </div>

    <div class="main-content" id="main-content">
        <div class="page-wrapper">
            @yield('content')
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        const mainContent = document.getElementById('main-content');
        const profileTrigger = document.getElementById('profile-trigger');
        const profileDropdown = document.getElementById('profile-dropdown');

        function setSidebarState() {
            const sidebarState = localStorage.getItem('sidebarState');
            if (sidebarState === 'open') {
                sidebar.classList.add('no-transition');
                mainContent.classList.add('sidebar-open');
                sidebar.classList.remove('closed');
                sidebar.offsetHeight;
                sidebar.classList.remove('no-transition');
            } else {
                 mainContent.classList.remove('sidebar-open');
            }
        }
        setSidebarState();

        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('closed');
            mainContent.classList.toggle('sidebar-open');

            if (profileDropdown) profileDropdown.classList.remove('show');
            if (profileTrigger) profileTrigger.classList.remove('open');
            
            if (sidebar.classList.contains('closed')) {
                localStorage.setItem('sidebarState', 'closed');
            } else {
                localStorage.setItem('sidebarState', 'open');
            }
        });

        if (profileTrigger) {
            profileTrigger.addEventListener('click', function(event) {
                event.stopPropagation();
                profileDropdown.classList.toggle('show');
                profileTrigger.classList.toggle('open');
            });
        }
        
        window.addEventListener('click', function(event) {
            if (profileDropdown && profileDropdown.classList.contains('show')) {
                if (profileTrigger && !profileTrigger.contains(event.target)) {
                    profileDropdown.classList.remove('show');
                    profileTrigger.classList.remove('open');
                }
            }
        });
    </script>

    @stack('scripts')
    
</body>
</html>