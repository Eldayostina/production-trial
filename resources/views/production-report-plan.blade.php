@extends('layouts.app')

@section('page_title', 'Production Report - Per Plan')
@section('breadcrumb_parent', 'Production Report')

@section('content')

@php
    // DATA DUMMY - nomor plan di URL belum dipakai untuk query.

    // Info plan
    $plan = [
        'no'      => $no,
        'produk'  => 'Life Cat Pouch Tuna & Chicken 85 g',
        'kode'    => 'FG-LC-P01',
        'tipe'    => 'Trial Formula Baru',
        'line'    => 'Line Pouch 1',
        'shift'   => 'Shift 1 (07.00 - 15.00)',
        'target'  => '5.000 pouch',
        'jadwal'  => '10 Okt - 14 Okt 2026',
        'pic'     => 'Budi Santoso',
        'bom'     => 'BOM-LC-P01 Rev. 02',
        'status'  => 'Berjalan',
        'status_badge' => 'run',
    ];

    // Trial terkait plan ini: [no, mulai, status, badge, yield, reject]
    $trials = [
        ['TRL-2026-0048', '10 Okt 2026', 'Berjalan',  'run',   null,  null],
        ['TRL-2026-0045', '01 Okt 2026', 'Ditahan',   'fail',  null,  null],
    ];

    // Material terpakai: [kode, nama, satuan, rencana, aktual, selisih %]
    $materials = [
        ['RM-1001', 'Daging Tuna Cooked Flake', 'kg',  150.0, 151.2, 0.8],
        ['RM-1005', 'Daging Ayam Giling',       'kg',  100.0, 99.5,  -0.5],
        ['RM-1010', 'Hati Ayam',                'kg',  40.0,  40.0,  0.0],
        ['RM-2001', 'Tepung Tapioka',           'kg',  15.0,  15.3,  2.0],
        ['RM-2010', 'Guar Gum',                 'kg',  4.0,   4.0,   0.0],
        ['RM-2020', 'Taurine',                  'kg',  0.5,   0.5,   0.0],
        ['RM-2021', 'Premix Vitamin & Mineral', 'kg',  3.0,   3.1,   3.3],
        ['PK-3001', 'Retort Pouch 85 g',        'pcs', 5250,  5190,  -1.1],
    ];

    // Deviasi parameter: [tahap, parameter, nilai, batas, tindakan]
    $deviations = [
        ['Sealing', 'Suhu seal bar', '192 °C', '170 - 190 °C', 'Diturunkan ke 182 °C, 40 pouch diseleksi ulang'],
    ];

    // Dokumen terkait
    $relatedDocs = [
        ['Stock Transfer',  'ST-2026-0087', 'stock-transfer.show',  'sent'],
        ['Stock Transfer',  'ST-2026-0086', 'stock-transfer.show',  'received'],
        ['Quality Insp.',   'QI-2026-0123', 'quality-inspection.show', 'run'],
        ['Quality Insp.',   'QI-2026-0122', 'quality-inspection.show', 'ok'],
    ];

    // KPI ringkas
    $totalTrial  = count($trials);
    $totalOutput = 5000;
    $yieldAvg    = 96.4;
    $rejectAvg   = 3.6;
@endphp

<section class="page-head">
    <div>
        <h2>Laporan Plan {{ $plan['no'] }} <span class="badge badge--{{ $plan['status_badge'] }}">{{ $plan['status'] }}</span></h2>
        <p>{{ $plan['produk'] }} &middot; {{ $plan['line'] }} &middot; {{ $plan['jadwal'] }}</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('production-report') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali ke Rekap</span></a>
        <a href="{{ route('production-plan') }}" class="btn btn--light"><i data-lucide="clipboard-list" aria-hidden="true"></i><span>Lihat Plan</span></a>
        <button type="button" class="btn btn--primary" id="reportPrint"><i data-lucide="printer" aria-hidden="true"></i><span>Cetak PDF</span></button>
    </div>
</section>

<div id="reportPage">

    {{-- KPI plan --}}
    <section class="kpi-grid" aria-label="Indikator plan">
        <article class="kpi">
            <span class="kpi__icon kpi__icon--blue"><i data-lucide="flask-conical" aria-hidden="true"></i></span>
            <div class="kpi__body">
                <p class="kpi__label">Trial Terkait Plan</p>
                <p class="kpi__value">{{ $totalTrial }}</p>
                <p class="kpi__trend">Termasuk trial ulang</p>
            </div>
        </article>

        <article class="kpi">
            <span class="kpi__icon kpi__icon--indigo"><i data-lucide="package" aria-hidden="true"></i></span>
            <div class="kpi__body">
                <p class="kpi__label">Target Output</p>
                <p class="kpi__value">{{ number_format($totalOutput, 0, ',', '.') }}</p>
                <p class="kpi__trend">pouch</p>
            </div>
        </article>

        <article class="kpi">
            <span class="kpi__icon kpi__icon--green"><i data-lucide="trending-up" aria-hidden="true"></i></span>
            <div class="kpi__body">
                <p class="kpi__label">Yield Rata-rata</p>
                <p class="kpi__value">{{ number_format($yieldAvg, 1, ',', '.') }}%</p>
                <p class="kpi__trend kpi__trend--up">Target ≥ 95%</p>
            </div>
        </article>

        <article class="kpi">
            <span class="kpi__icon kpi__icon--amber"><i data-lucide="alert-triangle" aria-hidden="true"></i></span>
            <div class="kpi__body">
                <p class="kpi__label">Reject Rata-rata</p>
                <p class="kpi__value">{{ number_format($rejectAvg, 1, ',', '.') }}%</p>
                <p class="kpi__trend kpi__trend--warn"><i data-lucide="clock" aria-hidden="true"></i>Target ≤ 2,5%</p>
            </div>
        </article>
    </section>

    {{-- Info plan --}}
    <section class="panel panel--fit">
        <div class="panel__head panel__head--row">
            <h3>Informasi Plan</h3>
            <a href="{{ route('production-plan') }}" class="link">Lihat Production Plan &rarr;</a>
        </div>
        <dl class="info-grid">
            <div><dt>No. Plan</dt><dd class="table__code">{{ $plan['no'] }}</dd></div>
            <div><dt>Status</dt><dd><span class="badge badge--{{ $plan['status_badge'] }}">{{ $plan['status'] }}</span></dd></div>
            <div><dt>Produk</dt><dd>{{ $plan['produk'] }}</dd></div>
            <div><dt>Kode Produk</dt><dd class="table__code">{{ $plan['kode'] }}</dd></div>
            <div><dt>Tipe Trial</dt><dd>{{ $plan['tipe'] }}</dd></div>
            <div><dt>Versi BOM</dt><dd>{{ $plan['bom'] }}</dd></div>
            <div><dt>Line / Shift</dt><dd>{{ $plan['line'] }} &middot; {{ $plan['shift'] }}</dd></div>
            <div><dt>Target</dt><dd>{{ $plan['target'] }}</dd></div>
            <div><dt>Jadwal</dt><dd>{{ $plan['jadwal'] }}</dd></div>
            <div><dt>PIC</dt><dd>{{ $plan['pic'] }}</dd></div>
        </dl>
    </section>

    <div class="form-layout">
        <div class="form-main">

            {{-- Trial terkait --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Trial Terkait Plan Ini</h3>
                    <span class="hint-inline">Semua trial di bawah plan {{ $plan['no'] }}</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Trial</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th class="table__right">Yield</th>
                                <th class="table__right">Reject</th>
                                <th class="table__right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trials as $t)
                                <tr>
                                    <td class="table__code"><a href="{{ route('production-trial.show', $t[0]) }}">{{ $t[0] }}</a></td>
                                    <td>{{ $t[1] }}</td>
                                    <td><span class="badge badge--{{ $t[3] }}">{{ $t[2] }}</span></td>
                                    <td class="table__right">{{ $t[4] !== null ? number_format($t[4], 1, ',', '.') . '%' : '-' }}</td>
                                    <td class="table__right">{{ $t[5] !== null ? number_format($t[5], 1, ',', '.') . '%' : '-' }}</td>
                                    <td class="table__right">
                                        <div class="table__actions">
                                            <a href="{{ route('production-trial.show', $t[0]) }}" class="icon-btn icon-btn--sm" aria-label="Buka trial"><i data-lucide="eye" aria-hidden="true"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Material terpakai --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Material Terpakai vs Rencana</h3>
                    <span class="hint-inline">Selisih &gt; 5% ditandai merah / kuning</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Material</th>
                                <th class="table__right">Rencana</th>
                                <th class="table__right">Aktual</th>
                                <th class="table__right">Selisih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($materials as $m)
                                @php
                                    $cls = abs($m[5]) > 5 ? ($m[5] > 0 ? 'is-over' : 'is-under') : '';
                                @endphp
                                <tr>
                                    <td class="table__code">{{ $m[0] }}</td>
                                    <td>{{ $m[1] }}</td>
                                    <td class="table__right">{{ number_format($m[3], 2, ',', '.') }} {{ $m[2] }}</td>
                                    <td class="table__right">{{ number_format($m[4], 2, ',', '.') }} {{ $m[2] }}</td>
                                    <td class="table__right {{ $cls }}">
                                        {{ ($m[5] > 0 ? '+' : '') . number_format($m[5], 1, ',', '.') }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Deviasi --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Deviasi Parameter Selama Plan Ini</h3>
                    <span class="badge badge--wait">{{ count($deviations) }} kejadian</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tahap</th>
                                <th>Parameter</th>
                                <th>Aktual</th>
                                <th>Batas</th>
                                <th>Tindakan Koreksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deviations as $d)
                                <tr>
                                    <td>{{ $d[0] }}</td>
                                    <td>{{ $d[1] }}</td>
                                    <td class="is-over">{{ $d[2] }}</td>
                                    <td>{{ $d[3] }}</td>
                                    <td>{{ $d[4] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Dokumen terkait --}}
            <section class="panel panel--fit">
                <div class="panel__head panel--head--row">
                    <h3>Dokumen Terkait Plan</h3>
                    <span class="hint-inline">Stock Transfer &amp; Quality Inspection</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>No. Dokumen</th>
                                <th>Status</th>
                                <th class="table__right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($relatedDocs as $d)
                                <tr>
                                    <td>{{ $d[0] }}</td>
                                    <td class="table__code"><a href="{{ route($d[2], $d[1]) }}">{{ $d[1] }}</a></td>
                                    <td><span class="badge badge--{{ $d[3] }}">{{ $d[3] === 'ok' ? 'Lulus' : ($d[3] === 'run' ? 'Sedang Diuji' : ($d[3] === 'sent' ? 'Dikirim' : 'Diterima')) }}</span></td>
                                    <td class="table__right">
                                        <div class="table__actions">
                                            <a href="{{ route($d[2], $d[1]) }}" class="icon-btn icon-btn--sm" aria-label="Buka dokumen"><i data-lucide="eye" aria-hidden="true"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        {{-- Kolom kanan --}}
        <aside class="form-side">
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Ringkasan</h3></div>
                <dl class="summary">
                    <div><dt>No. Plan</dt><dd>{{ $plan['no'] }}</dd></div>
                    <div><dt>Produk</dt><dd>{{ $plan['kode'] }}</dd></div>
                    <div><dt>Line</dt><dd>{{ $plan['line'] }}</dd></div>
                    <div><dt>Target</dt><dd>{{ $plan['target'] }}</dd></div>
                    <div><dt>Jumlah Trial</dt><dd>{{ $totalTrial }}</dd></div>
                    <div><dt>Yield Rata-rata</dt><dd>{{ number_format($yieldAvg, 1, ',', '.') }}%</dd></div>
                    <div><dt>Reject Rata-rata</dt><dd>{{ number_format($rejectAvg, 1, ',', '.') }}%</dd></div>
                    <div><dt>Status</dt><dd><span class="badge badge--{{ $plan['status_badge'] }}">{{ $plan['status'] }}</span></dd></div>
                </dl>
            </section>

            <section class="panel panel--fit">
                <div class="panel__head"><h3>Alur Plan</h3></div>
                <ol class="flow">
                    <li class="flow__item"><span class="flow__dot"><i data-lucide="check" aria-hidden="true"></i></span>
                        <div><strong>Plan dibuat</strong><small>Admin Produksi</small></div>
                    </li>
                    <li class="flow__item"><span class="flow__dot"><i data-lucide="check" aria-hidden="true"></i></span>
                        <div><strong>Disetujui</strong><small>QC &amp; Manager</small></div>
                    </li>
                    <li class="flow__item"><span class="flow__dot"><i data-lucide="check" aria-hidden="true"></i></span>
                        <div><strong>Stock Transfer</strong><small>Material ke area produksi</small></div>
                    </li>
                    <li class="flow__item is-current"><span class="flow__dot">4</span>
                        <div><strong>Trial berjalan</strong><small>2 trial terkait</small></div>
                    </li>
                    <li class="flow__item"><span class="flow__dot">5</span>
                        <div><strong>Trial Result</strong><small>Evaluasi &amp; keputusan scale-up</small></div>
                    </li>
                </ol>
            </section>
        </aside>
    </div>

</div>

@endsection