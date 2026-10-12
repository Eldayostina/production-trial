@extends('layouts.app')

@section('page_title', 'Master Supplier')
@section('breadcrumb_parent', 'Master Data')

@section('content')

@php
    $categories = [
        'RM' => ['Raw Material (Bahan Baku)', 'ok'],
        'PM' => ['Packaging (Kemasan)', 'run'],
        'CN' => ['Consumable / Penolong', 'done'],
    ];

    // [kode, nama supplier, kategori pasokan, kontak / pic, lead time rata-rata, rating, status]
    $suppliers = [
        ['SUP-001', 'PT Samudra Tuna Jaya', 'RM', 'Bpk. Hendra (0812-3456-7890)', '7 Hari', '4.8 / 5.0', 'aktif'],
        ['SUP-002', 'CV Bahari Nusantara', 'RM', 'Ibu Siska (0813-9876-5432)', '10 Hari', '4.5 / 5.0', 'aktif'],
        ['SUP-003', 'PT Pangan Inti Sejahtera', 'RM', 'Bpk. Joko (0821-1122-3344)', '5 Hari', '4.7 / 5.0', 'aktif'],
        ['SUP-010', 'PT Retort Pouch Indonesia', 'PM', 'Ibu Rina (0811-5566-7788)', '14 Hari', '4.6 / 5.0', 'aktif'],
        ['SUP-012', 'CV Mega Cipta Kemasan', 'PM', 'Bpk. Doni (0856-4433-2211)', '12 Hari', '4.3 / 5.0', 'aktif'],
        ['SUP-020', 'PT Kimia Pangan Nusantara', 'CN', 'Ibu Dian (0878-9900-1122)', '3 Hari', '4.9 / 5.0', 'aktif'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>Master Supplier (Pemasok)</h2>
        <p>Kelola data vendor bahan baku, kemasan, bahan penolong, serta evaluasi performa pengadaan</p>
    </div>
    <div class="page-head__actions">
        <button type="button" class="btn btn--light"><i data-lucide="download" aria-hidden="true"></i><span>Export</span></button>
        <a href="{{ route('master.supplier.create') }}" class="btn btn--primary"><i data-lucide="plus" aria-hidden="true"></i><span>Tambah Supplier</span></a>
    </div>
</section>

{{-- Ringkasan KPI --}}
<section class="kpi-grid" aria-label="Ringkasan supplier">
    <article class="kpi">
        <span class="kpi__icon kpi__icon--blue"><i data-lucide="truck" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total Supplier</p>
            <p class="kpi__value">6</p>
            <p class="kpi__trend">Vendor terdaftar aktif</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--green"><i data-lucide="check-circle-2" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Supplier Bahan Baku (RM)</p>
            <p class="kpi__value">3</p>
            <p class="kpi__trend">Tuna, Ayam, Makarel</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--indigo"><i data-lucide="star" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Rata-rata Rating Kinerja</p>
            <p class="kpi__value">4.6 / 5.0</p>
            <p class="kpi__trend">Kategori Sangat Baik</p>
        </div>
    </article>
</section>

{{-- Daftar Supplier --}}
<section class="panel panel--fit">

    <div class="toolbar">
        <div class="tabs" role="tablist" aria-label="Filter kategori">
            <button type="button" class="tabs__btn is-active" data-filter="all">Semua</button>
            <button type="button" class="tabs__btn" data-filter="RM">Bahan Baku (RM)</button>
            <button type="button" class="tabs__btn" data-filter="PM">Kemasan (PM)</button>
            <button type="button" class="tabs__btn" data-filter="CN">Consumable (CN)</button>
        </div>

        <div class="toolbar__right">
            <label class="search">
                <i data-lucide="search" aria-hidden="true"></i>
                <input type="search" id="planSearch" placeholder="Cari nama supplier atau PIC..." aria-label="Cari supplier">
            </label>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table" id="planTable">
            <thead>
                <tr>
                    <th>Kode Vendor</th>
                    <th>Nama Supplier / Perusahaan</th>
                    <th>Kategori Pasokan</th>
                    <th>Kontak & PIC</th>
                    <th>Lead Time</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th class="table__right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $sup)
                    @php
                        [$catLabel, $catBadge] = $categories[$sup[2]];
                    @endphp
                    <tr data-status="{{ $sup[2] }}" data-search="{{ strtolower($sup[0].' '.$sup[1].' '.$sup[3]) }}">
                        <td class="table__code"><a href="{{ route('master.supplier.show', $sup[0]) }}">{{ $sup[0] }}</a></td>
                        <td><span class="cell-title">{{ $sup[1] }}</span></td>
                        <td><span class="badge badge--{{ $catBadge }}">{{ $catLabel }}</span></td>
                        <td>{{ $sup[3] }}</td>
                        <td>{{ $sup[4] }}</td>
                        <td><strong>⭐ {{ $sup[5] }}</strong></td>
                        <td><span class="badge badge--ok">Aktif</span></td>
                        <td>
                            <div class="table__actions">
                                <a href="{{ route('master.supplier.show', $sup[0]) }}" class="icon-btn icon-btn--sm" aria-label="Buka detail"><i data-lucide="eye" aria-hidden="true"></i></a>
                                <button type="button" class="icon-btn icon-btn--sm" aria-label="Ubah"><i data-lucide="pencil" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="empty" id="planEmpty" hidden>
            <i data-lucide="search-x" aria-hidden="true"></i>
            <p>Tidak ada supplier yang cocok.</p>
        </div>
    </div>

    <div class="panel__foot">
        <p>Menampilkan 1-6 dari 6 data</p>
    </div>

</section>

@endsection