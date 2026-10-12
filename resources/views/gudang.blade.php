@extends('layouts.app')

@section('page_title', 'Master Gudang')
@section('breadcrumb_parent', 'Master Data')

@section('content')

@php
    // DATA DUMMY - nanti diganti data dari database (Controller)
    $conds = [
        'frozen'  => ['Beku (Frozen)', 'done'],
        'chiller' => ['Dingin (Chiller)', 'run'],
        'ambient' => ['Suhu Ruang', 'draft'],
    ];

    // [kode, nama, tipe, kondisi, suhu min, suhu max, kapasitas, satuan, terisi %, PIC, status]
    $warehouses = [
        ['GD-CS01',  'Cold Storage',           'Bahan Baku',      'frozen',  -22, -18,  50000, 'kg',     72, 'Agus Pratama',    'aktif'],
        ['GD-CH01',  'Chiller Bahan Baku',     'Bahan Baku',      'chiller', 0,   4,    10000, 'kg',     45, 'Agus Pratama',    'aktif'],
        ['GD-DRY01', 'Gudang Bahan Kering',    'Bahan Baku',      'ambient', null, 30,  80000, 'kg',     58, 'Siti Nurhaliza',  'aktif'],
        ['GD-AUX01', 'Gudang Bahan Penolong',  'Bahan Penolong',  'ambient', null, 30,  20000, 'kg',     33, 'Siti Nurhaliza',  'aktif'],
        ['GD-PKG01', 'Gudang Kemasan',         'Kemasan',         'ambient', null, 35,  600,   'pallet', 64, 'Joko Susilo',     'aktif'],
        ['GD-FG01',  'Gudang Barang Jadi',     'Barang Jadi',     'ambient', null, 30,  800,   'pallet', 51, 'Joko Susilo',     'aktif'],
        ['GD-QRN01', 'Area Karantina',         'Karantina',       'ambient', null, 30,  100,   'pallet', 20, 'Dewi Anggraini',  'aktif'],
        ['GD-PRD01', 'Staging Produksi',       'Staging Produksi','ambient', null, 30,  30,    'pallet', 40, 'Budi Santoso',    'aktif'],
        ['GD-OLD01', 'Gudang Lama (Tidak Dipakai)', 'Bahan Baku', 'ambient', null, 35,  5000,  'kg',     0,  'Siti Nurhaliza',  'nonaktif'],
    ];

    $temp = function ($w) {
        if ($w[3] === 'ambient') { return '≤ ' . $w[5] . ' °C'; }
        return $w[4] . ' s/d ' . $w[5] . ' °C';
    };

    $active = array_filter($warehouses, fn ($w) => $w[10] === 'aktif');
    $avg = round(array_sum(array_column($active, 8)) / max(count($active), 1));
    $controlled = count(array_filter($active, fn ($w) => $w[3] !== 'ambient'));
@endphp

<section class="page-head">
    <div>
        <h2>Master Gudang</h2>
        <p>Daftar gudang dan lokasi penyimpanan. Diisi sekali, lalu dipakai di semua transaksi stok.</p>
    </div>
    <div class="page-head__actions">
        <button type="button" class="btn btn--light"><i data-lucide="download" aria-hidden="true"></i><span>Export</span></button>
        <a href="{{ route('gudang.create') }}" class="btn btn--primary"><i data-lucide="plus" aria-hidden="true"></i><span>Tambah Gudang</span></a>
    </div>
</section>

<section class="kpi-grid" aria-label="Ringkasan gudang">
    <article class="kpi">
        <span class="kpi__icon kpi__icon--blue"><i data-lucide="warehouse" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total Gudang</p>
            <p class="kpi__value">{{ count($warehouses) }}</p>
            <p class="kpi__trend">Semua lokasi</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--green"><i data-lucide="check-circle-2" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Aktif</p>
            <p class="kpi__value">{{ count($active) }}</p>
            <p class="kpi__trend kpi__trend--up">Dapat dipakai transaksi</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--indigo"><i data-lucide="snowflake" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Suhu Terkendali</p>
            <p class="kpi__value">{{ $controlled }}</p>
            <p class="kpi__trend">Beku &amp; dingin</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--amber"><i data-lucide="gauge" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Rata-rata Terisi</p>
            <p class="kpi__value">{{ $avg }}%</p>
            <p class="kpi__trend">Dari kapasitas gudang aktif</p>
        </div>
    </article>
</section>

<section class="panel panel--fit">

    <div class="toolbar">
        <div class="tabs" role="tablist" aria-label="Filter gudang">
            <button type="button" class="tabs__btn is-active" data-filter="all">Semua</button>
            <button type="button" class="tabs__btn" data-filter="frozen">Beku</button>
            <button type="button" class="tabs__btn" data-filter="chiller">Dingin</button>
            <button type="button" class="tabs__btn" data-filter="ambient">Suhu Ruang</button>
            <button type="button" class="tabs__btn" data-filter="nonaktif">Nonaktif</button>
        </div>

        <div class="toolbar__right">
            <label class="search">
                <i data-lucide="search" aria-hidden="true"></i>
                <input type="search" id="planSearch" placeholder="Cari kode atau nama gudang..." aria-label="Cari gudang">
            </label>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table" id="planTable">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Gudang</th>
                    <th>Tipe</th>
                    <th>Kondisi</th>
                    <th class="table__right">Kapasitas</th>
                    <th>Terisi</th>
                    <th>Status</th>
                    <th class="table__right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($warehouses as $w)
                    @php [$cLabel, $cBadge] = $conds[$w[3]]; @endphp
                    <tr data-status="{{ $w[10] === 'nonaktif' ? 'nonaktif' : $w[3] }}" data-search="{{ strtolower($w[0].' '.$w[1].' '.$w[2]) }}">
                        <td class="table__code"><a href="{{ route('gudang.edit', $w[0]) }}">{{ $w[0] }}</a></td>
                        <td>
                            <span class="cell-title">{{ $w[1] }}</span>
                            <small class="cell-sub">PIC: {{ $w[9] }}</small>
                        </td>
                        <td>{{ $w[2] }}</td>
                        <td>
                            <span class="badge badge--{{ $cBadge }}">{{ $cLabel }}</span>
                            <small class="cell-sub">{{ $temp($w) }}</small>
                        </td>
                        <td class="table__right">{{ number_format($w[6], 0, ',', '.') }} {{ $w[7] }}</td>
                        <td>
                            <div class="progress" role="progressbar" aria-valuenow="{{ $w[8] }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress__track"><div class="progress__fill" style="width: {{ $w[8] }}%"></div></div>
                                <span class="progress__text">{{ $w[8] }}%</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge--{{ $w[10] === 'aktif' ? 'ok' : 'obsolete' }}">{{ $w[10] === 'aktif' ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td>
                            <div class="table__actions">
                                <a href="{{ route('gudang.edit', $w[0]) }}" class="icon-btn icon-btn--sm" aria-label="Ubah gudang"><i data-lucide="pencil" aria-hidden="true"></i></a>
                                <button type="button" class="icon-btn icon-btn--sm" aria-label="Lainnya"><i data-lucide="more-vertical" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="empty" id="planEmpty" hidden>
            <i data-lucide="search-x" aria-hidden="true"></i>
            <p>Tidak ada gudang yang cocok.</p>
        </div>
    </div>

    <div class="panel__foot">
        <p>Menampilkan 1-{{ count($warehouses) }} dari {{ count($warehouses) }} data</p>
    </div>

</section>

@endsection