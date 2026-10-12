@extends('layouts.app')

@section('page_title', 'Detail Production Trial')
@section('breadcrumb_parent', 'Production Trial')

@section('content')

@php
// DATA DUMMY - nomor di URL belum dipakai untuk mengambil data. Nanti diganti query database.
$stages = [
['Persiapan & Penimbangan', 'done'],
['Thawing & Grinding', 'done'],
['Pemasakan & Mixing', 'done'],
['Filling', 'done'],
['Sealing', 'done'],
['Retort (Sterilisasi)', 'current'],
['Cooling & Pengeringan', 'pending'],
['Inkubasi & QC Release', 'pending'],
];

// [tahap, parameter, satuan, target, min, max, aktual]
$params = [
['Pemasakan', 'Suhu pemasakan adonan', '°C', '90', 85, 95, 91],
['Filling', 'Berat isi per pouch', 'g', '85', 83, 87, 85.4],
['Sealing', 'Suhu seal bar', '°C', '180', 170, 190, 192],
['Sealing', 'Tekanan seal', 'bar', '4.0', 3.5, 4.5, 4.1],
['Retort', 'Suhu sterilisasi', '°C', '121', 120, 122, 121.2],
['Retort', 'Waktu penahanan', 'menit', '45', 44, 50, ''],
['Retort', 'Tekanan retort', 'bar', '2.0', 1.8, 2.3, ''],
['Retort', 'Nilai F0', '-', '≥ 6', 6, 99, ''],
];

// [kode, material, satuan, rencana, aktual]
$materials = [
['RM-1001', 'Daging Tuna Cooked Flake', 'kg', 150, 151.2],
['RM-1005', 'Daging Ayam Giling', 'kg', 100, 99.5],
['RM-1010', 'Hati Ayam', 'kg', 40, 40],
['RM-2001', 'Tepung Tapioka', 'kg', 15, 15.3],
['RM-2010', 'Guar Gum', 'kg', 4, 4],
['RM-2020', 'Taurine', 'kg', 0.5, 0.5],
['RM-2021', 'Premix Vitamin & Mineral Kucing', 'kg', 3, 3.1],
['PK-3001', 'Retort Pouch 85 g Life Cat Tuna', 'pcs', 5250, 5190],
];

$qc = [
['Berat isi bersih', '85 g (±2 g)', '85,4 g', 'ok'],
['Kekuatan seal', 'Min. 25 N/15 mm', '27 N', 'ok'],
['Visual & kebocoran pouch','Tidak ada kebocoran', 'Sesuai', 'ok'],
['Uji inkubasi (7 hari)', 'Tidak menggembung', 'Menunggu', 'wait'],
['Uji mikrobiologi', 'Steril komersial', 'Menunggu', 'wait'],
];
@endphp

<section class="page-head">
    <div>
        <h2>{{ $no }} <span class="badge badge--run">Berjalan</span></h2>
        <p>Life Cat Pouch Tuna &amp; Chicken 85 g &middot; Batch LCP01-261010-A</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('production-trial') }}" class="btn btn--light"><i data-lucide="arrow-left"
                aria-hidden="true"></i><span>Kembali</span></a>
        <button type="button" class="btn btn--light" data-trial-action="hold"><i data-lucide="pause-circle"
                aria-hidden="true"></i><span>Tahan Trial</span></button>
        <button type="button" class="btn btn--primary" data-trial-action="finish"><i data-lucide="check-circle-2"
                aria-hidden="true"></i><span>Selesaikan &amp; Kirim ke Trial Result</span></button>
    </div>
</section>

<div id="trialPage">

    {{-- Informasi trial --}}
    <section class="panel panel--fit">
        <dl class="info-grid">
            <div>
                <dt>Ref. Production Plan</dt>
                <dd class="table__code">PP-2026-0018</dd>
            </div>
            <div>
                <dt>Tipe Trial</dt>
                <dd>Trial Formula Baru</dd>
            </div>
            <div>
                <dt>Versi BOM</dt>
                <dd>BOM-LC-P01 Rev. 02</dd>
            </div>
            <div>
                <dt>Line / Shift</dt>
                <dd>Line Pouch 1 &middot; Shift 1</dd>
            </div>
            <div>
                <dt>Target</dt>
                <dd>5.000 pouch</dd>
            </div>
            <div>
                <dt>Jadwal</dt>
                <dd>10 Okt - 14 Okt 2026</dd>
            </div>
            <div>
                <dt>PIC Produksi</dt>
                <dd>Budi Santoso</dd>
            </div>
            <div>
                <dt>PIC R&amp;D</dt>
                <dd>Rina Wulandari</dd>
            </div>
        </dl>
    </section>

    {{-- Tahap proses --}}
    <section class="panel panel--fit">
        <div class="panel__head">
            <h3>Tahapan Proses</h3>
        </div>
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

            {{-- Parameter proses --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Parameter Proses</h3>
                    <span class="hint-inline">Aktual di luar batas min/max otomatis ditandai deviasi</span>
                </div>
                <div class="alert alert--warn" id="devAlert" hidden>
                    <i data-lucide="alert-triangle" aria-hidden="true"></i>
                    <p id="devAlertText"></p>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tahap</th>
                                <th>Parameter</th>
                                <th class="table__right">Target</th>
                                <th class="table__right">Min - Max</th>
                                <th>Aktual</th>
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($params as $p)
                            <tr>
                                <td>{{ $p[0] }}</td>
                                <td>{{ $p[1] }}</td>
                                <td class="table__right">{{ $p[3] }} {{ $p[2] }}</td>
                                <td class="table__right">{{ $p[4] }} - {{ $p[5] == 99 ? '∞' : $p[5] }}</td>
                                <td>
                                    <div class="input-group input-group--sm">
                                        <input class="input input--sm js-param" type="number" step="any"
                                            data-min="{{ $p[4] }}" data-max="{{ $p[5] }}" value="{{ $p[6] }}"
                                            aria-label="Aktual {{ $p[1] }}">
                                        <span class="input-group__addon">{{ $p[2] }}</span>
                                    </div>
                                </td>
                                <td class="js-param-status"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="panel__body">
                    <div class="field">
                        <label class="field__label" for="devNote">Catatan Deviasi / Tindakan Koreksi</label>
                        <textarea class="input textarea" id="devNote" rows="2"
                            placeholder="Wajib diisi jika ada parameter di luar batas. Jelaskan penyebab dan tindakan yang diambil."></textarea>
                    </div>
                </div>
            </section>

            {{-- Pemakaian material --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Pemakaian Material</h3>
                    <span class="hint-inline">Rencana diambil dari Production Plan</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Material</th>
                                <th class="table__right">Rencana</th>
                                <th>Aktual</th>
                                <th class="table__right">Selisih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($materials as $m)
                            <tr>
                                <td class="table__code">{{ $m[0] }}</td>
                                <td>{{ $m[1] }}</td>
                                <td class="table__right">{{ number_format($m[3], 1, ',', '.') }} {{ $m[2] }}</td>
                                <td>
                                    <div class="input-group input-group--sm">
                                        <input class="input input--sm js-mat" type="number" step="any"
                                            data-plan="{{ $m[3] }}" value="{{ $m[4] }}" aria-label="Aktual {{ $m[1] }}">
                                        <span class="input-group__addon">{{ $m[2] }}</span>
                                    </div>
                                </td>
                                <td class="table__right js-mat-var"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Output & yield --}}
            <section class="panel panel--fit">
                <div class="panel__head">
                    <h3>Output &amp; Yield</h3>
                </div>
                <div class="panel__body form-grid form-grid--3">
                    <div class="field">
                        <label class="field__label" for="inputQty">Qty Diproses (pouch)</label>
                        <input class="input" id="inputQty" type="number" min="0" value="5190">
                    </div>
                    <div class="field">
                        <label class="field__label" for="goodQty">Output Baik (pouch)</label>
                        <input class="input" id="goodQty" type="number" min="0" value="4980">
                    </div>
                    <div class="field">
                        <label class="field__label" for="rejectQty">Reject (pouch)</label>
                        <input class="input" id="rejectQty" type="number" min="0" value="210">
                    </div>
                    <div class="field">
                        <label class="field__label" for="yieldVal">Yield</label>
                        <input class="input" id="yieldVal" type="text" value="-" readonly>
                    </div>
                    <div class="field">
                        <label class="field__label" for="rejectRate">Reject Rate</label>
                        <input class="input" id="rejectRate" type="text" value="-" readonly>
                    </div>
                    <div class="field">
                        <label class="field__label" for="rejectCause">Penyebab Utama Reject</label>
                        <select class="select" id="rejectCause">
                            <option value="">Pilih penyebab</option>
                            <option>Seal bocor / tidak sempurna</option>
                            <option>Berat isi di luar spesifikasi</option>
                            <option>Pouch rusak / kotor</option>
                            <option>Deformasi setelah retort</option>
                            <option>Lainnya</option>
                        </select>
                    </div>
                </div>
            </section>

            {{-- QC sampling --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Pengujian QC</h3>
                    <span class="hint-inline">Hasil akhir dicatat di Trial Result</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Pengujian</th>
                                <th>Spesifikasi</th>
                                <th>Hasil</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($qc as $q)
                            <tr>
                                <td>{{ $q[0] }}</td>
                                <td>{{ $q[1] }}</td>
                                <td>{{ $q[2] }}</td>
                                <td><span class="badge badge--{{ $q[3] }}">{{ $q[3] === 'ok' ? 'Sesuai' : 'Menunggu'
                                        }}</span></td>
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
                <div class="panel__head">
                    <h3>Ringkasan</h3>
                </div>
                <dl class="summary">
                    <div>
                        <dt>No. Batch</dt>
                        <dd>LCP01-261010-A</dd>
                    </div>
                    <div>
                        <dt>Tahap saat ini</dt>
                        <dd>6/8 Retort</dd>
                    </div>
                    <div>
                        <dt>Parameter deviasi</dt>
                        <dd id="sumDev">-</dd>
                    </div>
                    <div>
                        <dt>Yield</dt>
                        <dd id="sumYield">-</dd>
                    </div>
                    <div>
                        <dt>Status</dt>
                        <dd><span class="badge badge--run">Berjalan</span></dd>
                    </div>
                </dl>
            </section>

            <section class="panel panel--fit">
                <div class="panel__head">
                    <h3>Riwayat Aktivitas</h3>
                </div>
                <ol class="flow">
                    <li class="flow__item is-current"><span class="flow__dot">6</span>
                        <div><strong>Retort dimulai</strong><small>10 Okt 2026, 13.20 &middot; Budi Santoso</small>
                        </div>
                    </li>
                    <li class="flow__item"><span class="flow__dot">5</span>
                        <div><strong>Sealing selesai</strong><small>10 Okt 2026, 11.45 &middot; Operator Line Pouch
                                1</small></div>
                    </li>
                    <li class="flow__item"><span class="flow__dot">4</span>
                        <div><strong>Filling selesai</strong><small>10 Okt 2026, 10.30 &middot; Operator Line Pouch
                                1</small></div>
                    </li>
                    <li class="flow__item"><span class="flow__dot">1</span>
                        <div><strong>Trial dimulai</strong><small>10 Okt 2026, 07.15 &middot; Budi Santoso</small></div>
                    </li>
                </ol>
            </section>
        </aside>
    </div>

</div>

@endsection