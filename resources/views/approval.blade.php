@extends('layouts.app')

@section('page_title', 'Approval')
@section('breadcrumb_parent', 'Quality')

@section('content')

@php
    // DATA DUMMY - nanti diganti data dari database (Controller)
    $statuses = [
        'wait'   => ['Menunggu',        'wait'],
        'run'    => ['Sedang Direview', 'run'],
        'ok'     => ['Disetujui',       'ok'],
        'fail'   => ['Ditolak',         'fail'],
        'revisi' => ['Perlu Revisi',    'draft'],
    ];

    // [no dokumen, jenis, ringkasan, diajukan oleh, tanggal, tahap, menunggu hari, status]
    $approvals = [
        ['PP-2026-0019',        'Production Plan',  'Life Cat Pouch Tuna & Chicken 85 g - Trial formula baru', 'Admin Produksi',  '10 Okt 2026', 'Review QC',        0, 'wait'],
        ['PP-2026-0017',        'Production Plan',  'Life Cat Pouch Mackerel 85 g - Trial supplier baru',      'Admin Produksi',  '09 Okt 2026', 'Review R&D',       1, 'run'],
        ['TRL-RES-2026-0042',   'Trial Result',     'Life Cat Pouch Mackerel 85 g - Hasil Lulus',              'Budi Santoso',    '08 Okt 2026', 'Persetujuan Manager', 2, 'run'],
        ['ST-2026-0085',        'Stock Transfer',   'Gudang Bahan Kering → Line Pouch 2',                      'Joko Susilo',     '09 Okt 2026', 'Review Gudang',    0, 'wait'],
        ['PP-2026-0014',        'Production Plan',  'Life Cat Pouch Mackerel 85 g - Trial varian rasa baru',   'Admin Produksi',  '07 Okt 2026', 'Review QC',        3, 'wait'],
        ['ST-2026-0082',        'Stock Transfer',   'Gudang Kemasan → Line Kaleng 1',                          'Siti Rahma',      '08 Okt 2026', 'Persetujuan Manager', 2, 'run'],
        ['TRL-RES-2026-0044',   'Trial Result',     'Life Cat Pouch Tuna & Chicken 85 g - Lulus Bersyarat',    'Budi Santoso',    '07 Okt 2026', 'Review R&D',       1, 'revisi'],
        ['TRL-RES-2026-0043',   'Trial Result',     'Life Cat Kaleng Tuna 400 g - Hasil Lulus',                'Budi Santoso',    '05 Okt 2026', 'Selesai',          0, 'ok'],
        ['ST-2026-0080',        'Stock Transfer',   'Gudang Bahan Kering → Line Pouch 1',                      'Joko Susilo',     '06 Okt 2026', 'Selesai',          0, 'fail'],
        ['PP-2026-0013',        'Production Plan',  'Life Cat Kaleng Tuna 400 g - Trial formula baru',         'Admin Produksi',  '05 Okt 2026', 'Selesai',          0, 'ok'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>Approval</h2>
        <p>Dokumen yang membutuhkan persetujuan: Production Plan, Trial Result, dan Stock Transfer</p>
    </div>
    <div class="page-head__actions">
        <button type="button" class="btn btn--light"><i data-lucide="download" aria-hidden="true"></i><span>Export</span></button>
        <button type="button" class="btn btn--light"><i data-lucide="refresh-cw" aria-hidden="true"></i><span>Refresh</span></button>
    </div>
</section>

{{-- Ringkasan --}}
<section class="kpi-grid" aria-label="Ringkasan approval">
    <article class="kpi">
        <span class="kpi__icon kpi__icon--amber"><i data-lucide="inbox" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Menunggu Saya</p>
            <p class="kpi__value">6</p>
            <p class="kpi__trend kpi__trend--warn"><i data-lucide="clock" aria-hidden="true"></i>Perlu ditinjau</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--blue"><i data-lucide="users" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Menunggu Orang Lain</p>
            <p class="kpi__value">4</p>
            <p class="kpi__trend">Sedang direview</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--green"><i data-lucide="check-circle-2" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Disetujui Bulan Ini</p>
            <p class="kpi__value">23</p>
            <p class="kpi__trend kpi__trend--up"><i data-lucide="trending-up" aria-hidden="true"></i>+5 dari bulan lalu</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--indigo"><i data-lucide="x-circle" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Ditolak Bulan Ini</p>
            <p class="kpi__value">3</p>
            <p class="kpi__trend">Perlu tindak lanjut</p>
        </div>
    </article>
</section>

{{-- Daftar approval --}}
<section class="panel panel--fit">

    <div class="toolbar">
        <div class="tabs" role="tablist" aria-label="Filter status">
            <button type="button" class="tabs__btn is-active" data-filter="all">Semua</button>
            <button type="button" class="tabs__btn" data-filter="wait">Menunggu</button>
            <button type="button" class="tabs__btn" data-filter="run">Sedang Direview</button>
            <button type="button" class="tabs__btn" data-filter="ok">Disetujui</button>
            <button type="button" class="tabs__btn" data-filter="fail">Ditolak</button>
            <button type="button" class="tabs__btn" data-filter="revisi">Perlu Revisi</button>
        </div>

        <div class="toolbar__right">
            <label class="search">
                <i data-lucide="search" aria-hidden="true"></i>
                <input type="search" id="planSearch" placeholder="Cari no. dokumen, pengaju, atau ringkasan..." aria-label="Cari approval">
            </label>
            <select class="select" aria-label="Filter jenis dokumen">
                <option>Semua Jenis</option>
                <option>Production Plan</option>
                <option>Trial Result</option>
                <option>Stock Transfer</option>
            </select>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table" id="planTable">
            <thead>
                <tr>
                    <th>No. Dokumen</th>
                    <th>Jenis</th>
                    <th>Ringkasan</th>
                    <th>Diajukan Oleh</th>
                    <th>Tanggal</th>
                    <th>Tahap Approval</th>
                    <th class="table__right">Menunggu</th>
                    <th>Status</th>
                    <th class="table__right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($approvals as $a)
                    @php
                        [$label, $badge] = $statuses[$a[7]];
                        $waitClass = $a[6] > 2 ? 'is-over' : '';
                    @endphp
                    <tr data-status="{{ $a[7] }}" data-search="{{ strtolower($a[0].' '.$a[1].' '.$a[2].' '.$a[3]) }}">
                        <td class="table__code"><a href="{{ route('approval.show', $a[0]) }}">{{ $a[0] }}</a></td>
                        <td><span class="badge badge--{{ $a[1] === 'Production Plan' ? 'draft' : ($a[1] === 'Trial Result' ? 'done' : 'run') }}">{{ $a[1] }}</span></td>
                        <td><span class="cell-title">{{ $a[2] }}</span></td>
                        <td>{{ $a[3] }}</td>
                        <td>{{ $a[4] }}</td>
                        <td>{{ $a[5] }}</td>
                        <td class="table__right {{ $waitClass }}">
                            {{ $a[6] === 0 ? 'Hari ini' : $a[6] . ' hari' }}
                        </td>
                        <td><span class="badge badge--{{ $badge }}">{{ $label }}</span></td>
                        <td>
                            <div class="table__actions">
                                <a href="{{ route('approval.show', $a[0]) }}" class="icon-btn icon-btn--sm" aria-label="Tinjau dokumen"><i data-lucide="eye" aria-hidden="true"></i></a>
                                <button type="button" class="icon-btn icon-btn--sm" aria-label="Lainnya"><i data-lucide="more-vertical" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="empty" id="planEmpty" hidden>
            <i data-lucide="search-x" aria-hidden="true"></i>
            <p>Tidak ada dokumen approval yang cocok.</p>
        </div>
    </div>

    <div class="panel__foot">
        <p>Menampilkan 1-10 dari 32 data</p>
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