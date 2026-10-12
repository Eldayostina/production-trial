@extends('layouts.app')

@section('page_title', 'Stock Transfer')
@section('breadcrumb_parent', 'Inventory')

@section('content')

@php
    // DATA DUMMY - nanti diganti data dari database (Controller)
    $statuses = [
        'draft'    => ['Draft', 'draft'],
        'approval' => ['Menunggu Approval', 'wait'],
        'approved' => ['Disetujui', 'done'],
        'sent'     => ['Dikirim', 'run'],
        'received' => ['Diterima', 'ok'],
        'canceled' => ['Dibatalkan', 'fail'],
    ];

    // [no transfer, tanggal, dari gudang, ke gudang, ref plan, jumlah item, total qty, progress %, status]
    $transfers = [
        ['ST-2026-0087', '10 Okt 2026', 'Cold Storage',           'Area Produksi Line Pouch 1', 'PP-2026-0018', 8,  '312,50 kg',  70,  'sent'],
        ['ST-2026-0086', '10 Okt 2026', 'Gudang Kemasan',         'Area Produksi Line Pouch 1', 'PP-2026-0018', 3,  '5.250 pcs',  100, 'received'],
        ['ST-2026-0085', '09 Okt 2026', 'Gudang Bahan Kering',    'Area Produksi Line Pouch 2', 'PP-2026-0017', 6,  '45,80 kg',   0,   'approval'],
        ['ST-2026-0084', '09 Okt 2026', 'Cold Storage',           'Area Produksi Line Kaleng 1','PP-2026-0016', 5,  '1.240,00 kg',100, 'received'],
        ['ST-2026-0083', '08 Okt 2026', 'Gudang Bahan Penolong',  'Area Produksi Line Pouch 1', 'PP-2026-0015', 4,  '12,40 kg',   100, 'received'],
        ['ST-2026-0082', '08 Okt 2026', 'Gudang Kemasan',         'Area Produksi Line Kaleng 1','PP-2026-0016', 4,  '9.600 pcs',  50,  'approved'],
        ['ST-2026-0081', '07 Okt 2026', 'Cold Storage',           'Area Produksi Line Pouch 1', 'PP-2026-0015', 7,  '210,00 kg',  0,   'draft'],
        ['ST-2026-0080', '06 Okt 2026', 'Gudang Bahan Kering',    'Area Produksi Line Pouch 1', 'PP-2026-0014', 5,  '33,20 kg',   0,   'canceled'],
    ];
@endphp

{{-- Judul & aksi --}}
<section class="page-head">
    <div>
        <h2>Stock Transfer</h2>
        <p>Perpindahan material dari gudang ke area produksi berdasarkan Production Plan</p>
    </div>
    <div class="page-head__actions">
        <button type="button" class="btn btn--light"><i data-lucide="download" aria-hidden="true"></i><span>Export</span></button>
        <a href="{{ route('stock-transfer.create') }}" class="btn btn--primary"><i data-lucide="plus" aria-hidden="true"></i><span>Buat Stock Transfer</span></a>
    </div>
</section>

{{-- Ringkasan --}}
<section class="kpi-grid" aria-label="Ringkasan stock transfer">
    <article class="kpi">
        <span class="kpi__icon kpi__icon--blue"><i data-lucide="arrow-left-right" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total Transfer</p>
            <p class="kpi__value">28</p>
            <p class="kpi__trend">Oktober 2026</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--amber"><i data-lucide="hourglass" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Menunggu Approval</p>
            <p class="kpi__value">4</p>
            <p class="kpi__trend kpi__trend--warn"><i data-lucide="clock" aria-hidden="true"></i>Perlu ditinjau</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--indigo"><i data-lucide="truck" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Dalam Pengiriman</p>
            <p class="kpi__value">3</p>
            <p class="kpi__trend">Menuju area produksi</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--green"><i data-lucide="package-check" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Selesai Bulan Ini</p>
            <p class="kpi__value">18</p>
            <p class="kpi__trend kpi__trend--up"><i data-lucide="trending-up" aria-hidden="true"></i>+4 dari bulan lalu</p>
        </div>
    </article>
</section>

{{-- Daftar transfer --}}
<section class="panel panel--fit">

    <div class="toolbar">
        <div class="tabs" role="tablist" aria-label="Filter status">
            <button type="button" class="tabs__btn is-active" data-filter="all">Semua</button>
            <button type="button" class="tabs__btn" data-filter="draft">Draft</button>
            <button type="button" class="tabs__btn" data-filter="approval">Approval</button>
            <button type="button" class="tabs__btn" data-filter="approved">Disetujui</button>
            <button type="button" class="tabs__btn" data-filter="sent">Dikirim</button>
            <button type="button" class="tabs__btn" data-filter="received">Diterima</button>
        </div>

        <div class="toolbar__right">
            <label class="search">
                <i data-lucide="search" aria-hidden="true"></i>
                <input type="search" id="planSearch" placeholder="Cari no. transfer, batch, atau gudang..." aria-label="Cari stock transfer">
            </label>
            <select class="select" aria-label="Filter gudang asal">
                <option>Semua Gudang</option>
                <option>Cold Storage</option>
                <option>Gudang Bahan Kering</option>
                <option>Gudang Bahan Penolong</option>
                <option>Gudang Kemasan</option>
            </select>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table" id="planTable">
            <thead>
                <tr>
                    <th>No. Transfer</th>
                    <th>Tanggal</th>
                    <th>Dari Gudang</th>
                    <th>Ke Gudang</th>
                    <th>Ref. Plan</th>
                    <th class="table__right">Item</th>
                    <th class="table__right">Total Qty</th>
                    <th>Progress</th>
                    <th>Status</th>
                    <th class="table__right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transfers as $t)
                    @php [$label, $badge] = $statuses[$t[8]]; @endphp
                    <tr data-status="{{ $t[8] }}" data-search="{{ strtolower($t[0].' '.$t[2].' '.$t[3].' '.$t[4]) }}">
                        <td class="table__code"><a href="{{ route('stock-transfer.show', $t[0]) }}">{{ $t[0] }}</a></td>
                        <td>{{ $t[1] }}</td>
                        <td>{{ $t[2] }}</td>
                        <td>{{ $t[3] }}</td>
                        <td>{{ $t[4] }}</td>
                        <td class="table__right">{{ $t[5] }}</td>
                        <td class="table__right">{{ $t[6] }}</td>
                        <td>
                            <div class="progress" role="progressbar" aria-valuenow="{{ $t[7] }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress__track"><div class="progress__fill" style="width: {{ $t[7] }}%"></div></div>
                                <span class="progress__text">{{ $t[7] }}%</span>
                            </div>
                        </td>
                        <td><span class="badge badge--{{ $badge }}">{{ $label }}</span></td>
                        <td>
                            <div class="table__actions">
                                <a href="{{ route('stock-transfer.show', $t[0]) }}" class="icon-btn icon-btn--sm" aria-label="Buka transfer"><i data-lucide="eye" aria-hidden="true"></i></a>
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
            <p>Tidak ada stock transfer yang cocok.</p>
        </div>
    </div>

    <div class="panel__foot">
        <p>Menampilkan 1-8 dari 28 data</p>
        <nav class="pagination" aria-label="Halaman">
            <button type="button" class="pagination__btn" disabled aria-label="Sebelumnya"><i data-lucide="chevron-left" aria-hidden="true"></i></button>
            <button type="button" class="pagination__btn is-active" aria-current="page">1</button>
            <button type="button" class="pagination__btn">2</button>
            <button type="button" class="pagination__btn">3</button>
            <button type="button" class="pagination__btn">4</button>
            <button type="button" class="pagination__btn" aria-label="Berikutnya"><i data-lucide="chevron-right" aria-hidden="true"></i></button>
        </nav>
    </div>

</section>

@endsection