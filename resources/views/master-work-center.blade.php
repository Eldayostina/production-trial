@extends('layouts.app')

@section('page_title', 'Master Work Center')
@section('breadcrumb_parent', 'Master Data')

@section('content')

@php
    $categories = [
        'pouch'       => ['Produksi Pouch', 'run'],
        'kaleng'      => ['Produksi Kaleng', 'run'],
        'kitchen'     => ['Preparation / Dapur', 'done'],
        'sterilisasi' => ['Sterilisasi (Retort)', 'wait'],
        'packaging'   => ['Secondary Packaging', 'ok'],
    ];

    // [kode, nama, kategori, kapasitas, pic, status]
    $workCenters = [
        ['WC-POUCH-01', 'Line Pouch 1 (Filling & Sealing)', 'pouch', '3,500 Pouch / Jam', 'Budi Santoso', 'aktif'],
        ['WC-POUCH-02', 'Line Pouch 2 (High Speed)', 'pouch', '4,000 Pouch / Jam', 'Ahmad Fauzi', 'aktif'],
        ['WC-CAN-01', 'Line Kaleng 1 (Can Seaming)', 'kaleng', '1,800 Kaleng / Jam', 'Siti Rahma', 'aktif'],
        ['WC-COOK-01', 'Kitchen / Dapur Pemasakan (Cooking)', 'kitchen', '500 Kg / Batch', 'Joko Widodo', 'aktif'],
        ['WC-RETORT-01', 'Area Retort & Sterilisasi (Autoclave)', 'sterilisasi', '2 Retort / Cycle', 'Rian Hidayat', 'maintenance'],
        ['WC-PACK-01', 'Secondary Packaging & Boxing', 'packaging', '5,000 Pcs / Jam', 'Dewi Lestari', 'aktif'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>Master Work Center</h2>
        <p>Kelola lini produksi, mesin, dan area kerja di PT. Evo Manufacturing Indonesia</p>
    </div>
    <div class="page-head__actions">
        <button type="button" class="btn btn--light"><i data-lucide="download" aria-hidden="true"></i><span>Export</span></button>
        <a href="{{ route('master.work-center.create') }}" class="btn btn--primary"><i data-lucide="plus" aria-hidden="true"></i><span>Tambah Work Center</span></a>
    </div>
</section>

{{-- Ringkasan KPI --}}
<section class="kpi-grid" aria-label="Ringkasan work center">
    <article class="kpi">
        <span class="kpi__icon kpi__icon--blue"><i data-lucide="cpu" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total Work Center</p>
            <p class="kpi__value">6</p>
            <p class="kpi__trend">Seluruh area pabrik</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--green"><i data-lucide="check-circle-2" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Status Aktif</p>
            <p class="kpi__value">5</p>
            <p class="kpi__trend">Siap beroperasi</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--amber"><i data-lucide="wrench" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Maintenance</p>
            <p class="kpi__value">1</p>
            <p class="kpi__trend">Dalam perbaikan</p>
        </div>
    </article>
</section>

{{-- Daftar Work Center --}}
<section class="panel panel--fit">

    <div class="toolbar">
        <div class="tabs" role="tablist" aria-label="Filter kategori">
            <button type="button" class="tabs__btn is-active" data-filter="all">Semua</button>
            <button type="button" class="tabs__btn" data-filter="pouch">Produksi Pouch</button>
            <button type="button" class="tabs__btn" data-filter="kaleng">Produksi Kaleng</button>
            <button type="button" class="tabs__btn" data-filter="kitchen">Preparation</button>
            <button type="button" class="tabs__btn" data-filter="sterilisasi">Sterilisasi</button>
        </div>

        <div class="toolbar__right">
            <label class="search">
                <i data-lucide="search" aria-hidden="true"></i>
                <input type="search" id="planSearch" placeholder="Cari kode, nama, atau PIC..." aria-label="Cari work center">
            </label>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table" id="planTable">
            <thead>
                <tr>
                    <th>Kode WC</th>
                    <th>Nama Work Center</th>
                    <th>Kategori / Area</th>
                    <th>Kapasitas Standar</th>
                    <th>PIC / Supervisor</th>
                    <th>Status</th>
                    <th class="table__right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($workCenters as $wc)
                    @php
                        [$catLabel, $catBadge] = $categories[$wc[2]];
                    @endphp
                    <tr data-status="{{ $wc[2] }}" data-search="{{ strtolower($wc[0].' '.$wc[1].' '.$wc[4]) }}">
                        <td class="table__code"><a href="{{ route('master.work-center.show', $wc[0]) }}">{{ $wc[0] }}</a></td>
                        <td><span class="cell-title">{{ $wc[1] }}</span></td>
                        <td><span class="badge badge--{{ $catBadge }}">{{ $catLabel }}</span></td>
                        <td>{{ $wc[3] }}</td>
                        <td>{{ $wc[4] }}</td>
                        <td>
                            <span class="badge badge--{{ $wc[5] === 'aktif' ? 'ok' : 'warn' }}">
                                {{ $wc[5] === 'aktif' ? 'Aktif' : 'Maintenance' }}
                            </span>
                        </td>
                        <td>
                            <div class="table__actions">
                                <a href="{{ route('master.work-center.show', $wc[0]) }}" class="icon-btn icon-btn--sm" aria-label="Buka detail"><i data-lucide="eye" aria-hidden="true"></i></a>
                                <button type="button" class="icon-btn icon-btn--sm" aria-label="Ubah"><i data-lucide="pencil" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="empty" id="planEmpty" hidden>
            <i data-lucide="search-x" aria-hidden="true"></i>
            <p>Tidak ada work center yang cocok.</p>
        </div>
    </div>

    <div class="panel__foot">
        <p>Menampilkan 1-6 dari 6 data</p>
    </div>

</section>

@endsection