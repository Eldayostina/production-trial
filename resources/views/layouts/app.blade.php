<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'Dashboard') - Production Trial</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    {{-- Lucide Icons (CDN). Jika gagal dimuat, teks menu tetap terbaca. --}}
    <script src="https://unpkg.com/lucide@0.452.0/dist/umd/lucide.min.js" defer></script>
    <script src="{{ asset('js/dashboard.js') }}" defer></script>
</head>
<body>

@php
    $isDashboard     = request()->routeIs('dashboard');

    /* Production */
    $isPlan          = request()->routeIs('production-plan*');
    $isTrial         = request()->routeIs('production-trial*');
    $isResult        = request()->routeIs('trial-result*');
    $isProduction    = $isPlan || $isTrial || $isResult;

    /* Inventory */
    $isStockTransfer = request()->routeIs('stock-transfer*');
    $isStockOverview = request()->routeIs('stock-overview*');
    $isInventoryOpen = $isStockTransfer || $isStockOverview;

    /* Quality */
    $isApproval          = request()->routeIs('approval*');
    $isQualityInspection = request()->routeIs('quality-inspection*');
    $isQualityOpen       = $isApproval || $isQualityInspection;

    /* Reports */
    $isProductionReport  = request()->routeIs('production-report*');
    $isTrialReport       = request()->routeIs('trial-report*');
    $isInventoryReport   = request()->routeIs('inventory-report*');
    $isReportsOpen       = $isProductionReport || $isTrialReport || $isInventoryReport;

    /* Master Data */
    $isMasterBarang     = request()->routeIs('master-barang*');
    $isMasterGudang     = request()->routeIs('gudang*');
    $isBom              = request()->routeIs('bom*');
    $isMasterWorkCenter = request()->routeIs('master.work-center*');
    $isMasterUom        = request()->routeIs('master.uom*');
    $isMasterSupplier   = request()->routeIs('master.supplier*');
    $isMasterOpen       = $isMasterBarang || $isMasterGudang || $isBom || $isMasterWorkCenter || $isMasterUom || $isMasterSupplier;
    
    $isAudit            = request()->routeIs('audit-log*');    
    $isUsers            = request()->routeIs('users*');
    $isRoles            = request()->routeIs('roles*');
    $isAccess           = $isUsers || $isRoles;
@endphp

<div class="app">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar" id="sidebar" aria-label="Navigasi utama">
        <div class="sidebar__brand">
            <span class="sidebar__logo"><img src="{{ asset('images/logo.png') }}" alt="Logo PT"></span>
            <div class="sidebar__brand-text">
                <strong>PT. Evo Manufacturing Indonesia</strong>
                <small>Production Trial</small>
            </div>
            <button type="button" class="sidebar__close" id="sidebarClose" aria-label="Tutup menu">
                <i data-lucide="x" aria-hidden="true"></i>
            </button>
        </div>

        <nav class="sidebar__nav" aria-label="Menu utama">

            <div class="nav-group">
                <ul class="nav-list">
                    <li>
                        <a href="{{ route('dashboard') }}" class="nav-link {{ $isDashboard ? 'is-active' : '' }}" @if($isDashboard) aria-current="page" @endif>
                            <i data-lucide="layout-dashboard" aria-hidden="true"></i><span>Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-group">
                <p class="nav-group__title">OPERASIONAL</p>
                <ul class="nav-list">

                    <li class="nav-item">
                        <button type="button" class="nav-link nav-toggle" aria-expanded="{{ $isProduction ? 'true' : 'false' }}" aria-controls="sub-production">
                            <i data-lucide="factory" aria-hidden="true"></i><span>Production</span>
                            <i data-lucide="chevron-down" class="nav-toggle__arrow" aria-hidden="true"></i>
                        </button>
                        <ul class="nav-sub" id="sub-production" @unless($isProduction) hidden @endunless>
                            <li>
                                <a href="{{ route('production-plan') }}" class="nav-sublink {{ $isPlan ? 'is-active' : '' }}" @if($isPlan) aria-current="page" @endif>Production Plan</a>
                            </li>
                            <li>
                                <a href="{{ route('production-trial') }}" class="nav-sublink {{ $isTrial ? 'is-active' : '' }}" @if($isTrial) aria-current="page" @endif>Production Trial</a>
                            </li>
                            <li>
                                <a href="{{ route('trial-result') }}" class="nav-sublink {{ $isResult ? 'is-active' : '' }}" @if($isResult) aria-current="page" @endif>Trial Result</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <button type="button" class="nav-link nav-toggle" aria-expanded="{{ $isInventoryOpen ? 'true' : 'false' }}" aria-controls="sub-inventory">
                            <i data-lucide="boxes" aria-hidden="true"></i><span>Inventory</span>
                            <i data-lucide="chevron-down" class="nav-toggle__arrow" aria-hidden="true"></i>
                        </button>
                        <ul class="nav-sub" id="sub-inventory" @unless($isInventoryOpen) hidden @endunless>
                            <li>
                                <a href="{{ route('stock-transfer') }}" class="nav-sublink {{ $isStockTransfer ? 'is-active' : '' }}" @if($isStockTransfer) aria-current="page" @endif>Stock Transfer</a>
                            </li>
                            <li>
                                <a href="{{ route('stock-overview') }}" class="nav-sublink {{ $isStockOverview ? 'is-active' : '' }}" @if($isStockOverview) aria-current="page" @endif>Stock Overview</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <button type="button" class="nav-link nav-toggle" aria-expanded="{{ $isQualityOpen ? 'true' : 'false' }}" aria-controls="sub-quality">
                            <i data-lucide="shield-check" aria-hidden="true"></i><span>Quality</span>
                            <i data-lucide="chevron-down" class="nav-toggle__arrow" aria-hidden="true"></i>
                        </button>
                        <ul class="nav-sub" id="sub-quality" @unless($isQualityOpen) hidden @endunless>
                            <li>
                                <a href="{{ route('approval') }}" class="nav-sublink {{ $isApproval ? 'is-active' : '' }}" @if($isApproval) aria-current="page" @endif>Approval</a>
                            </li>
                            <li>
                                <a href="{{ route('quality-inspection') }}" class="nav-sublink {{ $isQualityInspection ? 'is-active' : '' }}" @if($isQualityInspection) aria-current="page" @endif>Quality Inspection</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <button type="button" class="nav-link nav-toggle" aria-expanded="{{ $isReportsOpen ? 'true' : 'false' }}" aria-controls="sub-reports">
                            <i data-lucide="bar-chart-3" aria-hidden="true"></i><span>Reports</span>
                            <i data-lucide="chevron-down" class="nav-toggle__arrow" aria-hidden="true"></i>
                        </button>
                        <ul class="nav-sub" id="sub-reports" @unless($isReportsOpen) hidden @endunless>
                            <li>
                                <a href="{{ route('production-report') }}" class="nav-sublink {{ $isProductionReport ? 'is-active' : '' }}" @if($isProductionReport) aria-current="page" @endif>Production Report</a>
                            </li>
                            <li>
                                <a href="{{ route('trial-report') }}" class="nav-sublink {{ $isTrialReport ? 'is-active' : '' }}" @if($isTrialReport) aria-current="page" @endif>Trial Report</a>
                            </li>
                            <li>
                                <a href="{{ route('inventory-report') }}" class="nav-sublink {{ $isInventoryReport ? 'is-active' : '' }}" @if($isInventoryReport) aria-current="page" @endif>Inventory Report</a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>

            <div class="nav-group">
                <p class="nav-group__title">MASTER DATA</p>
                <ul class="nav-list">
                    <li class="nav-item">
                        <button type="button" class="nav-link nav-toggle" aria-expanded="{{ $isMasterOpen ? 'true' : 'false' }}" aria-controls="sub-master">
                            <i data-lucide="database" aria-hidden="true"></i><span>Master Data</span>
                            <i data-lucide="chevron-down" class="nav-toggle__arrow" aria-hidden="true"></i>
                        </button>
                        <ul class="nav-sub" id="sub-master" @unless($isMasterOpen) hidden @endunless>
                            <li>
                                <a href="{{ route('master-barang') }}" class="nav-sublink {{ $isMasterBarang ? 'is-active' : '' }}" @if($isMasterBarang) aria-current="page" @endif>Master Barang</a>
                            </li>
                            <li>
                                <a href="{{ route('gudang') }}" class="nav-sublink {{ $isMasterGudang ? 'is-active' : '' }}" @if($isMasterGudang) aria-current="page" @endif>Master Gudang</a>
                            </li>
                            <li>
                                <a href="{{ route('bom') }}" class="nav-sublink {{ $isBom ? 'is-active' : '' }}" @if($isBom) aria-current="page" @endif>Bill of Materials (BOM)</a>
                            </li>
                            <li>
                                <a href="{{ route('master.work-center') }}" class="nav-sublink {{ $isMasterWorkCenter ? 'is-active' : '' }}" @if($isMasterWorkCenter) aria-current="page" @endif>Work Center</a>
                            </li>
                            <li>
                                <a href="{{ route('master.uom') }}" class="nav-sublink {{ $isMasterUom ? 'is-active' : '' }}" @if($isMasterUom) aria-current="page" @endif>Satuan (UoM)</a>
                            </li>
                            <li>
                                <a href="{{ route('master.supplier') }}" class="nav-sublink {{ $isMasterSupplier ? 'is-active' : '' }}" @if($isMasterSupplier) aria-current="page" @endif>Supplier</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="nav-group nav-group--bottom">
                <p class="nav-group__title">SYSTEM</p>
                <ul class="nav-list">
                    <li class="nav-item">
                        <button type="button" class="nav-link nav-toggle" aria-expanded="{{ $isAccess ? 'true' : 'false' }}" aria-controls="sub-access">
                            <i data-lucide="users" aria-hidden="true"></i><span>Users &amp; Access</span>
                            <i data-lucide="chevron-down" class="nav-toggle__arrow" aria-hidden="true"></i>
                        </button>
                        <ul class="nav-sub" id="sub-access" @unless($isAccess) hidden @endunless>
                            <li>
                                <a href="{{ route('users') }}" class="nav-sublink {{ $isUsers ? 'is-active' : '' }}" @if($isUsers) aria-current="page" @endif>User Management</a>
                            </li>
                            <li><a href="{{ route('roles') }}" class="nav-sublink {{ $isRoles ? 'is-active' : '' }}" @if($isRoles) aria-current="page" @endif>Roles &amp; Permission</a>
                            </li>
                        </ul>
                    </li>
                    <li><a href="{{ route('audit-log') }}" class="nav-link {{ $isAudit ? 'is-active' : '' }}" @if($isAudit) aria-current="page" @endif><i data-lucide="history" aria-hidden="true"></i><span>Audit Log</span></a></li>
                    <li><a href="#" class="nav-link"><i data-lucide="settings" aria-hidden="true"></i><span>Settings</span></a></li>
                </ul>
            </div>

        </nav>
    </aside>

    <div class="overlay" id="overlay"></div>

    {{-- ===== AREA UTAMA ===== --}}
    <div class="main">

        {{-- HEADER --}}
        <header class="header">
            <div class="header__left">
                <button type="button" class="icon-btn header__toggle" id="sidebarToggle" aria-label="Buka menu" aria-controls="sidebar" aria-expanded="false">
                    <i data-lucide="menu" aria-hidden="true"></i>
                </button>
                <div class="header__title">
                    <nav class="breadcrumb" aria-label="Breadcrumb">
                        <span>Home</span><span class="breadcrumb__sep">/</span>
                        @hasSection('breadcrumb_parent')
                            <span>@yield('breadcrumb_parent')</span><span class="breadcrumb__sep">/</span>
                        @endif
                        <span class="breadcrumb__current">@yield('page_title', 'Dashboard')</span>
                    </nav>
                    <h1>@yield('page_title', 'Dashboard')</h1>
                </div>
            </div>

            <div class="header__right">
                <button type="button" class="icon-btn" aria-label="Notifikasi">
                    <i data-lucide="bell" aria-hidden="true"></i>
                    <span class="icon-btn__dot"></span>
                </button>

                <div class="profile" id="profile">
                    <button type="button" class="profile__btn" id="profileBtn" aria-haspopup="true" aria-expanded="false">
                        <span class="avatar">AP</span>
                        <span class="profile__name">Admin Produksi</span>
                        <i data-lucide="chevron-down" class="profile__chevron" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown" id="profileMenu" role="menu" hidden>
                        <div class="dropdown__head">
                            <strong>Admin Produksi</strong>
                            <small>Administrator</small>
                        </div>
                        <a href="#" class="dropdown__item" role="menuitem"><i data-lucide="user" aria-hidden="true"></i><span>Profil Saya</span></a>
                        <a href="#" class="dropdown__item" role="menuitem"><i data-lucide="settings" aria-hidden="true"></i><span>Pengaturan</span></a>
                        <a href="#" class="dropdown__item dropdown__item--danger" role="menuitem"><i data-lucide="log-out" aria-hidden="true"></i><span>Keluar</span></a>
                    </div>
                </div>
            </div>
        </header>

        {{-- KONTEN HALAMAN --}}
        <main class="content">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>