@extends('layouts.app')

@section('page_title', 'Detail Production Plan')
@section('breadcrumb_parent', 'Production Plan')

@section('content')

@php
    // DATA DUMMY - nomor plan di URL belum dipakai untuk query.
    // Nanti saat database: $plan = ProductionPlan::with([...])->where('no', $no)->firstOrFail();

    // Status workflow plan
    $stages = [
        ['Draft',              'done'],
        ['Review QC',          'done'],
        ['Review R&D',         'done'],
        ['Persetujuan Manager','current'],
        ['Disetujui',          'pending'],
        ['Stock Transfer',     'pending'],
    ];

    // Kebutuhan material dari BOM: [kode, nama, uom, per unit, kebutuhan, stok tersedia]
    $materials = [
        ['RM-1001', 'Daging Tuna Cooked Flake', 'kg',  0.030,  150.0, 120.0],
        ['RM-1005', 'Daging Ayam Giling',       'kg',  0.020,  100.0, 300.0],
        ['RM-1010', 'Hati Ayam',                'kg',  0.008,  40.0,  45.0],
        ['RM-2001', 'Tepung Tapioka',           'kg',  0.003,  15.0,  400.0],
        ['RM-2010', 'Guar Gum',                 'kg',  0.0008, 4.0,   6.0],
        ['RM-2020', 'Taurine',                  'kg',  0.0001, 0.5,   2.0],
        ['RM-2021', 'Premix Vitamin & Mineral', 'kg',  0.0006, 3.0,   8.0],
        ['PK-3001', 'Retort Pouch 85 g Tuna',   'pcs', 1,      5000,  60000],
    ];

    // Kriteria keberhasilan: [kriteria, target]
    $criteria = [
        ['Yield proses',              '≥ 95%'],
        ['Reject rate',               '≤ 2,5%'],
        ['Berat isi per pouch',       '85 g (±2 g)'],
        ['Kekuatan seal',             '≥ 25 N/15 mm'],
        ['Uji mikrobiologi',          'Steril komersial'],
        ['Uji inkubasi 7 hari',       'Tidak menggembung'],
    ];

    // Dokumen terkait
    $relatedDocs = [
        ['Stock Transfer',   'ST-2026-0087', 'stock-transfer.show',   'sent',     'Dikirim'],
        ['Stock Transfer',   'ST-2026-0086', 'stock-transfer.show',   'received', 'Diterima'],
        ['Quality Insp.',    'QI-2026-0123', 'quality-inspection.show','run',     'Sedang Diuji'],
        ['Quality Insp.',    'QI-2026-0122', 'quality-inspection.show','ok',      'Lulus'],
    ];

    // Riwayat approval
    $approval = [
        ['Admin Produksi',   'Dibuat',              '10 Okt 2026, 07.10', 'Dokumen plan dibuat'],
        ['Dewi Anggraini',   'Review QC disetujui', '10 Okt 2026, 09.20', 'Spesifikasi QC lengkap'],
        ['Rina Wulandari',   'Review R&D disetujui','10 Okt 2026, 11.05', 'Formula & BOM sesuai'],
        ['Manager Produksi', 'Menunggu persetujuan','—',                  'Sedang direview'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>{{ $no }} <span class="badge badge--run">Berjalan</span></h2>
        <p>Life Cat Pouch Tuna &amp; Chicken 85 g &middot; Line Pouch 1 &middot; 10 Okt - 14 Okt 2026</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('production-plan') }}" class="btn btn--light">
            <i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span>
        </a>
        <a href="{{ route('production-report.plan', $no) }}" class="btn btn--light">
            <i data-lucide="bar-chart-3" aria-hidden="true"></i><span>Lihat Laporan</span>
        </a>
        <button type="button" class="btn btn--light">
            <i data-lucide="printer" aria-hidden="true"></i><span>Cetak</span>
        </button>
    </div>
</section>

<div id="planPage">

    {{-- Info plan --}}
    <section class="panel panel--fit">
        <dl class="info-grid">
            <div><dt>No. Plan</dt><dd class="table__code">{{ $no }}</dd></div>
            <div><dt>Status</dt><dd><span class="badge badge--run">Berjalan</span></dd></div>
            <div><dt>Tipe Trial</dt><dd>Trial Formula Baru</dd></div>
            <div><dt>Prioritas</dt><dd>Normal</dd></div>
            <div><dt>Produk</dt><dd>Life Cat Pouch Tuna &amp; Chicken 85 g</dd></div>
            <div><dt>Kode Produk</dt><dd class="table__code">FG-LC-P01</dd></div>
            <div><dt>Versi BOM</dt><dd>BOM-LC-P01 Rev. 02</dd></div>
            <div><dt>Target</dt><dd>5.000 pouch</dd></div>
            <div><dt>Line / Shift</dt><dd>Line Pouch 1 &middot; Shift 1</dd></div>
            <div><dt>Jadwal</dt><dd>10 Okt - 14 Okt 2026</dd></div>
            <div><dt>Departemen Pemohon</dt><dd>Production</dd></div>
            <div><dt>PIC</dt><dd>Budi Santoso</dd></div>
            <div><dt>No. Referensi</dt><dd>ECN-2026-014</dd></div>
            <div><dt>Progress</dt><dd>35%</dd></div>
            <div><dt>Trial Terkait</dt><dd>2 trial</dd></div>
            <div><dt>Dibuat Pada</dt><dd>10 Okt 2026, 07.10</dd></div>
        </dl>
    </section>

    {{-- Stepper alur plan --}}
    <section class="panel panel--fit">
        <div class="panel__head"><h3>Alur Production Plan</h3></div>
        <ol class="stepper">
            @foreach ($stages as $i => $s)
                <li class="stepper__item is-{{ $s[1] }}">
                    <span class="stepper__dot">
                        @if ($s[1] === 'done') <i data-lucide="check" aria-hidden="true"></i> @else {{ $i + 1 }} @endif
                    </span>
                    <span class="stepper__label">{{ $s[0] }}</span>
                </li>
            @endforeach
        </ol>
    </section>

    <div class="form-layout">
        <div class="form-main">

            {{-- Kebutuhan material --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Kebutuhan Material (dari BOM)</h3>
                    <span class="hint-inline">Qty target × pemakaian per unit</span>
                </div>

                <div class="alert alert--warn" id="matAlert" hidden>
                    <i data-lucide="alert-triangle" aria-hidden="true"></i>
                    <p>Ada material yang stoknya kurang dari kebutuhan. Siapkan Stock Transfer sebelum trial dimulai.</p>
                </div>

                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Material</th>
                                <th class="table__right">Per Unit</th>
                                <th class="table__right">Kebutuhan</th>
                                <th class="table__right">Stok</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($materials as $m)
                                @php $enough = $m[5] >= $m[4]; @endphp
                                <tr>
                                    <td class="table__code">{{ $m[0] }}</td>
                                    <td>{{ $m[1] }}</td>
                                    <td class="table__right">{{ number_format($m[3], 4, ',', '.') }} {{ $m[2] }}</td>
                                    <td class="table__right">{{ number_format($m[4], 2, ',', '.') }} {{ $m[2] }}</td>
                                    <td class="table__right">{{ number_format($m[5], 2, ',', '.') }} {{ $m[2] }}</td>
                                    <td>
                                        <span class="badge badge--{{ $enough ? 'ok' : 'fail' }}">
                                            {{ $enough ? 'Cukup' : 'Kurang' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Kriteria keberhasilan --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Kriteria Keberhasilan</h3>
                    <span class="hint-inline">Acuan evaluasi Trial Result</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kriteria</th>
                                <th class="table__right">Target</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($criteria as $c)
                                <tr>
                                    <td>{{ $c[0] }}</td>
                                    <td class="table__right"><strong>{{ $c[1] }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Trial terkait --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Trial Terkait Plan Ini</h3>
                    <span class="hint-inline">Semua trial yang mengacu ke {{ $no }}</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Trial</th>
                                <th>Tanggal</th>
                                <th>Line</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th class="table__right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="table__code"><a href="{{ route('production-trial.show', 'TRL-2026-0048') }}">TRL-2026-0048</a></td>
                                <td>10 Okt 2026</td>
                                <td>Line Pouch 1</td>
                                <td>
                                    <div class="progress" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress__track"><div class="progress__fill" style="width:70%"></div></div>
                                        <span class="progress__text">70%</span>
                                    </div>
                                </td>
                                <td><span class="badge badge--run">Berjalan</span></td>
                                <td>
                                    <div class="table__actions">
                                        <a href="{{ route('production-trial.show', 'TRL-2026-0048') }}" class="icon-btn icon-btn--sm" aria-label="Buka trial"><i data-lucide="eye" aria-hidden="true"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="table__code"><a href="{{ route('production-trial.show', 'TRL-2026-0045') }}">TRL-2026-0045</a></td>
                                <td>01 Okt 2026</td>
                                <td>Line Pouch 1</td>
                                <td>
                                    <div class="progress" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress__track"><div class="progress__fill" style="width:50%"></div></div>
                                        <span class="progress__text">50%</span>
                                    </div>
                                </td>
                                <td><span class="badge badge--fail">Ditahan</span></td>
                                <td>
                                    <div class="table__actions">
                                        <a href="{{ route('production-trial.show', 'TRL-2026-0045') }}" class="icon-btn icon-btn--sm" aria-label="Buka trial"><i data-lucide="eye" aria-hidden="true"></i></a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Dokumen terkait --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Dokumen Terkait</h3>
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
                                    <td><span class="badge badge--{{ $d[3] }}">{{ $d[4] }}</span></td>
                                    <td>
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

            {{-- Catatan --}}
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Catatan</h3></div>
                <div class="panel__body">
                    <p>Formula baru dengan substitusi supplier ayam. Perhatikan parameter sealing karena pouch baru memiliki karakteristik berbeda. Koordinasi dengan QC sebelum filling dimulai.</p>
                </div>
            </section>
        </div>

        {{-- Kolom kanan --}}
        <aside class="form-side">
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Ringkasan</h3></div>
                <dl class="summary">
                    <div><dt>No. Plan</dt><dd>{{ $no }}</dd></div>
                    <div><dt>Produk</dt><dd>FG-LC-P01</dd></div>
                    <div><dt>Target</dt><dd>5.000 pouch</dd></div>
                    <div><dt>Line</dt><dd>Line Pouch 1</dd></div>
                    <div><dt>Progress</dt><dd>35%</dd></div>
                    <div><dt>Trial Terkait</dt><dd>2</dd></div>
                    <div><dt>Material Kurang</dt><dd>2 item</dd></div>
                    <div><dt>Status</dt><dd><span class="badge badge--run">Berjalan</span></dd></div>
                </dl>
            </section>

            <section class="panel panel--fit">
                <div class="panel__head"><h3>Alur Approval</h3></div>
                <ol class="flow">
                    <li class="flow__item">
                        <span class="flow__dot"><i data-lucide="check" aria-hidden="true"></i></span>
                        <div><strong>Dibuat oleh</strong><small>Admin Produksi &middot; 10 Okt 2026, 07.10</small></div>
                    </li>
                    <li class="flow__item">
                        <span class="flow__dot"><i data-lucide="check" aria-hidden="true"></i></span>
                        <div><strong>Review Quality Control</strong><small>Dewi Anggraini &middot; 10 Okt 2026, 09.20</small></div>
                    </li>
                    <li class="flow__item">
                        <span class="flow__dot"><i data-lucide="check" aria-hidden="true"></i></span>
                        <div><strong>Review R&amp;D</strong><small>Rina Wulandari &middot; 10 Okt 2026, 11.05</small></div>
                    </li>
                    <li class="flow__item is-current">
                        <span class="flow__dot">4</span>
                        <div><strong>Persetujuan Manager</strong><small>Manager Produksi &middot; sedang berjalan</small></div>
                    </li>
                    <li class="flow__item">
                        <span class="flow__dot">5</span>
                        <div><strong>Siap Stock Transfer</strong><small>Gudang &amp; Production Trial</small></div>
                    </li>
                </ol>
            </section>

            <section class="panel panel--fit">
                <div class="panel__head"><h3>Riwayat Aktivitas</h3></div>
                <div class="panel__body">
                    <ul class="todo">
                        @foreach ($approval as $a)
                            <li class="todo__item">
                                <span class="todo__icon todo__icon--info"><i data-lucide="activity" aria-hidden="true"></i></span>
                                <div>
                                    <strong>{{ $a[1] }}</strong>
                                    <small>{{ $a[0] }} &middot; {{ $a[2] }}</small>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>
        </aside>
    </div>

</div>

@endsection