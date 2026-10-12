@extends('layouts.app')

@section('page_title', 'Bill of Materials')
@section('breadcrumb_parent', 'Master Data')

@section('content')

@php
    // DATA DUMMY - nanti diganti data dari database (Controller)
    $statuses = [
        'draft'    => ['Draft', 'draft'],
        'review'   => ['Review QA', 'wait'],
        'released' => ['Released', 'ok'],
        'obsolete' => ['Obsolete', 'obsolete'],
    ];

    // [no BOM, produk, revisi, tipe, base qty, jumlah bahan, status, tanggal efektif, pemilik]
    $boms = [
        ['BOM-LC-P01-R03', 'Life Cat Pouch Tuna & Chicken 85 g', 'Rev. 03', 'BOM Trial (R&D)', '1.000 pouch',  15, 'review',   '10 Okt 2026', 'Rina Wulandari'],
        ['BOM-LC-P01-R02', 'Life Cat Pouch Tuna & Chicken 85 g', 'Rev. 02', 'BOM Produksi',     '1.000 pouch',  14, 'released', '01 Sep 2026', 'Rina Wulandari'],
        ['BOM-LC-P01-R01', 'Life Cat Pouch Tuna & Chicken 85 g', 'Rev. 01', 'BOM Produksi',     '1.000 pouch',  14, 'obsolete', '15 Jun 2026', 'Rina Wulandari'],
        ['BOM-LC-M01-R01', 'Life Cat Pouch Mackerel 85 g',       'Rev. 01', 'BOM Produksi',     '1.000 pouch',  10, 'released', '20 Jul 2026', 'Rina Wulandari'],
        ['BOM-LC-K01-R04', 'Life Cat Kaleng Tuna 400 g',         'Rev. 04', 'BOM Trial (R&D)', '1.000 kaleng', 12, 'draft',    '20 Okt 2026', 'Rina Wulandari'],
        ['BOM-LC-K01-R03', 'Life Cat Kaleng Tuna 400 g',         'Rev. 03', 'BOM Produksi',     '1.000 kaleng', 11, 'released', '05 Agu 2026', 'Rina Wulandari'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>Bill of Materials (BOM)</h2>
        <p>Formula produk: bahan apa saja dan berapa pemakaian per unit. Dimiliki R&amp;D.</p>
    </div>
    <div class="page-head__actions">
        <button type="button" class="btn btn--light"><i data-lucide="download" aria-hidden="true"></i><span>Export</span></button>
        <a href="{{ route('bom.create') }}" class="btn btn--primary"><i data-lucide="plus" aria-hidden="true"></i><span>Buat BOM Baru</span></a>
    </div>
</section>

<section class="kpi-grid" aria-label="Ringkasan BOM">
    <article class="kpi">
        <span class="kpi__icon kpi__icon--blue"><i data-lucide="list-tree" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total BOM</p>
            <p class="kpi__value">6</p>
            <p class="kpi__trend">Semua revisi</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--green"><i data-lucide="badge-check" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Released</p>
            <p class="kpi__value">3</p>
            <p class="kpi__trend kpi__trend--up">Siap dipakai Production Plan</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--amber"><i data-lucide="hourglass" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Dalam Review QA</p>
            <p class="kpi__value">1</p>
            <p class="kpi__trend kpi__trend--warn"><i data-lucide="clock" aria-hidden="true"></i>Menunggu persetujuan</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--indigo"><i data-lucide="file-pen" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Draft</p>
            <p class="kpi__value">1</p>
            <p class="kpi__trend">Masih disusun R&amp;D</p>
        </div>
    </article>
</section>

<section class="panel panel--fit">

    <div class="toolbar">
        <div class="tabs" role="tablist" aria-label="Filter status">
            <button type="button" class="tabs__btn is-active" data-filter="all">Semua</button>
            <button type="button" class="tabs__btn" data-filter="draft">Draft</button>
            <button type="button" class="tabs__btn" data-filter="review">Review QA</button>
            <button type="button" class="tabs__btn" data-filter="released">Released</button>
            <button type="button" class="tabs__btn" data-filter="obsolete">Obsolete</button>
        </div>

        <div class="toolbar__right">
            <label class="search">
                <i data-lucide="search" aria-hidden="true"></i>
                <input type="search" id="planSearch" placeholder="Cari no. BOM atau produk..." aria-label="Cari BOM">
            </label>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table" id="planTable">
            <thead>
                <tr>
                    <th>No. BOM</th>
                    <th>Produk</th>
                    <th>Revisi</th>
                    <th>Tipe</th>
                    <th>Base Qty</th>
                    <th class="table__right">Bahan</th>
                    <th>Efektif</th>
                    <th>Status</th>
                    <th class="table__right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($boms as $b)
                    @php [$label, $badge] = $statuses[$b[6]]; @endphp
                    <tr data-status="{{ $b[6] }}" data-search="{{ strtolower($b[0].' '.$b[1]) }}">
                        <td class="table__code"><a href="{{ route('bom.show', $b[0]) }}">{{ $b[0] }}</a></td>
                        <td>
                            <span class="cell-title">{{ $b[1] }}</span>
                            <small class="cell-sub">Pemilik: {{ $b[8] }}</small>
                        </td>
                        <td>{{ $b[2] }}</td>
                        <td>{{ $b[3] }}</td>
                        <td>{{ $b[4] }}</td>
                        <td class="table__right">{{ $b[5] }}</td>
                        <td>{{ $b[7] }}</td>
                        <td><span class="badge badge--{{ $badge }}">{{ $label }}</span></td>
                        <td>
                            <div class="table__actions">
                                <a href="{{ route('bom.show', $b[0]) }}" class="icon-btn icon-btn--sm" aria-label="Lihat BOM"><i data-lucide="eye" aria-hidden="true"></i></a>
                                <button type="button" class="icon-btn icon-btn--sm" aria-label="Lainnya"><i data-lucide="more-vertical" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="empty" id="planEmpty" hidden>
            <i data-lucide="search-x" aria-hidden="true"></i>
            <p>Tidak ada BOM yang cocok.</p>
        </div>
    </div>

    <div class="panel__foot">
        <p>Menampilkan 1-6 dari 6 data</p>
    </div>

</section>

@endsection