@extends('layouts.app')

@section('page_title', 'Trial Report')
@section('breadcrumb_parent', 'Reports')

@section('content')

@php
    // DATA DUMMY - nanti diganti data dari database (Controller)

    // Rekap per tipe trial: [tipe, jumlah, pass rate %, reject rata %, durasi rata (hari)]
    $perType = [
        ['Trial Formula Baru',          14, 92.9, 2.8, 4.1],
        ['Trial Supplier Bahan Baku Baru', 8, 87.5, 3.4, 3.5],
        ['Trial Parameter Retort',      6,  83.3, 4.1, 3.8],
        ['Trial Kemasan Baru',          7,  100.0, 1.6, 2.9],
        ['Trial Varian Rasa Baru',      5,  80.0, 4.2, 4.2],
    ];

    // [no trial, produk, tipe, line, tanggal, durasi hari, yield %, reject %, hasil]
    $trials = [
        ['TRL-2026-0048', 'Life Cat Pouch Tuna & Chicken 85 g', 'Trial Formula Baru',            'Line Pouch 1',  '10 Okt 2026', 5, null, null, 'run'],
        ['TRL-2026-0047', 'Life Cat Pouch Mackerel 85 g',       'Trial Supplier Bahan Baku Baru','Line Pouch 2',  '12 Okt 2026', 0, null, null, 'draft'],
        ['TRL-2026-0046', 'Life Cat Kaleng Tuna 400 g',         'Trial Parameter Retort',        'Line Kaleng 1', '06 Okt 2026', 4, 98.1, 1.9, 'ok'],
        ['TRL-2026-0045', 'Life Cat Pouch Tuna & Chicken 85 g', 'Trial Formula Baru',            'Line Pouch 1',  '01 Okt 2026', 3, 89.5, 10.5, 'fail'],
        ['TRL-2026-0044', 'Life Cat Pouch Tuna & Chicken 85 g', 'Trial Parameter Sealing',       'Line Pouch 1',  '25 Sep 2026', 3, 96.4, 3.6, 'ok'],
        ['TRL-2026-0043', 'Life Cat Kaleng Tuna 400 g',         'Trial Supplier Bahan Baku Baru','Line Kaleng 1', '22 Sep 2026', 4, 98.1, 1.9, 'ok'],
        ['TRL-2026-0042', 'Life Cat Pouch Mackerel 85 g',       'Trial Varian Rasa Baru',        'Line Pouch 2',  '18 Sep 2026', 4, 97.0, 2.0, 'ok'],
        ['TRL-2026-0041', 'Life Cat Pouch Tuna & Chicken 85 g', 'Trial Kemasan Baru',            'Line Pouch 1',  '15 Sep 2026', 3, 99.1, 0.9, 'ok'],
        ['TRL-2026-0040', 'Life Cat Kaleng Tuna 400 g',         'Trial Parameter Retort',        'Line Kaleng 1', '10 Sep 2026', 5, 92.4, 7.6, 'ok'],
        ['TRL-2026-0039', 'Life Cat Pouch Mackerel 85 g',       'Trial Formula Baru',            'Line Pouch 2',  '05 Sep 2026', 4, 85.2, 14.8, 'fail'],
    ];

    // Top deviasi: [parameter, kejadian, tahap, penyebab utama, dampak]
    $deviations = [
        ['Suhu seal bar',        4, 'Sealing',   'Kalibrasi seal bar belum akurat',    'Kekuatan seal menurun'],
        ['Nilai F0',             3, 'Retort',    'Distribusi panas tidak merata',      'Sterilitas perlu dikaji ulang'],
        ['Berat isi per pouch',  2, 'Filling',   'Setelan filler tidak stabil',        'Penyimpangan berat isi'],
        ['Suhu pemasakan',       2, 'Pemasakan', 'Beban adonan melebihi kapasitas',    'Tekstur adonan tidak stabil'],
        ['Waktu thawing',        1, 'Thawing',   'Suhu ruang thawing naik',            'Kualitas bahan baku menurun'],
    ];

    $totalTrials  = 40;
    $passRate     = 90.0;
    $avgDuration  = 3.6;
    $trialHeld    = 2;
@endphp

<section class="page-head">
    <div>
        <h2>Trial Report</h2>
        <p>Analisa hasil trial periode Oktober 2026 &middot; semua tipe trial</p>
    </div>
    <div class="page-head__actions">
        <button type="button" class="btn btn--light"><i data-lucide="calendar" aria-hidden="true"></i><span>Oktober 2026</span></button>
        <button type="button" class="btn btn--light" id="reportExport"><i data-lucide="download" aria-hidden="true"></i><span>Export Excel</span></button>
        <button type="button" class="btn btn--primary" id="reportPrint"><i data-lucide="printer" aria-hidden="true"></i><span>Cetak PDF</span></button>
    </div>
</section>

{{-- KPI --}}
<section class="kpi-grid" aria-label="Indikator trial">
    <article class="kpi">
        <span class="kpi__icon kpi__icon--blue"><i data-lucide="flask-conical" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total Trial</p>
            <p class="kpi__value">{{ $totalTrials }}</p>
            <p class="kpi__trend kpi__trend--up"><i data-lucide="trending-up" aria-hidden="true"></i>+8 dari bulan lalu</p>
        </div>
    </article>

    <article class="kpi">
        <span class="kpi__icon kpi__icon--green"><i data-lucide="check-circle-2" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Pass Rate</p>
            <p class="kpi__value">{{ number_format($passRate, 1, ',', '.') }}%</p>
            <p class="kpi__trend kpi__trend--up"><i data-lucide="trending-up" aria-hidden="true"></i>+2,1% dari bulan lalu</p>
        </div>
    </article>

    <article class="kpi">
        <span class="kpi__icon kpi__icon--indigo"><i data-lucide="calendar-clock" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Durasi Rata-rata</p>
            <p class="kpi__value">{{ number_format($avgDuration, 1, ',', '.') }} hari</p>
            <p class="kpi__trend">Per trial</p>
        </div>
    </article>

    <article class="kpi">
        <span class="kpi__icon kpi__icon--amber"><i data-lucide="pause-circle" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Trial Ditahan</p>
            <p class="kpi__value">{{ $trialHeld }}</p>
            <p class="kpi__trend kpi__trend--warn"><i data-lucide="clock" aria-hidden="true"></i>Perlu keputusan</p>
        </div>
    </article>
</section>

{{-- Grafik --}}
<section class="grid grid--chart">
    <article class="panel panel--fit">
        <div class="panel__head panel__head--row">
            <h3>Trial per Tipe</h3>
            <div class="legend">
                <span class="legend__item"><i class="dot dot--pass"></i>Lulus</span>
                <span class="legend__item"><i class="dot dot--fail"></i>Gagal</span>
            </div>
        </div>
        <div class="panel__body">
            <div class="bars" role="img" aria-label="Grafik batang jumlah trial per tipe">
                <div class="bars__col"><span class="bars__val">14</span><div class="bars__bar" style="--h:88%;--pass:13;--fail:1"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Formula</span></div>
                <div class="bars__col"><span class="bars__val">8</span><div class="bars__bar" style="--h:52%;--pass:7;--fail:1"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Supplier</span></div>
                <div class="bars__col"><span class="bars__val">6</span><div class="bars__bar" style="--h:40%;--pass:5;--fail:1"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Retort</span></div>
                <div class="bars__col"><span class="bars__val">7</span><div class="bars__bar" style="--h:46%;--pass:7;--fail:0"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Kemasan</span></div>
                <div class="bars__col"><span class="bars__val">5</span><div class="bars__bar" style="--h:32%;--pass:4;--fail:1"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Varian</span></div>
            </div>
        </div>
    </article>

    <article class="panel panel--fit">
        <div class="panel__head"><h3>Distribusi Hasil Trial</h3></div>
        <div class="panel__body donut-wrap">
            <div class="donut" role="img" aria-label="Distribusi hasil trial">
                <div class="donut__center"><strong>{{ $totalTrials }}</strong><span>Total Trial</span></div>
            </div>
            <ul class="status-list">
                <li><i class="dot dot--done"></i><span>Lulus</span><strong>30</strong></li>
                <li><i class="dot dot--wait"></i><span>Lulus Bersyarat</span><strong>6</strong></li>
                <li><i class="dot dot--fail"></i><span>Gagal</span><strong>4</strong></li>
            </ul>
        </div>
    </article>
</section>

{{-- Tabel rekap per tipe --}}
<section class="panel panel--fit" style="margin-bottom:20px">
    <div class="panel__head panel__head--row">
        <h3>Rekap per Tipe Trial</h3>
        <span class="hint-inline">Oktober 2026</span>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Tipe Trial</th>
                    <th class="table__right">Jumlah</th>
                    <th class="table__right">Pass Rate</th>
                    <th class="table__right">Reject Rata-rata</th>
                    <th class="table__right">Durasi Rata-rata</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($perType as $t)
                    <tr>
                        <td><span class="cell-title">{{ $t[0] }}</span></td>
                        <td class="table__right">{{ $t[1] }}</td>
                        <td class="table__right">
                            <span class="badge badge--{{ $t[2] >= 90 ? 'ok' : ($t[2] >= 80 ? 'wait' : 'fail') }}">{{ number_format($t[2], 1, ',', '.') }}%</span>
                        </td>
                        <td class="table__right {{ $t[3] > 2.5 ? 'is-over' : '' }}">{{ number_format($t[3], 1, ',', '.') }}%</td>
                        <td class="table__right">{{ number_format($t[4], 1, ',', '.') }} hari</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

{{-- Semua trial --}}
{{-- Semua trial --}}
<section class="panel panel--fit" style="margin-bottom:20px">

    <div class="panel__head panel__head--row">
        <h3>Semua Trial Periode Ini</h3>
        <span class="hint-inline">Menampilkan 10 dari 40 trial</span>
    </div>

    <div class="toolbar">
        <div class="tabs" role="tablist" aria-label="Filter hasil">
            <button type="button" class="tabs__btn is-active" data-tr-filter="all">Semua</button>
            <button type="button" class="tabs__btn" data-tr-filter="ok">Lulus</button>
            <button type="button" class="tabs__btn" data-tr-filter="fail">Gagal</button>
            <button type="button" class="tabs__btn" data-tr-filter="run">Berjalan</button>
            <button type="button" class="tabs__btn" data-tr-filter="draft">Terjadwal</button>
        </div>

        <div class="toolbar__right">
            <label class="search">
                <i data-lucide="search" aria-hidden="true"></i>
                <input type="search" id="trialReportSearch" placeholder="Cari no. trial, produk, atau tipe..." aria-label="Cari trial">
            </label>
            <button type="button" class="btn btn--light" id="trialReportExportPdf">
                <i data-lucide="file-text" aria-hidden="true"></i><span>Export PDF</span>
            </button>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table" id="trialReportTable">
            <thead>
                <tr>
                    <th>No. Trial</th>
                    <th>Produk</th>
                    <th>Tipe</th>
                    <th>Line</th>
                    <th>Tanggal</th>
                    <th class="table__right">Durasi</th>
                    <th class="table__right">Yield</th>
                    <th class="table__right">Reject</th>
                    <th>Hasil</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trials as $t)
                    @php
                        $resultMap = [
                            'ok'    => ['Lulus',        'ok'],
                            'fail'  => ['Gagal',        'fail'],
                            'run'   => ['Berjalan',     'run'],
                            'draft' => ['Terjadwal',    'draft'],
                        ];
                        [$rLabel, $rBadge] = $resultMap[$t[8]];
                    @endphp
                    <tr data-tr-status="{{ $t[8] }}"
                        data-tr-search="{{ strtolower($t[0].' '.$t[1].' '.$t[2].' '.$t[3]) }}">
                        <td class="table__code"><a href="{{ route('production-trial.show', $t[0]) }}">{{ $t[0] }}</a></td>
                        <td><span class="cell-title">{{ $t[1] }}</span></td>
                        <td>{{ $t[2] }}</td>
                        <td>{{ $t[3] }}</td>
                        <td>{{ $t[4] }}</td>
                        <td class="table__right">{{ $t[5] }} hari</td>
                        <td class="table__right">{{ $t[6] !== null ? number_format($t[6], 1, ',', '.') . '%' : '-' }}</td>
                        <td class="table__right {{ $t[7] !== null && $t[7] > 2.5 ? 'is-over' : '' }}">{{ $t[7] !== null ? number_format($t[7], 1, ',', '.') . '%' : '-' }}</td>
                        <td><span class="badge badge--{{ $rBadge }}">{{ $rLabel }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="empty" id="trialReportEmpty" hidden>
            <i data-lucide="search-x" aria-hidden="true"></i>
            <p>Tidak ada trial yang cocok.</p>
        </div>
    </div>

</section>

{{-- Top deviasi + ringkasan --}}
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
                        <th>Penyebab Utama</th>
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
                            <td>{{ $d[4] }}</td>
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
                    <strong>Pass rate naik 2,1%</strong>
                    <small>Dibanding bulan lalu (87,9% → 90,0%). Didorong oleh Trial Kemasan Baru yang 100% lulus.</small>
                </div>
            </li>
            <li class="todo__item">
                <span class="todo__icon todo__icon--warn"><i data-lucide="alert-triangle" aria-hidden="true"></i></span>
                <div>
                    <strong>Trial Varian Rasa Baru pass rate terendah (80%)</strong>
                    <small>Butuh kajian lanjutan: 1 trial gagal karena parameter retort belum optimal.</small>
                </div>
            </li>
            <li class="todo__item">
                <span class="todo__icon todo__icon--warn"><i data-lucide="clock" aria-hidden="true"></i></span>
                <div>
                    <strong>Suhu seal bar paling sering deviasi (4x)</strong>
                    <small>Perlu kalibrasi rutin seal bar di Line Pouch 1.</small>
                </div>
            </li>
            <li class="todo__item">
                <span class="todo__icon todo__icon--info"><i data-lucide="calendar-clock" aria-hidden="true"></i></span>
                <div>
                    <strong>Durasi rata-rata 3,6 hari</strong>
                    <small>Turun 0,4 hari dari bulan lalu. Efisiensi pelaksanaan trial membaik.</small>
                </div>
            </li>
        </ul>
    </article>
</section>

@endsection