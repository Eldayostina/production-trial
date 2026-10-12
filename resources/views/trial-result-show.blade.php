@extends('layouts.app')

@section('page_title', 'Detail Trial Result')
@section('breadcrumb_parent', 'Trial Result')

@section('content')

@php
    // DATA DUMMY - nomor di URL belum dipakai untuk mengambil data.

    // [kriteria, satuan, tipe (min|max|range|text), batas1, batas2, aktual, hasil teks (khusus tipe text), lulus teks]
    $criteria = [
        ['Yield proses',               '%',    'min',   95,  null, 96.4],
        ['Reject rate',                '%',    'max',   2,   null, 3.6],
        ['Berat isi per pouch',        'g',    'range', 83,  87,   85.2],
        ['Kekuatan seal',              'N/15 mm', 'min', 25, null, 27],
        ['Pouch menggembung (inkubasi 7 hari)', 'pcs', 'max', 0, null, 0],
        ['Uji mikrobiologi',           '',     'text',  null, null, 'Steril komersial', true],
    ];

    $rows = [];
    $failCount = 0;
    foreach ($criteria as $c) {
        [$name, $unit, $type, $a, $b, $actual] = $c;

        if ($type === 'min')   { $target = '≥ ' . $a . ' ' . $unit;                $pass = $actual >= $a; }
        elseif ($type === 'max'){ $target = '≤ ' . $a . ' ' . $unit;               $pass = $actual <= $a; }
        elseif ($type === 'range'){ $target = $a . ' - ' . $b . ' ' . $unit;       $pass = $actual >= $a && $actual <= $b; }
        else                   { $target = 'Steril komersial';                      $pass = $c[6]; }

        if (! $pass) { $failCount++; }

        $rows[] = [
            'name'   => $name,
            'target' => $target,
            'actual' => is_numeric($actual) ? rtrim(rtrim(number_format($actual, 2, ',', '.'), '0'), ',') . ' ' . $unit : $actual,
            'pass'   => $pass,
        ];
    }

    $suggest = $failCount === 0 ? 'Lulus' : 'Lulus Bersyarat / perlu tinjauan';
@endphp

<section class="page-head">
    <div>
        <h2>{{ $no }} <span class="badge badge--run">Dalam Review</span></h2>
        <p>Life Cat Pouch Tuna &amp; Chicken 85 g &middot; Batch LCP01-260925-A</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('trial-result') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
        <a href="{{ route('production-trial.show', 'TRL-2026-0044') }}" class="btn btn--light"><i data-lucide="flask-conical" aria-hidden="true"></i><span>Lihat Data Trial</span></a>
    </div>
</section>

<div id="resultPage" data-fail-count="{{ $failCount }}">

<section class="panel panel--fit">
    <dl class="info-grid">
        <div><dt>Ref. Production Trial</dt><dd class="table__code">TRL-2026-0044</dd></div>
        <div><dt>Ref. Production Plan</dt><dd class="table__code">PP-2026-0012</dd></div>
        <div><dt>Tipe Trial</dt><dd>Trial Parameter Sealing</dd></div>
        <div><dt>Versi BOM</dt><dd>BOM-LC-P01 Rev. 02</dd></div>
        <div><dt>Line</dt><dd>Line Pouch 1</dd></div>
        <div><dt>Periode</dt><dd>25 Sep - 28 Sep 2026</dd></div>
        <div><dt>Qty Diproses</dt><dd>2.500 pouch</dd></div>
        <div><dt>Output Baik / Reject</dt><dd>2.410 / 90 pouch</dd></div>
    </dl>
</section>

<div class="form-layout">
    <div class="form-main">

        {{-- Evaluasi kriteria --}}
        <section class="panel panel--fit">
            <div class="panel__head panel__head--row">
                <h3>Evaluasi Kriteria Keberhasilan</h3>
                <span class="hint-inline">Kriteria diambil dari Production Plan</span>
            </div>

            <div class="verdict {{ $failCount === 0 ? 'verdict--ok' : 'verdict--warn' }}">
                <i data-lucide="{{ $failCount === 0 ? 'check-circle-2' : 'alert-triangle' }}" aria-hidden="true"></i>
                <div>
                    <strong>
                        {{ $failCount === 0 ? 'Semua kriteria terpenuhi' : $failCount . ' dari ' . count($rows) . ' kriteria tidak terpenuhi' }}
                    </strong>
                    <small>Saran sistem: {{ $suggest }}. Keputusan akhir tetap oleh reviewer.</small>
                </div>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th class="table__right">Target</th>
                            <th class="table__right">Aktual</th>
                            <th>Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $r)
                            <tr>
                                <td>{{ $r['name'] }}</td>
                                <td class="table__right">{{ $r['target'] }}</td>
                                <td class="table__right {{ $r['pass'] ? '' : 'is-over' }}">{{ $r['actual'] }}</td>
                                <td>
                                    <span class="badge badge--{{ $r['pass'] ? 'ok' : 'fail' }}">{{ $r['pass'] ? 'Sesuai' : 'Tidak sesuai' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Deviasi selama trial --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3>Deviasi Selama Trial</h3></div>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr><th>Tahap</th><th>Parameter</th><th>Temuan</th><th>Tindakan Koreksi</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Sealing</td>
                            <td>Suhu seal bar</td>
                            <td>192 °C (batas 170 - 190 °C)</td>
                            <td>Setelan diturunkan ke 182 °C, 40 pouch awal diseleksi ulang</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Keputusan --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3>Keputusan Review</h3></div>

            <div class="alert alert--warn" id="decisionWarn" hidden>
                <i data-lucide="alert-triangle" aria-hidden="true"></i>
                <p>Masih ada kriteria yang tidak sesuai. Jika memilih <strong>Lulus</strong>, jelaskan justifikasinya pada catatan keputusan.</p>
            </div>

            <div class="panel__body form-grid">
                <div class="field field--full">
                    <label class="field__label" for="decision">Keputusan <span class="req">*</span></label>
                    <select class="select" id="decision" required>
                        <option value="">Pilih keputusan</option>
                        <option value="pass">Lulus - dapat dilanjutkan ke scale-up</option>
                        <option value="cond">Lulus Bersyarat - perlu perbaikan tertentu</option>
                        <option value="retry">Gagal - ulangi trial</option>
                        <option value="stop">Gagal - hentikan formula / proses ini</option>
                    </select>
                </div>
                <div class="field field--full">
                    <label class="field__label" for="decisionNote">Catatan Keputusan <span class="req">*</span></label>
                    <textarea class="input textarea" id="decisionNote" rows="3" placeholder="Dasar pertimbangan keputusan, berdasarkan data trial dan hasil QC..."></textarea>
                </div>
                <div class="field field--full" id="followUpField">
                    <label class="field__label" for="followUp">Tindak Lanjut / CAPA <span class="req" id="followUpReq" hidden>*</span></label>
                    <textarea class="input textarea" id="followUp" rows="2" placeholder="Contoh: Kalibrasi seal bar, ulangi trial dengan suhu 182 °C, tambah sampling QC tiap 30 menit."></textarea>
                    <small class="field__hint">Wajib diisi jika keputusan bukan Lulus.</small>
                </div>
                <div class="field">
                    <label class="field__label" for="followUpPic">PIC Tindak Lanjut</label>
                    <select class="select" id="followUpPic">
                        <option value="">Pilih PIC</option>
                        <option>Budi Santoso - Supervisor Produksi</option>
                        <option>Rina Wulandari - R&amp;D</option>
                        <option>Dewi Anggraini - Quality Assurance</option>
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="followUpDate">Target Selesai</label>
                    <input class="input" id="followUpDate" type="date">
                </div>
            </div>
        </section>

        <div class="form-actions">
            <a href="{{ route('trial-result') }}" class="btn btn--light">Batal</a>
            <button type="button" class="btn btn--light" data-result-action="draft"><i data-lucide="save" aria-hidden="true"></i><span>Simpan Draft</span></button>
            <button type="button" class="btn btn--primary" data-result-action="submit"><i data-lucide="send" aria-hidden="true"></i><span>Kirim ke Approval</span></button>
        </div>
    </div>

    <aside class="form-side">
        <section class="panel panel--fit">
            <div class="panel__head"><h3>Ringkasan</h3></div>
            <dl class="summary">
                <div><dt>No. Batch</dt><dd>LCP01-260925-A</dd></div>
                <div><dt>Yield</dt><dd>96,4%</dd></div>
                <div><dt>Reject Rate</dt><dd>3,6%</dd></div>
                <div><dt>Kriteria sesuai</dt><dd>{{ count($rows) - $failCount }} / {{ count($rows) }}</dd></div>
                <div><dt>Keputusan</dt><dd id="sumDecision">-</dd></div>
            </dl>
        </section>

        <section class="panel panel--fit">
            <div class="panel__head"><h3>Alur Approval</h3></div>
            <ol class="flow">
                <li class="flow__item">
                    <span class="flow__dot"><i data-lucide="check" aria-hidden="true"></i></span>
                    <div><strong>Review Quality Control</strong><small>Dewi Anggraini &middot; 29 Sep 2026</small></div>
                </li>
                <li class="flow__item is-current">
                    <span class="flow__dot">2</span>
                    <div><strong>Review R&amp;D</strong><small>Rina Wulandari &middot; sedang berjalan</small></div>
                </li>
                <li class="flow__item">
                    <span class="flow__dot">3</span>
                    <div><strong>Persetujuan Manager</strong><small>Manager Produksi</small></div>
                </li>
                <li class="flow__item">
                    <span class="flow__dot">4</span>
                    <div><strong>Dokumen ditutup</strong><small>Siap untuk keputusan scale-up</small></div>
                </li>
            </ol>
        </section>
    </aside>
</div>

</div>

@endsection