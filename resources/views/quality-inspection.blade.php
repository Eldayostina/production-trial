@extends('layouts.app')

@section('page_title', 'Quality Inspection')
@section('breadcrumb_parent', 'Quality')

@section('content')

@php
    // DATA DUMMY - nanti diganti data dari database (Controller)
    $types = [
        'incoming'  => ['Incoming',  'draft'],
        'inprocess' => ['In-Process','run'],
        'final'     => ['Final',     'done'],
        'release'   => ['Release',   'ok'],
    ];

    $results = [
        'wait' => ['Menunggu Hasil', 'wait'],
        'run'  => ['Sedang Diuji',   'run'],
        'ok'   => ['Lulus',          'ok'],
        'fail' => ['Gagal',          'fail'],
    ];

    // [no inspeksi, jenis, ref batch, material/produk, tahap, tanggal, inspektur, hasil]
    $inspections = [
        ['QI-2026-0125', 'incoming',  'LOT-241001',      'Daging Tuna Cooked Flake',          'Penerimaan Bahan Baku',  '10 Okt 2026', 'Dewi Anggraini',  'ok'],
        ['QI-2026-0124', 'incoming',  'LOT-241005',      'Daging Ayam Giling',                'Penerimaan Bahan Baku',  '10 Okt 2026', 'Dewi Anggraini',  'ok'],
        ['QI-2026-0123', 'inprocess', 'LCP01-261010-A',  'Life Cat Pouch Tuna & Chicken 85 g','Retort (Sterilisasi)',   '10 Okt 2026', 'Agus Setiawan',   'run'],
        ['QI-2026-0122', 'final',     'LCP01-261010-A',  'Life Cat Pouch Tuna & Chicken 85 g','Seal & Berat Isi',       '10 Okt 2026', 'Agus Setiawan',   'ok'],
        ['QI-2026-0121', 'release',   'LCK01-261006-A',  'Life Cat Kaleng Tuna 400 g',        'Inkubasi 7 Hari & Mikrobiologi','09 Okt 2026','Dewi Anggraini','wait'],
        ['QI-2026-0120', 'incoming',  'LOT-243201',      'Kaleng 401 Lacquer Putih',          'Penerimaan Kemasan',     '09 Okt 2026', 'Sari Handayani',  'ok'],
        ['QI-2026-0119', 'inprocess', 'LCM01-260918-A',  'Life Cat Pouch Mackerel 85 g',      'Filling & Sealing',      '08 Okt 2026', 'Agus Setiawan',   'ok'],
        ['QI-2026-0118', 'final',     'LCM01-260918-A',  'Life Cat Pouch Mackerel 85 g',      'Retort & Cooling',       '08 Okt 2026', 'Agus Setiawan',   'fail'],
        ['QI-2026-0117', 'release',   'LCP01-260925-A',  'Life Cat Pouch Tuna & Chicken 85 g','Inkubasi 7 Hari & Mikrobiologi','07 Okt 2026','Dewi Anggraini','ok'],
        ['QI-2026-0116', 'incoming',  'LOT-242001',      'Tepung Tapioka',                    'Penerimaan Bahan Kering', '07 Okt 2026','Sari Handayani',  'ok'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>Quality Inspection</h2>
        <p>Inspeksi kualitas bahan baku, proses, produk jadi, dan release batch</p>
    </div>
<div class="page-head__actions">
    <button type="button" class="btn btn--light"><i data-lucide="download" aria-hidden="true"></i><span>Export</span></button>
    <a href="{{ route('quality-inspection.create') }}" class="btn btn--primary"><i data-lucide="plus" aria-hidden="true"></i><span>Buat Inspeksi</span></a>
</div>
</section>

{{-- Ringkasan --}}
<section class="kpi-grid" aria-label="Ringkasan quality inspection">
    <article class="kpi">
        <span class="kpi__icon kpi__icon--blue"><i data-lucide="clipboard-check" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total Inspeksi Bulan Ini</p>
            <p class="kpi__value">125</p>
            <p class="kpi__trend">Oktober 2026</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--amber"><i data-lucide="hourglass" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Menunggu Hasil</p>
            <p class="kpi__value">8</p>
            <p class="kpi__trend kpi__trend--warn"><i data-lucide="clock" aria-hidden="true"></i>Dalam pengujian</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--green"><i data-lucide="check-circle-2" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Pass Rate</p>
            <p class="kpi__value">94,4%</p>
            <p class="kpi__trend kpi__trend--up"><i data-lucide="trending-up" aria-hidden="true"></i>+1,2% dari bulan lalu</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--indigo"><i data-lucide="alert-octagon" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Batch Ditahan</p>
            <p class="kpi__value">2</p>
            <p class="kpi__trend">Perlu keputusan</p>
        </div>
    </article>
</section>

{{-- Daftar inspeksi --}}
<section class="panel panel--fit">

    <div class="toolbar">
        <div class="tabs" role="tablist" aria-label="Filter jenis inspeksi">
            <button type="button" class="tabs__btn is-active" data-filter="all">Semua</button>
            <button type="button" class="tabs__btn" data-filter="incoming">Incoming</button>
            <button type="button" class="tabs__btn" data-filter="inprocess">In-Process</button>
            <button type="button" class="tabs__btn" data-filter="final">Final</button>
            <button type="button" class="tabs__btn" data-filter="release">Release</button>
        </div>

        <div class="toolbar__right">
            <label class="search">
                <i data-lucide="search" aria-hidden="true"></i>
                <input type="search" id="planSearch" placeholder="Cari no. inspeksi, batch, atau material..." aria-label="Cari inspeksi">
            </label>
            <select class="select" aria-label="Filter hasil">
                <option>Semua Hasil</option>
                <option>Lulus</option>
                <option>Menunggu Hasil</option>
                <option>Sedang Diuji</option>
                <option>Gagal</option>
            </select>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table" id="planTable">
            <thead>
                <tr>
                    <th>No. Inspeksi</th>
                    <th>Jenis</th>
                    <th>Ref. Batch</th>
                    <th>Material / Produk</th>
                    <th>Tahap</th>
                    <th>Tanggal</th>
                    <th>Inspektur</th>
                    <th>Hasil</th>
                    <th class="table__right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inspections as $i)
                    @php
                        [$tLabel, $tBadge] = $types[$i[1]];
                        [$rLabel, $rBadge] = $results[$i[7]];
                    @endphp
                    <tr data-status="{{ $i[1] }}" data-search="{{ strtolower($i[0].' '.$i[2].' '.$i[3].' '.$i[4]) }}">
                        <td class="table__code"><a href="{{ route('quality-inspection.show', $i[0]) }}">{{ $i[0] }}</a></td>
                        <td><span class="badge badge--{{ $tBadge }}">{{ $tLabel }}</span></td>
                        <td class="table__code">{{ $i[2] }}</td>
                        <td><span class="cell-title">{{ $i[3] }}</span></td>
                        <td>{{ $i[4] }}</td>
                        <td>{{ $i[5] }}</td>
                        <td>{{ $i[6] }}</td>
                        <td><span class="badge badge--{{ $rBadge }}">{{ $rLabel }}</span></td>
                        <td>
                            <div class="table__actions">
                                <a href="{{ route('quality-inspection.show', $i[0]) }}" class="icon-btn icon-btn--sm" aria-label="Buka inspeksi"><i data-lucide="eye" aria-hidden="true"></i></a>
                                <button type="button" class="icon-btn icon-btn--sm" aria-label="Lainnya"><i data-lucide="more-vertical" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="empty" id="planEmpty" hidden>
            <i data-lucide="search-x" aria-hidden="true"></i>
            <p>Tidak ada inspeksi yang cocok.</p>
        </div>
    </div>

    <div class="panel__foot">
        <p>Menampilkan 1-10 dari 125 data</p>
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