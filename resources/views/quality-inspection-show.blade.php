@extends('layouts.app')

@section('page_title', 'Detail Quality Inspection')
@section('breadcrumb_parent', 'Quality Inspection')

@section('content')

@php
    // DATA DUMMY - nomor di URL belum dipakai untuk query.

    // [parameter, spesifikasi, hasil, metode, status]
    $criteria = [
        ['Berat isi bersih',        '85 g (±2 g)',       '85,4 g',    'Timbangan digital',    'ok'],
        ['Kekuatan seal',           'Min. 25 N/15 mm',   '27 N',      'Seal strength tester', 'ok'],
        ['Visual & kebocoran',      'Tidak ada bocor',   'Sesuai',    'Visual',               'ok'],
        ['Suhu retort',             '121 °C ± 1',        '121,2 °C',  'Data logger',          'ok'],
        ['Waktu penahanan retort',  '45 menit (min 44)', '45 menit',  'Data logger',          'ok'],
        ['Nilai F0',                '≥ 6 menit',         '7,2 menit', 'Kalkulasi F0',         'ok'],
        ['Uji organoleptik',        'Warna, aroma, tekstur sesuai standar','Sesuai', 'Panelis terlatih','ok'],
        ['Uji inkubasi 7 hari',     'Tidak menggembung', 'Belum',     'Inkubator 37 °C',      'wait'],
        ['Uji mikrobiologi (TPC)',  '< 10 cfu/g',        'Belum',     'Metode plate count',   'wait'],
        ['Uji mikrobiologi (Salmonella)','Negatif',      'Belum',     'Metode PCR',           'wait'],
    ];

    // [tanggal, user, peran, aksi, catatan]
    $history = [
        ['10 Okt 2026, 15.30', 'Agus Setiawan', 'QC Proses',   'Membuat inspeksi',         'Inspeksi final setelah retort selesai.'],
        ['10 Okt 2026, 15.35', 'Agus Setiawan', 'QC Proses',   'Melengkapi hasil uji',      'Parameter proses sudah lengkap, lanjut uji mikrobiologi.'],
        ['10 Okt 2026, 16.10', 'Dewi Anggraini','QA Supervisor','Memulai uji mikrobiologi',  'Sampel dikirim ke lab, hasil menunggu 7 hari inkubasi.'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>QI-2026-0123 <span class="badge badge--run">Sedang Diuji</span></h2>
        <p>In-Process Inspection &middot; Life Cat Pouch Tuna &amp; Chicken 85 g &middot; Batch LCP01-261010-A</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('quality-inspection') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
        <a href="{{ route('production-trial.show', 'TRL-2026-0048') }}" class="btn btn--light"><i data-lucide="external-link" aria-hidden="true"></i><span>Lihat Production Trial</span></a>
        <button type="button" class="btn btn--primary"><i data-lucide="send" aria-hidden="true"></i><span>Kirim Hasil</span></button>
    </div>
</section>

<div id="qiPage">

    {{-- Informasi inspeksi --}}
    <section class="panel panel--fit">
        <dl class="info-grid">
            <div><dt>No. Inspeksi</dt><dd class="table__code">QI-2026-0123</dd></div>
            <div><dt>Jenis</dt><dd>In-Process Inspection</dd></div>
            <div><dt>Ref. Batch</dt><dd class="table__code">LCP01-261010-A</dd></div>
            <div><dt>Material / Produk</dt><dd>Life Cat Pouch Tuna &amp; Chicken 85 g</dd></div>
            <div><dt>Tahap</dt><dd>Retort (Sterilisasi)</dd></div>
            <div><dt>Tanggal</dt><dd>10 Okt 2026</dd></div>
            <div><dt>Inspektur QC</dt><dd>Agus Setiawan</dd></div>
            <div><dt>Spec Rujukan</dt><dd>SP-QC-LC-P01 Rev. 03</dd></div>
        </dl>
    </section>

    <div class="form-layout">
        <div class="form-main">

            {{-- Kriteria & hasil uji --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Kriteria &amp; Hasil Uji</h3>
                    <span class="hint-inline">Spesifikasi diambil dari standar QC produk</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Parameter</th>
                                <th>Spesifikasi</th>
                                <th>Hasil</th>
                                <th>Metode</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($criteria as $c)
                                <tr>
                                    <td>{{ $c[0] }}</td>
                                    <td>{{ $c[1] }}</td>
                                    <td class="{{ $c[4] === 'fail' ? 'is-over' : '' }}">{{ $c[2] }}</td>
                                    <td>{{ $c[3] }}</td>
                                    <td><span class="badge badge--{{ $c[4] }}">{{ $c[4] === 'ok' ? 'Sesuai' : ($c[4] === 'fail' ? 'Tidak Sesuai' : 'Menunggu') }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Keputusan batch --}}
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Keputusan Batch</h3></div>
                <div class="panel__body form-grid">
                    <div class="field field--full">
                        <label class="field__label" for="batchDecision">Status Batch <span class="req">*</span></label>
                        <select class="select" id="batchDecision" required>
                            <option value="">Pilih status</option>
                            <option value="released">Released - batch boleh masuk gudang produk jadi</option>
                            <option value="hold">Hold - menunggu hasil uji lanjutan</option>
                            <option value="reject">Reject - batch tidak lolos, perlu tindak lanjut</option>
                        </select>
                    </div>
                    <div class="field field--full">
                        <label class="field__label" for="batchNote">Catatan QC <span class="req">*</span></label>
                        <textarea class="input textarea" id="batchNote" rows="3" placeholder="Catatan hasil inspeksi, deviasi, atau instruksi khusus..."></textarea>
                    </div>
                </div>
            </section>

            {{-- Riwayat aktivitas --}}
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Riwayat Aktivitas</h3></div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal &amp; Waktu</th>
                                <th>User</th>
                                <th>Peran</th>
                                <th>Aksi</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($history as $h)
                                <tr>
                                    <td>{{ $h[0] }}</td>
                                    <td>{{ $h[1] }}</td>
                                    <td>{{ $h[2] }}</td>
                                    <td>{{ $h[3] }}</td>
                                    <td>{{ $h[4] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Aksi --}}
            <div class="form-actions">
                <a href="{{ route('quality-inspection') }}" class="btn btn--light">Batal</a>
                <button type="button" class="btn btn--light" data-qi-action="draft"><i data-lucide="save" aria-hidden="true"></i><span>Simpan Draft</span></button>
                <button type="button" class="btn btn--primary" data-qi-action="submit"><i data-lucide="send" aria-hidden="true"></i><span>Kirim Hasil</span></button>
            </div>
        </div>

        {{-- Kolom kanan --}}
        <aside class="form-side">
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Ringkasan</h3></div>
                <dl class="summary">
                    <div><dt>Jenis</dt><dd>In-Process</dd></div>
                    <div><dt>Batch</dt><dd>LCP01-261010-A</dd></div>
                    <div><dt>Inspektur</dt><dd>Agus Setiawan</dd></div>
                    <div><dt>Total Uji</dt><dd>10 parameter</dd></div>
                    <div><dt>Sudah Selesai</dt><dd>7 parameter</dd></div>
                    <div><dt>Menunggu</dt><dd>3 parameter</dd></div>
                    <div><dt>Status</dt><dd><span class="badge badge--run">Sedang Diuji</span></dd></div>
                </dl>
            </section>

            <section class="panel panel--fit">
                <div class="panel__head"><h3>Alur Inspeksi</h3></div>
                <ol class="flow">
                    <li class="flow__item"><span class="flow__dot"><i data-lucide="check" aria-hidden="true"></i></span>
                        <div><strong>Inspeksi dibuat</strong><small>10 Okt 2026, 15.30</small></div>
                    </li>
                    <li class="flow__item is-current"><span class="flow__dot">2</span>
                        <div><strong>Pengujian laboratorium</strong><small>Inkubasi 7 hari &amp; mikrobiologi</small></div>
                    </li>
                    <li class="flow__item"><span class="flow__dot">3</span>
                        <div><strong>Review QA Supervisor</strong><small>Dewi Anggraini</small></div>
                    </li>
                    <li class="flow__item"><span class="flow__dot">4</span>
                        <div><strong>Keputusan Release Batch</strong><small>Siap masuk gudang produk jadi</small></div>
                    </li>
                </ol>
            </section>
        </aside>
    </div>

</div>

@endsection