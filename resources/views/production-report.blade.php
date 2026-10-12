@extends('layouts.app')

@section('page_title', 'Production Report')
@section('breadcrumb_parent', 'Reports')

@section('content')

@php
    // DATA DUMMY - nanti diganti data dari database (Controller)

    // Rekap per produk: [kode, nama, jumlah trial, total output, yield %, reject %, pass %]
    $perProduct = [
        ['FG-LC-P01', 'Life Cat Pouch Tuna & Chicken 85 g', 18, '54.000 pouch', 96.4, 3.6, 88.9],
        ['FG-LC-M01', 'Life Cat Pouch Mackerel 85 g',       12, '36.000 pouch', 97.0, 3.0, 91.7],
        ['FG-LC-K01', 'Life Cat Kaleng Tuna 400 g',         10, '15.000 kaleng',98.1, 1.9, 90.0],
    ];

    // Rekap per line: [line, jumlah trial, total output, yield %, reject %]
    $perLine = [
        ['Line Pouch 1',  22, '66.000 pouch',  96.2, 3.8],
        ['Line Pouch 2',  11, '24.000 pouch',  97.1, 2.9],
        ['Line Kaleng 1', 7,  '15.000 kaleng', 98.1, 1.9],
    ];

    // Top deviasi: [parameter, jumlah kejadian, tahap, dampak]
    $deviations = [
        ['Suhu seal bar',        3, 'Sealing',   'Kekuatan seal menurun'],
        ['Berat isi per pouch',  2, 'Filling',   'Penyimpangan berat isi'],
        ['Nilai F0',             2, 'Retort',    'Sterilitas perlu dikaji ulang'],
        ['Suhu pemasakan',       1, 'Pemasakan', 'Tekstur adonan tidak stabil'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>Production Report</h2>
        <p>Rekap produksi &amp; trial periode Oktober 2026</p>
    </div>
    <div class="page-head__actions">
        <button type="button" class="btn btn--light"><i data-lucide="calendar" aria-hidden="true"></i><span>Oktober 2026</span></button>
        <button type="button" class="btn btn--light" id="reportExport"><i data-lucide="download" aria-hidden="true"></i><span>Export Excel</span></button>
        <button type="button" class="btn btn--primary" id="reportPrint"><i data-lucide="printer" aria-hidden="true"></i><span>Cetak PDF</span></button>
    </div>
</section>

{{-- KPI agregat --}}
<section class="kpi-grid" aria-label="Indikator utama">
    <article class="kpi">
        <span class="kpi__icon kpi__icon--blue"><i data-lucide="flask-conical" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total Trial</p>
            <p class="kpi__value">40</p>
            <p class="kpi__trend kpi__trend--up"><i data-lucide="trending-up" aria-hidden="true"></i>+8 dari bulan lalu</p>
        </div>
    </article>

    <article class="kpi">
        <span class="kpi__icon kpi__icon--green"><i data-lucide="check-circle-2" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Pass Rate</p>
            <p class="kpi__value">90,0%</p>
            <p class="kpi__trend kpi__trend--up"><i data-lucide="trending-up" aria-hidden="true"></i>+2,1% dari bulan lalu</p>
        </div>
    </article>

    <article class="kpi">
        <span class="kpi__icon kpi__icon--indigo"><i data-lucide="package" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total Output</p>
            <p class="kpi__value">105.000</p>
            <p class="kpi__trend">Pouch &amp; kaleng gabungan</p>
        </div>
    </article>

    <article class="kpi">
        <span class="kpi__icon kpi__icon--amber"><i data-lucide="alert-triangle" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Rata-rata Reject Rate</p>
            <p class="kpi__value">2,9%</p>
            <p class="kpi__trend kpi__trend--warn"><i data-lucide="clock" aria-hidden="true"></i>Target ≤ 2,5%</p>
        </div>
    </article>
</section>

{{-- Grafik --}}
<section class="grid grid--chart">
    <article class="panel panel--fit">
        <div class="panel__head panel__head--row">
            <h3>Trial per Bulan</h3>
            <div class="legend">
                <span class="legend__item"><i class="dot dot--pass"></i>Lulus</span>
                <span class="legend__item"><i class="dot dot--fail"></i>Gagal</span>
            </div>
        </div>
        <div class="panel__body">
            <div class="bars" role="img" aria-label="Grafik batang jumlah trial Mei sampai Oktober 2026">
                <div class="bars__col"><span class="bars__val">28</span><div class="bars__bar" style="--h:56%;--pass:25;--fail:3"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Mei</span></div>
                <div class="bars__col"><span class="bars__val">34</span><div class="bars__bar" style="--h:68%;--pass:30;--fail:4"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Jun</span></div>
                <div class="bars__col"><span class="bars__val">31</span><div class="bars__bar" style="--h:62%;--pass:28;--fail:3"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Jul</span></div>
                <div class="bars__col"><span class="bars__val">40</span><div class="bars__bar" style="--h:80%;--pass:36;--fail:4"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Agu</span></div>
                <div class="bars__col"><span class="bars__val">44</span><div class="bars__bar" style="--h:88%;--pass:40;--fail:4"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Sep</span></div>
                <div class="bars__col"><span class="bars__val">40</span><div class="bars__bar" style="--h:80%;--pass:36;--fail:4"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Okt</span></div>
            </div>
        </div>
    </article>

    <article class="panel panel--fit">
        <div class="panel__head"><h3>Distribusi Hasil</h3></div>
        <div class="panel__body donut-wrap">
            <div class="donut" role="img" aria-label="Distribusi hasil trial">
                <div class="donut__center"><strong>40</strong><span>Total Trial</span></div>
            </div>
            <ul class="status-list">
                <li><i class="dot dot--done"></i><span>Lulus</span><strong>30</strong></li>
                <li><i class="dot dot--wait"></i><span>Lulus Bersyarat</span><strong>6</strong></li>
                <li><i class="dot dot--fail"></i><span>Gagal</span><strong>4</strong></li>
            </ul>
        </div>
    </article>
</section>

{{-- Tabel rekap --}}
<section class="grid grid--table">
    <article class="panel panel--fit">
        <div class="panel__head panel__head--row">
            <h3>Rekap per Produk</h3>
            <span class="hint-inline">Oktober 2026</span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="table__right">Jumlah Trial</th>
                        <th class="table__right">Total Output</th>
                        <th class="table__right">Yield</th>
                        <th class="table__right">Reject</th>
                        <th class="table__right">Pass Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($perProduct as $p)
                        <tr>
                            <td>
                                <span class="cell-title">{{ $p[1] }}</span>
                                <small class="cell-sub">{{ $p[0] }}</small>
                            </td>
                            <td class="table__right">{{ $p[2] }}</td>
                            <td class="table__right">{{ $p[3] }}</td>
                            <td class="table__right">{{ number_format($p[4], 1, ',', '.') }}%</td>
                            <td class="table__right {{ $p[5] > 2.5 ? 'is-over' : '' }}">{{ number_format($p[5], 1, ',', '.') }}%</td>
                            <td class="table__right">
                                <span class="badge badge--{{ $p[6] >= 90 ? 'ok' : ($p[6] >= 80 ? 'wait' : 'fail') }}">{{ number_format($p[6], 1, ',', '.') }}%</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>

    <article class="panel panel--fit">
        <div class="panel__head panel__head--row">
            <h3>Rekap per Line</h3>
            <span class="hint-inline">Oktober 2026</span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Line</th>
                        <th class="table__right">Trial</th>
                        <th class="table__right">Output</th>
                        <th class="table__right">Yield</th>
                        <th class="table__right">Reject</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($perLine as $l)
                        <tr>
                            <td><span class="cell-title">{{ $l[0] }}</span></td>
                            <td class="table__right">{{ $l[1] }}</td>
                            <td class="table__right">{{ $l[2] }}</td>
                            <td class="table__right">{{ number_format($l[3], 1, ',', '.') }}%</td>
                            <td class="table__right {{ $l[4] > 2.5 ? 'is-over' : '' }}">{{ number_format($l[4], 1, ',', '.') }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>
</section>

{{-- Top deviasi & ringkasan trial --}}
<section class="grid grid--table">
    <article class="panel panel--fit">
        <div class="panel__head panel__head--row">
            <h3>Top Deviasi Parameter</h3>
            <span class="badge badge--wait">{{ count($deviations) }} jenis</span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Parameter</th>
                        <th class="table__right">Kejadian</th>
                        <th>Tahap</th>
                        <th>Dampak</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($deviations as $d)
                        <tr>
                            <td>{{ $d[0] }}</td>
                            <td class="table__right {{ $d[1] >= 3 ? 'is-over' : '' }}">{{ $d[1] }}x</td>
                            <td>{{ $d[2] }}</td>
                            <td>{{ $d[3] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>

    <article class="panel panel--fit">
        <div class="panel__head panel__head--row">
            <h3>Ringkasan Eksekutif</h3>
        </div>
        <ul class="todo">
            <li class="todo__item">
                <span class="todo__icon todo__icon--info"><i data-lucide="trending-up" aria-hidden="true"></i></span>
                <div>
                    <strong>Pass Rate naik 2,1%</strong>
                    <small>Dibanding bulan lalu (87,9% → 90,0%). Faktor: perbaikan parameter retort.</small>
                </div>
            </li>
            <li class="todo__item">
                <span class="todo__icon todo__icon--warn"><i data-lucide="alert-triangle" aria-hidden="true"></i></span>
                <div>
                    <strong>Reject rate di atas target (2,9% vs 2,5%)</strong>
                    <small>Penyebab utama: suhu seal bar (3 kejadian). Perlu kalibrasi seal bar.</small>
                </div>
            </li>
            <li class="todo__item">
                <span class="todo__icon todo__icon--info"><i data-lucide="flask-conical" aria-hidden="true"></i></span>
                <div>
                    <strong>Line Pouch 2 paling stabil</strong>
                    <small>Yield 97,1%, reject 2,9%. Bisa jadi acuan best practice line lain.</small>
                </div>
            </li>
            <li class="todo__item">
                <span class="todo__icon todo__icon--warn"><i data-lucide="clock" aria-hidden="true"></i></span>
                <div>
                    <strong>Nilai F0 deviasi 2x</strong>
                    <small>Perlu kajian ulang spesifikasi retort bersama R&amp;D.</small>
                </div>
            </li>
        </ul>
    </article>
</section>
{{-- Shortcut lihat per plan --}}
<section class="panel panel--fit" style="margin-bottom:20px">
    <div class="panel__head panel__head--row">
        <h3>Lihat Laporan Per Plan</h3>
        <span class="hint-inline">Klik plan untuk lihat laporan detail</span>
    </div>
    <div class="panel__body" style="display:flex;flex-wrap:wrap;gap:8px">
        <a href="{{ route('production-report.plan', 'PP-2026-0018') }}" class="btn btn--light">
            <i data-lucide="clipboard-list" aria-hidden="true"></i>
            <span>PP-2026-0018 &middot; Pouch Tuna 85 g</span>
        </a>
        <a href="{{ route('production-report.plan', 'PP-2026-0017') }}" class="btn btn--light">
            <i data-lucide="clipboard-list" aria-hidden="true"></i>
            <span>PP-2026-0017 &middot; Pouch Mackerel 85 g</span>
        </a>
        <a href="{{ route('production-report.plan', 'PP-2026-0016') }}" class="btn btn--light">
            <i data-lucide="clipboard-list" aria-hidden="true"></i>
            <span>PP-2026-0016 &middot; Kaleng Tuna 400 g</span>
        </a>
        <a href="{{ route('production-report.plan', 'PP-2026-0015') }}" class="btn btn--light">
            <i data-lucide="clipboard-list" aria-hidden="true"></i>
            <span>PP-2026-0015 &middot; Pouch Tuna 85 g</span>
        </a>
    </div>
</section>
@endsection