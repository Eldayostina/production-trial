@extends('layouts.app')

@section('page_title', 'Trial Result')
@section('breadcrumb_parent', 'Production')

@section('content')

@php
    // DATA DUMMY - nanti diganti data dari database (Controller)
    $statuses = [
        'review'   => ['Dalam Review', 'run'],
        'approval' => ['Menunggu Approval', 'wait'],
        'closed'   => ['Selesai', 'ok'],
    ];

    $outcomes = [
        'pass'    => ['Lulus', 'ok'],
        'cond'    => ['Lulus Bersyarat', 'wait'],
        'fail'    => ['Gagal', 'fail'],
        'pending' => ['Belum ada', 'draft'],
    ];

    // [no result, ref trial, produk, no batch, yield, reject rate, hasil, status]
    $results = [
        ['TRL-RES-2026-0044', 'TRL-2026-0044', 'Life Cat Pouch Tuna & Chicken 85 g', 'LCP01-260925-A', 96.4, 3.6,  'cond',    'review'],
        ['TRL-RES-2026-0043', 'TRL-2026-0043', 'Life Cat Kaleng Tuna 400 g',         'LCK01-260922-A', 98.1, 1.9,  'pass',    'closed'],
        ['TRL-RES-2026-0042', 'TRL-2026-0042', 'Life Cat Pouch Mackerel 85 g',       'LCM01-260918-A', 97.0, 2.0,  'pass',    'approval'],
        ['TRL-RES-2026-0041', 'TRL-2026-0041', 'Life Cat Pouch Tuna & Chicken 85 g', 'LCP01-260915-A', 89.5, 10.5, 'fail',    'closed'],
        ['TRL-RES-2026-0040', 'TRL-2026-0040', 'Life Cat Kaleng Tuna 400 g',         'LCK01-260910-A', null, null, 'pending', 'review'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>Trial Result</h2>
        <p>Hasil akhir trial, review QC &amp; R&amp;D, dan keputusan tindak lanjut</p>
    </div>
    <div class="page-head__actions">
        <button type="button" class="btn btn--light"><i data-lucide="download" aria-hidden="true"></i><span>Export</span></button>
    </div>
</section>

<section class="kpi-grid" aria-label="Ringkasan trial result">
    <article class="kpi">
        <span class="kpi__icon kpi__icon--blue"><i data-lucide="clipboard-check" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total Result</p>
            <p class="kpi__value">44</p>
            <p class="kpi__trend">Oktober 2026</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--amber"><i data-lucide="hourglass" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Dalam Review</p>
            <p class="kpi__value">5</p>
            <p class="kpi__trend kpi__trend--warn"><i data-lucide="clock" aria-hidden="true"></i>Menunggu QC / R&amp;D</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--green"><i data-lucide="check-circle-2" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Pass Rate</p>
            <p class="kpi__value">87,5%</p>
            <p class="kpi__trend kpi__trend--up"><i data-lucide="trending-up" aria-hidden="true"></i>+2,1% dari bulan lalu</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--indigo"><i data-lucide="rocket" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Siap Scale-up</p>
            <p class="kpi__value">9</p>
            <p class="kpi__trend">Sudah disetujui Manager</p>
        </div>
    </article>
</section>

<section class="panel panel--fit">

    <div class="toolbar">
        <div class="tabs" role="tablist" aria-label="Filter status">
            <button type="button" class="tabs__btn is-active" data-filter="all">Semua</button>
            <button type="button" class="tabs__btn" data-filter="review">Dalam Review</button>
            <button type="button" class="tabs__btn" data-filter="approval">Menunggu Approval</button>
            <button type="button" class="tabs__btn" data-filter="closed">Selesai</button>
        </div>

        <div class="toolbar__right">
            <label class="search">
                <i data-lucide="search" aria-hidden="true"></i>
                <input type="search" id="planSearch" placeholder="Cari no. result, batch, atau produk..." aria-label="Cari trial result">
            </label>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table" id="planTable">
            <thead>
                <tr>
                    <th>No. Result</th>
                    <th>Produk</th>
                    <th>No. Batch</th>
                    <th>Ref. Trial</th>
                    <th class="table__right">Yield</th>
                    <th class="table__right">Reject</th>
                    <th>Hasil</th>
                    <th>Status</th>
                    <th class="table__right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results as $r)
                    @php
                        [$stLabel, $stBadge] = $statuses[$r[7]];
                        [$ocLabel, $ocBadge] = $outcomes[$r[6]];
                    @endphp
                    <tr data-status="{{ $r[7] }}" data-search="{{ strtolower($r[0].' '.$r[1].' '.$r[2].' '.$r[3]) }}">
                        <td class="table__code"><a href="{{ route('trial-result.show', $r[0]) }}">{{ $r[0] }}</a></td>
                        <td><span class="cell-title">{{ $r[2] }}</span></td>
                        <td>{{ $r[3] }}</td>
                        <td>{{ $r[1] }}</td>
                        <td class="table__right">{{ $r[4] !== null ? number_format($r[4], 1, ',', '.') . '%' : '-' }}</td>
                        <td class="table__right">{{ $r[5] !== null ? number_format($r[5], 1, ',', '.') . '%' : '-' }}</td>
                        <td><span class="badge badge--{{ $ocBadge }}">{{ $ocLabel }}</span></td>
                        <td><span class="badge badge--{{ $stBadge }}">{{ $stLabel }}</span></td>
                        <td>
                            <div class="table__actions">
                                <a href="{{ route('trial-result.show', $r[0]) }}" class="icon-btn icon-btn--sm" aria-label="Buka result"><i data-lucide="eye" aria-hidden="true"></i></a>
                                <button type="button" class="icon-btn icon-btn--sm" aria-label="Lainnya"><i data-lucide="more-vertical" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="empty" id="planEmpty" hidden>
            <i data-lucide="search-x" aria-hidden="true"></i>
            <p>Tidak ada trial result yang cocok.</p>
        </div>
    </div>

    <div class="panel__foot">
        <p>Menampilkan 1-5 dari 44 data</p>
        <nav class="pagination" aria-label="Halaman">
            <button type="button" class="pagination__btn" disabled aria-label="Sebelumnya"><i data-lucide="chevron-left" aria-hidden="true"></i></button>
            <button type="button" class="pagination__btn is-active" aria-current="page">1</button>
            <button type="button" class="pagination__btn">2</button>
            <button type="button" class="pagination__btn" aria-label="Berikutnya"><i data-lucide="chevron-right" aria-hidden="true"></i></button>
        </nav>
    </div>

</section>

@endsection