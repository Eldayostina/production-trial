@extends('layouts.app')

@section('page_title', 'Stock Overview')
@section('breadcrumb_parent', 'Inventory')

@section('content')

@php
    // DATA DUMMY - nanti diganti data dari database (Controller)
    $statuses = [
        'safe'     => ['Aman',          'ok'],
        'low'      => ['Menipis',       'wait'],
        'critical' => ['Kritis',        'fail'],
        'expiry'   => ['Expiry Dekat',  'wait'],
        'out'      => ['Habis',         'fail'],
    ];

    // [kode, nama, kategori, gudang, batch, expiry, uom, stok, stok_min, status]
    $stocks = [
        ['RM-1001', 'Daging Tuna Cooked Flake',        'Bahan Baku',   'Cold Storage',           'LOT-241001', '20 Okt 2026', 'kg',  120.0,  100, 'safe'],
        ['RM-1002', 'Daging Tuna Chunk',               'Bahan Baku',   'Cold Storage',           'LOT-241002', '18 Okt 2026', 'kg',  900.0,  500, 'expiry'],
        ['RM-1005', 'Daging Ayam Giling',              'Bahan Baku',   'Cold Storage',           'LOT-241005', '22 Okt 2026', 'kg',  300.0,  200, 'safe'],
        ['RM-1010', 'Hati Ayam',                       'Bahan Baku',   'Cold Storage',           'LOT-241010', '25 Okt 2026', 'kg',  45.0,   60,  'low'],
        ['RM-1020', 'Daging Ikan Makarel',             'Bahan Baku',   'Cold Storage',           'LOT-241020', '15 Okt 2026', 'kg',  18.0,   80,  'critical'],
        ['RM-2001', 'Tepung Tapioka',                  'Bahan Kering', 'Gudang Bahan Kering',    'LOT-242001', '15 Des 2026', 'kg',  400.0,  200, 'safe'],
        ['RM-2010', 'Guar Gum',                        'Bahan Kering', 'Gudang Bahan Kering',    'LOT-242010', '10 Jan 2027', 'kg',  6.0,    10,  'low'],
        ['RM-2011', 'Karagenan',                       'Bahan Kering', 'Gudang Bahan Kering',    'LOT-242011', '10 Jan 2027', 'kg',  4.0,    8,   'low'],
        ['RM-2020', 'Taurine',                         'Bahan Kering', 'Gudang Bahan Kering',    'LOT-242020', '01 Feb 2027', 'kg',  2.0,    5,   'low'],
        ['RM-2021', 'Premix Vitamin & Mineral Kucing', 'Bahan Kering', 'Gudang Bahan Kering',    'LOT-242021', '10 Feb 2027', 'kg',  8.0,    6,   'safe'],
        ['RM-2030', 'Minyak Ikan',                     'Bahan Penolong','Gudang Bahan Penolong', 'LOT-242030', '05 Des 2026', 'kg',  60.0,   40,  'safe'],
        ['RM-2040', 'Garam',                           'Bahan Penolong','Gudang Bahan Penolong', 'LOT-242040', '30 Jun 2027', 'kg',  50.0,   30,  'safe'],
        ['RM-2050', 'Perisa Palatant Tuna',            'Bahan Penolong','Gudang Bahan Penolong', 'LOT-242050', '20 Nov 2026', 'kg',  25.0,   15,  'safe'],
        ['PK-3001', 'Retort Pouch 85 g Life Cat Tuna', 'Kemasan',      'Gudang Kemasan',         'LOT-243001', '30 Jun 2027', 'pcs', 60000,  30000,'safe'],
        ['PK-3002', 'Retort Pouch 85 g Life Cat Mackerel','Kemasan',   'Gudang Kemasan',         'LOT-243002', '30 Jun 2027', 'pcs', 45000,  30000,'safe'],
        ['PK-3101', 'Karton Master isi 24 Pouch',      'Kemasan',      'Gudang Kemasan',         'LOT-243101', '30 Jun 2027', 'pcs', 2500,   1500,'safe'],
        ['PK-3201', 'Kaleng 401 Lacquer Putih',        'Kemasan',      'Gudang Kemasan',         'LOT-243201', '31 Des 2027', 'pcs', 8000,   10000,'low'],
        ['PK-3202', 'Tutup Kaleng Easy Open',          'Kemasan',      'Gudang Kemasan',         'LOT-243202', '31 Des 2027', 'pcs', 0,      10000,'out'],
        ['PK-3210', 'Label Kaleng Life Cat Tuna',      'Kemasan',      'Gudang Kemasan',         'LOT-243210', '31 Des 2027', 'pcs', 25000,  15000,'safe'],
        ['PK-3102', 'Karton Master isi 24 Kaleng',     'Kemasan',      'Gudang Kemasan',         'LOT-243102', '30 Jun 2027', 'pcs', 1500,   1000,'safe'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>Stock Overview</h2>
        <p>Posisi stok material dan kemasan per gudang</p>
    </div>
    <div class="page-head__actions">
        <button type="button" class="btn btn--light"><i data-lucide="download" aria-hidden="true"></i><span>Export</span></button>
        <button type="button" class="btn btn--light"><i data-lucide="refresh-cw" aria-hidden="true"></i><span>Refresh</span></button>
    </div>
</section>

{{-- Ringkasan --}}
<section class="kpi-grid" aria-label="Ringkasan stok">
    <article class="kpi">
        <span class="kpi__icon kpi__icon--blue"><i data-lucide="package" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total SKU Aktif</p>
            <p class="kpi__value">143</p>
            <p class="kpi__trend">Di 4 gudang</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--indigo"><i data-lucide="banknote" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Nilai Stok</p>
            <p class="kpi__value">Rp 2,84 M</p>
            <p class="kpi__trend">Harga rata-rata bergerak</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--amber"><i data-lucide="alert-triangle" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Di Bawah Minimum</p>
            <p class="kpi__value">7</p>
            <p class="kpi__trend kpi__trend--warn"><i data-lucide="clock" aria-hidden="true"></i>Perlu reorder</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--green"><i data-lucide="calendar-clock" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Expiry ≤ 30 Hari</p>
            <p class="kpi__value">3</p>
            <p class="kpi__trend">Batch perlu diprioritaskan (FIFO)</p>
        </div>
    </article>
</section>

{{-- Daftar stok --}}
<section class="panel panel--fit">

    <div class="toolbar">
        <div class="tabs" role="tablist" aria-label="Filter gudang">
            <button type="button" class="tabs__btn is-active" data-filter="all">Semua</button>
            <button type="button" class="tabs__btn" data-filter="Cold Storage">Cold Storage</button>
            <button type="button" class="tabs__btn" data-filter="Gudang Bahan Kering">Bahan Kering</button>
            <button type="button" class="tabs__btn" data-filter="Gudang Bahan Penolong">Bahan Penolong</button>
            <button type="button" class="tabs__btn" data-filter="Gudang Kemasan">Kemasan</button>
        </div>

        <div class="toolbar__right">
            <label class="search">
                <i data-lucide="search" aria-hidden="true"></i>
                <input type="search" id="planSearch" placeholder="Cari kode, nama, atau batch..." aria-label="Cari stok">
            </label>
            <select class="select" aria-label="Filter status stok">
                <option>Semua Status</option>
                <option>Aman</option>
                <option>Menipis</option>
                <option>Kritis</option>
                <option>Habis</option>
                <option>Expiry Dekat</option>
            </select>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table" id="planTable">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Material</th>
                    <th>Kategori</th>
                    <th>Gudang</th>
                    <th>Batch / Lot</th>
                    <th>Expiry</th>
                    <th class="table__right">Stok</th>
                    <th class="table__right">Min.</th>
                    <th>Status</th>
                    <th class="table__right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stocks as $s)
                    @php
                        [$label, $badge] = $statuses[$s[9]];
                        // Filter tab memakai data-status = nama gudang (agar tab gudang berfungsi)
                        $tabKey = $s[3];
                    @endphp
                    <tr data-status="{{ $tabKey }}" data-search="{{ strtolower($s[0].' '.$s[1].' '.$s[4].' '.$s[3]) }}">
                        <td class="table__code"><a href="{{ route('stock-overview.show', $s[0]) }}">{{ $s[0] }}</a></td>
                        <td><span class="cell-title">{{ $s[1] }}</span></td>
                        <td>{{ $s[2] }}</td>
                        <td>{{ $s[3] }}</td>
                        <td>{{ $s[4] }}</td>
                        <td>{{ $s[5] }}</td>
                        <td class="table__right">{{ number_format($s[7], 2, ',', '.') }} {{ $s[6] }}</td>
                        <td class="table__right">{{ number_format($s[8], 0, ',', '.') }} {{ $s[6] }}</td>
                        <td><span class="badge badge--{{ $badge }}">{{ $label }}</span></td>
                        <td>
                            <div class="table__actions">
                                <a href="{{ route('stock-overview.show', $s[0]) }}" class="icon-btn icon-btn--sm" aria-label="Buka detail stok"><i data-lucide="eye" aria-hidden="true"></i></a>
                                <button type="button" class="icon-btn icon-btn--sm" aria-label="Lainnya"><i data-lucide="more-vertical" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="empty" id="planEmpty" hidden>
            <i data-lucide="search-x" aria-hidden="true"></i>
            <p>Tidak ada stok yang cocok.</p>
        </div>
    </div>

    <div class="panel__foot">
        <p>Menampilkan 1-20 dari 143 data</p>
        <nav class="pagination" aria-label="Halaman">
            <button type="button" class="pagination__btn" disabled aria-label="Sebelumnya"><i data-lucide="chevron-left" aria-hidden="true"></i></button>
            <button type="button" class="pagination__btn is-active" aria-current="page">1</button>
            <button type="button" class="pagination__btn">2</button>
            <button type="button" class="pagination__btn">3</button>
            <button type="button" class="pagination__btn">4</button>
            <button type="button" class="pagination__btn">5</button>
            <button type="button" class="pagination__btn" aria-label="Berikutnya"><i data-lucide="chevron-right" aria-hidden="true"></i></button>
        </nav>
    </div>

</section>

@endsection