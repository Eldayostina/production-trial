@extends('layouts.app')

@section('page_title', 'Master Satuan (UoM)')
@section('breadcrumb_parent', 'Master Data')

@section('content')

@php
    $categories = [
        'berat'   => ['Berat / Massa', 'ok'],
        'unit'    => ['Unit / Pcs', 'run'],
        'volume'  => ['Volume / Cairan', 'done'],
        'kemasan' => ['Kemasan Sekunder', 'wait'],
    ];

    // [kode, nama satuan, kategori, simbol/faktor, jumlah sku aktif, status]
    $uoms = [
        ['KG', 'Kilogram', 'berat', 'Satuan Dasar Massa', '85 SKU', 'aktif'],
        ['G', 'Gram', 'berat', '1 KG = 1.000 G', '12 SKU', 'aktif'],
        ['PCS', 'Pieces / Buah', 'unit', 'Satuan Dasar Unit', '45 SKU', 'aktif'],
        ['POUCH', 'Pouch 85g', 'unit', 'Kemasan Primer Wet Food', '3 SKU', 'aktif'],
        ['KALENG', 'Kaleng 400g', 'unit', 'Kemasan Primer Kaleng', '2 SKU', 'aktif'],
        ['LITER', 'Liter', 'volume', 'Satuan Dasar Cairan', '8 SKU', 'aktif'],
        ['ML', 'Milliliter', 'volume', '1 Liter = 1.000 ML', '5 SKU', 'aktif'],
        ['KARTON', 'Karton / Box', 'kemasan', '1 Karton = 24 Pouch', '10 SKU', 'aktif'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>Master Satuan (UoM)</h2>
        <p>Kelola satuan ukuran, berat, volume, dan faktor konversi untuk inventaris & produksi</p>
    </div>
    <div class="page-head__actions">
        <button type="button" class="btn btn--light"><i data-lucide="download" aria-hidden="true"></i><span>Export</span></button>
        <a href="{{ route('master.uom.create') }}" class="btn btn--primary"><i data-lucide="plus" aria-hidden="true"></i><span>Tambah Satuan</span></a>
    </div>
</section>

{{-- Ringkasan KPI --}}
<section class="kpi-grid" aria-label="Ringkasan satuan">
    <article class="kpi">
        <span class="kpi__icon kpi__icon--blue"><i data-lucide="ruler" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total Satuan (UoM)</p>
            <p class="kpi__value">8</p>
            <p class="kpi__trend">Aktif digunakan sistem</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--green"><i data-lucide="check-circle-2" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Kategori Berat & Unit</p>
            <p class="kpi__value">5</p>
            <p class="kpi__trend">Standar operasional</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--indigo"><i data-lucide="layers" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Konversi Turunan</p>
            <p class="kpi__value">3</p>
            <p class="kpi__trend">Karton / Gram / ML</p>
        </div>
    </article>
</section>

{{-- Daftar Satuan --}}
<section class="panel panel--fit">

    <div class="toolbar">
        <div class="tabs" role="tablist" aria-label="Filter kategori">
            <button type="button" class="tabs__btn is-active" data-filter="all">Semua</button>
            <button type="button" class="tabs__btn" data-filter="berat">Berat</button>
            <button type="button" class="tabs__btn" data-filter="unit">Unit / Pcs</button>
            <button type="button" class="tabs__btn" data-filter="volume">Volume</button>
            <button type="button" class="tabs__btn" data-filter="kemasan">Kemasan</button>
        </div>

        <div class="toolbar__right">
            <label class="search">
                <i data-lucide="search" aria-hidden="true"></i>
                <input type="search" id="planSearch" placeholder="Cari kode atau nama satuan..." aria-label="Cari satuan">
            </label>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table" id="planTable">
            <thead>
                <tr>
                    <th>Kode UoM</th>
                    <th>Nama Satuan</th>
                    <th>Kategori</th>
                    <th>Keterangan / Faktor Konversi</th>
                    <th>SKU Terkait</th>
                    <th>Status</th>
                    <th class="table__right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($uoms as $u)
                    @php
                        [$catLabel, $catBadge] = $categories[$u[2]];
                    @endphp
                    <tr data-status="{{ $u[2] }}" data-search="{{ strtolower($u[0].' '.$u[1].' '.$u[3]) }}">
                        <td class="table__code"><a href="{{ route('master.uom.show', $u[0]) }}">{{ $u[0] }}</a></td>
                        <td><span class="cell-title">{{ $u[1] }}</span></td>
                        <td><span class="badge badge--{{ $catBadge }}">{{ $catLabel }}</span></td>
                        <td>{{ $u[3] }}</td>
                        <td>{{ $u[4] }}</td>
                        <td><span class="badge badge--ok">Aktif</span></td>
                        <td>
                            <div class="table__actions">
                                <a href="{{ route('master.uom.show', $u[0]) }}" class="icon-btn icon-btn--sm" aria-label="Buka detail"><i data-lucide="eye" aria-hidden="true"></i></a>
                                <button type="button" class="icon-btn icon-btn--sm" aria-label="Ubah"><i data-lucide="pencil" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="empty" id="planEmpty" hidden>
            <i data-lucide="search-x" aria-hidden="true"></i>
            <p>Tidak ada satuan yang cocok.</p>
        </div>
    </div>

    <div class="panel__foot">
        <p>Menampilkan 1-8 dari 8 data</p>
    </div>

</section>

@endsection