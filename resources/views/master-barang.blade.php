@extends('layouts.app')

@section('page_title', 'Master Barang')
@section('breadcrumb_parent', 'Master Data')

@section('content')

@php
    // DATA DUMMY - nanti diganti data dari database (Controller)
    $categories = [
        'FG' => ['Finished Good',   'ok'],
        'WIP'=> ['Work in Progress','run'],
        'RM' => ['Raw Material',    'draft'],
        'PM' => ['Packaging',       'done'],
        'CN' => ['Consumable',      'wait'],
    ];

    // [kode, nama, kategori, uom, gudang default, harga rata-rata (Rp), status]
    $items = [
        ['FG-LC-P01', 'Life Cat Pouch Tuna & Chicken 85 g', 'FG',  'pouch', 'Gudang Produk Jadi',  8500,    'aktif'],
        ['FG-LC-M01', 'Life Cat Pouch Mackerel 85 g',       'FG',  'pouch', 'Gudang Produk Jadi',  8200,    'aktif'],
        ['FG-LC-K01', 'Life Cat Kaleng Tuna 400 g',         'FG',  'kaleng','Gudang Produk Jadi',  22000,   'aktif'],
        ['WIP-LC-001','Adonan Basah Tuna (WIP)',            'WIP', 'kg',    'Area Produksi',       45000,   'aktif'],
        ['RM-1001',   'Daging Tuna Cooked Flake',           'RM',  'kg',    'Cold Storage',        85000,   'aktif'],
        ['RM-1002',   'Daging Tuna Chunk',                  'RM',  'kg',    'Cold Storage',        92000,   'aktif'],
        ['RM-1005',   'Daging Ayam Giling',                 'RM',  'kg',    'Cold Storage',        42000,   'aktif'],
        ['RM-1010',   'Hati Ayam',                          'RM',  'kg',    'Cold Storage',        38000,   'aktif'],
        ['RM-1020',   'Daging Ikan Makarel',                'RM',  'kg',    'Cold Storage',        55000,   'aktif'],
        ['RM-2001',   'Tepung Tapioka',                     'RM',  'kg',    'Gudang Bahan Kering', 12000,   'aktif'],
        ['RM-2010',   'Guar Gum',                           'RM',  'kg',    'Gudang Bahan Kering', 95000,   'aktif'],
        ['RM-2011',   'Karagenan',                          'RM',  'kg',    'Gudang Bahan Kering', 135000,  'aktif'],
        ['RM-2020',   'Taurine',                            'RM',  'kg',    'Gudang Bahan Kering', 280000,  'aktif'],
        ['RM-2021',   'Premix Vitamin & Mineral Kucing',    'RM',  'kg',    'Gudang Bahan Kering', 420000,  'aktif'],
        ['PK-3001',   'Retort Pouch 85 g Life Cat Tuna',    'PM',  'pcs',   'Gudang Kemasan',      650,     'aktif'],
        ['PK-3002',   'Retort Pouch 85 g Life Cat Mackerel','PM',  'pcs',   'Gudang Kemasan',      650,     'aktif'],
        ['PK-3101',   'Karton Master isi 24 Pouch',         'PM',  'pcs',   'Gudang Kemasan',      4500,    'aktif'],
        ['PK-3201',   'Kaleng 401 Lacquer Putih',           'PM',  'pcs',   'Gudang Kemasan',      2800,    'aktif'],
        ['PK-3202',   'Tutup Kaleng Easy Open',             'PM',  'pcs',   'Gudang Kemasan',      1200,    'aktif'],
        ['PK-3210',   'Label Kaleng Life Cat Tuna',         'PM',  'pcs',   'Gudang Kemasan',      150,     'aktif'],
        ['CN-4001',   'Minyak Ikan',                        'CN',  'kg',    'Gudang Bahan Penolong',65000,  'aktif'],
        ['CN-4002',   'Garam',                              'CN',  'kg',    'Gudang Bahan Penolong',8000,   'aktif'],
        ['CN-4003',   'Perisa Palatant Tuna',               'CN',  'kg',    'Gudang Bahan Penolong',180000, 'aktif'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>Master Barang</h2>
        <p>Daftar seluruh SKU: produk jadi, bahan baku, kemasan, dan consumable</p>
    </div>
    <div class="page-head__actions">
        <button type="button" class="btn btn--light"><i data-lucide="download" aria-hidden="true"></i><span>Export</span></button>
        <a href="{{ route('master-barang.create') }}" class="btn btn--primary"><i data-lucide="plus" aria-hidden="true"></i><span>Tambah Barang</span></a>
    </div>
</section>

{{-- Ringkasan --}}
<section class="kpi-grid" aria-label="Ringkasan master barang">
    <article class="kpi">
        <span class="kpi__icon kpi__icon--blue"><i data-lucide="package" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total Barang</p>
            <p class="kpi__value">143</p>
            <p class="kpi__trend">Semua kategori</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--green"><i data-lucide="check-circle-2" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Barang Aktif</p>
            <p class="kpi__value">138</p>
            <p class="kpi__trend">96,5% dari total</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--amber"><i data-lucide="pause-circle" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Nonaktif</p>
            <p class="kpi__value">5</p>
            <p class="kpi__trend">Tidak dipakai produksi</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--indigo"><i data-lucide="sparkles" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">SKU Baru Bulan Ini</p>
            <p class="kpi__value">4</p>
            <p class="kpi__trend kpi__trend--up"><i data-lucide="trending-up" aria-hidden="true"></i>Oktober 2026</p>
        </div>
    </article>
</section>

{{-- Daftar barang --}}
<section class="panel panel--fit">

    <div class="toolbar">
        <div class="tabs" role="tablist" aria-label="Filter kategori">
            <button type="button" class="tabs__btn is-active" data-filter="all">Semua</button>
            <button type="button" class="tabs__btn" data-filter="FG">Finished Good</button>
            <button type="button" class="tabs__btn" data-filter="WIP">WIP</button>
            <button type="button" class="tabs__btn" data-filter="RM">Raw Material</button>
            <button type="button" class="tabs__btn" data-filter="PM">Packaging</button>
            <button type="button" class="tabs__btn" data-filter="CN">Consumable</button>
        </div>

        <div class="toolbar__right">
            <label class="search">
                <i data-lucide="search" aria-hidden="true"></i>
                <input type="search" id="planSearch" placeholder="Cari kode, nama, atau gudang..." aria-label="Cari barang">
            </label>
            <select class="select" aria-label="Filter status">
                <option>Semua Status</option>
                <option>Aktif</option>
                <option>Nonaktif</option>
            </select>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table" id="planTable">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Satuan</th>
                    <th>Gudang Default</th>
                    <th class="table__right">Harga Rata-rata</th>
                    <th>Status</th>
                    <th class="table__right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $it)
                    @php
                        [$catLabel, $catBadge] = $categories[$it[2]];
                    @endphp
                    <tr data-status="{{ $it[2] }}" data-search="{{ strtolower($it[0].' '.$it[1].' '.$it[4]) }}">
                        <td class="table__code"><a href="{{ route('master-barang.show', $it[0]) }}">{{ $it[0] }}</a></td>
                        <td><span class="cell-title">{{ $it[1] }}</span></td>
                        <td><span class="badge badge--{{ $catBadge }}">{{ $catLabel }}</span></td>
                        <td>{{ $it[3] }}</td>
                        <td>{{ $it[4] }}</td>
                        <td class="table__right">Rp {{ number_format($it[5], 0, ',', '.') }}</td>
                        <td>
                            <span class="badge badge--{{ $it[6] === 'aktif' ? 'ok' : 'draft' }}">
                                {{ $it[6] === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <div class="table__actions">
                                <a href="{{ route('master-barang.show', $it[0]) }}" class="icon-btn icon-btn--sm" aria-label="Buka barang"><i data-lucide="eye" aria-hidden="true"></i></a>
                                <button type="button" class="icon-btn icon-btn--sm" aria-label="Ubah"><i data-lucide="pencil" aria-hidden="true"></i></button>
                                <button type="button" class="icon-btn icon-btn--sm" aria-label="Lainnya"><i data-lucide="more-vertical" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="empty" id="planEmpty" hidden>
            <i data-lucide="search-x" aria-hidden="true"></i>
            <p>Tidak ada barang yang cocok.</p>
        </div>
    </div>

    <div class="panel__foot">
        <p>Menampilkan 1-23 dari 143 data</p>
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