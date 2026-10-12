@extends('layouts.app')

@section('page_title', 'Production Plan')
@section('breadcrumb_parent', 'Production')

@section('content')

@php
    // DATA DUMMY - nanti diganti data dari database (Controller)
    $statuses = [
        'draft'    => ['Draft', 'draft'],
        'approval' => ['Menunggu Approval', 'wait'],
        'approved' => ['Disetujui', 'done'],
        'running'  => ['Berjalan', 'run'],
        'finished' => ['Selesai', 'ok'],
        'canceled' => ['Dibatalkan', 'fail'],
    ];

    $plans = [
        ['PP-2026-0018', 'Life Cat Pouch Tuna & Chicken 85 g', 'Trial formula baru',          5000, 'Line Pouch 1', '10 Okt - 14 Okt 2026', 35,  'running'],
        ['PP-2026-0017', 'Life Cat Pouch Mackerel 85 g',       'Trial supplier makarel baru', 3000, 'Line Pouch 2', '12 Okt - 15 Okt 2026', 0,   'approval'],
        ['PP-2026-0016', 'Life Cat Kaleng Tuna 400 g',         'Trial parameter retort',      2000, 'Line Kaleng 1','06 Okt - 09 Okt 2026', 100, 'finished'],
        ['PP-2026-0015', 'Life Cat Pouch Tuna & Chicken 85 g', 'Trial kemasan pouch baru',    8000, 'Line Pouch 1', '01 Okt - 07 Okt 2026', 62,  'running'],
        ['PP-2026-0014', 'Life Cat Pouch Mackerel 85 g',       'Trial varian rasa baru',      4000, 'Line Pouch 2', '15 Okt - 20 Okt 2026', 0,   'approved'],
        ['PP-2026-0013', 'Life Cat Kaleng Tuna 400 g',         'Trial formula baru',          1500, 'Line Kaleng 1','20 Okt - 23 Okt 2026', 0,   'draft'],
        ['PP-2026-0012', 'Life Cat Pouch Tuna & Chicken 85 g', 'Trial parameter sealing',     2500, 'Line Pouch 1', '25 Sep - 28 Sep 2026', 100, 'finished'],
        ['PP-2026-0011', 'Life Cat Kaleng Tuna 400 g',         'Trial supplier tuna baru',    1200, 'Line Kaleng 1','22 Sep - 26 Sep 2026', 0,   'canceled'],
    ];
@endphp

{{-- Judul & aksi --}}
<section class="page-head">
    <div>
        <h2>Production Plan</h2>
        <p>Rencana produksi untuk kegiatan trial</p>
    </div>
    <div class="page-head__actions">
        <button type="button" class="btn btn--light"><i data-lucide="download" aria-hidden="true"></i><span>Export</span></button>
        <a href="{{ route('production-plan.create') }}" class="btn btn--primary"><i data-lucide="plus" aria-hidden="true"></i><span>Buat Production Plan</span></a>
    </div>
</section>

{{-- Ringkasan --}}
<section class="kpi-grid" aria-label="Ringkasan production plan">
    <article class="kpi">
        <span class="kpi__icon kpi__icon--blue"><i data-lucide="clipboard-list" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total Plan</p>
            <p class="kpi__value">18</p>
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
        <span class="kpi__icon kpi__icon--indigo"><i data-lucide="play-circle" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Sedang Berjalan</p>
            <p class="kpi__value">6</p>
            <p class="kpi__trend">Di 4 line produksi</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--green"><i data-lucide="check-circle-2" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Selesai</p>
            <p class="kpi__value">8</p>
            <p class="kpi__trend kpi__trend--up"><i data-lucide="trending-up" aria-hidden="true"></i>+2 dari bulan lalu</p>
        </div>
    </article>
</section>

{{-- Daftar plan --}}
<section class="panel panel--fit">

    <div class="toolbar">
        <div class="tabs" role="tablist" aria-label="Filter status">
            <button type="button" class="tabs__btn is-active" data-filter="all">Semua</button>
            <button type="button" class="tabs__btn" data-filter="draft">Draft</button>
            <button type="button" class="tabs__btn" data-filter="approval">Approval</button>
            <button type="button" class="tabs__btn" data-filter="running">Berjalan</button>
            <button type="button" class="tabs__btn" data-filter="finished">Selesai</button>
        </div>

        <div class="toolbar__right">
            <label class="search">
                <i data-lucide="search" aria-hidden="true"></i>
                <input type="search" id="planSearch" placeholder="Cari no. plan atau produk..." aria-label="Cari production plan">
            </label>
            <select class="select" aria-label="Filter work center">
                <option>Semua Line</option>
                <option>Line 1</option>
                <option>Line 2</option>
                <option>Line 3</option>
                <option>Line 4</option>
            </select>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table" id="planTable">
            <thead>
                <tr>
                    <th>No. Plan</th>
                    <th>Produk</th>
                    <th>Target</th>
                    <th>Work Center</th>
                    <th>Jadwal</th>
                    <th>Progress</th>
                    <th>Status</th>
                    <th class="table__right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($plans as $p)
                    @php [$label, $badge] = $statuses[$p[7]]; @endphp
                    <tr data-status="{{ $p[7] }}" data-search="{{ strtolower($p[0].' '.$p[1]) }}">
                        <td class="table__code">{{ $p[0] }}</td>
                        <td>
                            <span class="cell-title">{{ $p[1] }}</span>
                            <small class="cell-sub">{{ $p[2] }}</small>
                        </td>
                        <td>{{ number_format($p[3], 0, ',', '.') }} pcs</td>
                        <td>{{ $p[4] }}</td>
                        <td>{{ $p[5] }}</td>
                        <td>
                            <div class="progress" role="progressbar" aria-valuenow="{{ $p[6] }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress__track"><div class="progress__fill" style="width: {{ $p[6] }}%"></div></div>
                                <span class="progress__text">{{ $p[6] }}%</span>
                            </div>
                        </td>
                        <td><span class="badge badge--{{ $badge }}">{{ $label }}</span></td>
                        <td>
                            <div class="table__actions">
                                <a href="{{ route('production-plan.show', $p[0]) }}" class="icon-btn icon-btn--sm" aria-label="Lihat detail"><i data-lucide="eye" aria-hidden="true"></i></a>
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
            <p>Tidak ada production plan yang cocok.</p>
        </div>
    </div>

    <div class="panel__foot">
        <p>Menampilkan 1-8 dari 18 data</p>
        <nav class="pagination" aria-label="Halaman">
            <button type="button" class="pagination__btn" disabled aria-label="Sebelumnya"><i data-lucide="chevron-left" aria-hidden="true"></i></button>
            <button type="button" class="pagination__btn is-active" aria-current="page">1</button>
            <button type="button" class="pagination__btn">2</button>
            <button type="button" class="pagination__btn">3</button>
            <button type="button" class="pagination__btn" aria-label="Berikutnya"><i data-lucide="chevron-right" aria-hidden="true"></i></button>
        </nav>
    </div>

</section>

@endsection