@extends('layouts.app')

@section('page_title', 'Detail BOM')
@section('breadcrumb_parent', 'Bill of Materials')

@section('content')

@php
    // DATA DUMMY - nomor di URL belum dipakai untuk mengambil data.
    $base = 1000;
    $fill = 85;

    // [kode, bahan, jenis, satuan, qty untuk base, scrap %]
    $lines = [
        ['RM-1001', 'Daging Tuna Cooked Flake',        'Bahan Baku',     'kg',  30,   3],
        ['RM-1005', 'Daging Ayam Giling',              'Bahan Baku',     'kg',  20,   3],
        ['RM-1010', 'Hati Ayam',                       'Bahan Baku',     'kg',  8,    3],
        ['RM-2001', 'Tepung Tapioka',                  'Bahan Baku',     'kg',  3,    2],
        ['RM-2010', 'Guar Gum',                        'Bahan Baku',     'kg',  0.8,  2],
        ['RM-2011', 'Karagenan',                       'Bahan Baku',     'kg',  0.5,  2],
        ['RM-2020', 'Taurine',                         'Bahan Baku',     'kg',  0.1,  1],
        ['RM-2021', 'Premix Vitamin & Mineral Kucing', 'Bahan Baku',     'kg',  0.6,  1],
        ['RM-2030', 'Minyak Ikan',                     'Bahan Penolong', 'kg',  1,    2],
        ['RM-2040', 'Garam',                           'Bahan Baku',     'kg',  0.4,  1],
        ['RM-2050', 'Perisa Palatant Tuna',            'Bahan Penolong', 'kg',  1,    2],
        ['RM-2090', 'Air Proses',                      'Bahan Penolong', 'kg',  25,   0],
        ['PK-3001', 'Retort Pouch 85 g Life Cat Tuna', 'Kemasan',        'pcs', 1000, 2],
        ['PK-3101', 'Karton Master isi 24 Pouch',      'Kemasan',        'pcs', 41.7, 1],
    ];

    $totalKg = 0;
    foreach ($lines as $l) {
        if ($l[3] === 'kg') { $totalKg += $l[4]; }
    }
    $massG = $totalKg / $base * 1000;
    $yield = $fill / $massG * 100;

    $f = fn ($n, $d) => rtrim(rtrim(number_format($n, $d, ',', '.'), '0'), ',');

    $history = [
        ['Rev. 03', '10 Okt 2026', 'Penambahan maizena untuk memperbaiki tekstur', 'Rina Wulandari', 'Review QA',  'wait'],
        ['Rev. 02', '01 Sep 2026', 'Penyesuaian taurine dan premix sesuai standar nutrisi', 'Rina Wulandari', 'Released', 'ok'],
        ['Rev. 01', '15 Jun 2026', 'Formula awal', 'Rina Wulandari', 'Obsolete', 'obsolete'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>{{ $no }} <span class="badge badge--ok">Released</span></h2>
        <p>Life Cat Pouch Tuna &amp; Chicken 85 g &middot; Rev. 02 &middot; BOM Produksi</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('bom') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
        <button type="button" class="btn btn--light"><i data-lucide="printer" aria-hidden="true"></i><span>Cetak</span></button>
        <a href="{{ route('bom.create') }}" class="btn btn--primary"><i data-lucide="copy-plus" aria-hidden="true"></i><span>Buat Revisi Baru</span></a>
    </div>
</section>

<section class="panel panel--fit" style="margin-bottom:20px">
    <dl class="info-grid">
        <div><dt>Produk Jadi</dt><dd>FG-LC-P01</dd></div>
        <div><dt>Base Quantity</dt><dd>1.000 pouch</dd></div>
        <div><dt>Berat Isi Bersih</dt><dd>{{ $fill }} g</dd></div>
        <div><dt>Tanggal Efektif</dt><dd>01 Sep 2026</dd></div>
        <div><dt>Dibuat oleh</dt><dd>Rina Wulandari (R&amp;D)</dd></div>
        <div><dt>Direview QA</dt><dd>Dewi Anggraini</dd></div>
        <div><dt>Di-release oleh</dt><dd>Manager R&amp;D</dd></div>
        <div><dt>No. Dokumen R&amp;D</dt><dd>ECN-2026-009</dd></div>
    </dl>
</section>

<div class="form-layout">
    <div class="form-main">

        <section class="panel panel--fit">
            <div class="panel__head panel__head--row">
                <h3>Komposisi Bahan</h3>
                <span class="hint-inline">Formula terkunci. Perubahan dilakukan lewat revisi baru.</span>
            </div>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Bahan</th>
                            <th>Jenis</th>
                            <th class="table__right">Qty / 1.000 pouch</th>
                            <th class="table__right">Per Unit</th>
                            <th class="table__right">Scrap</th>
                            <th class="table__right">% Formula</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lines as $i => $l)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="table__code">{{ $l[0] }}</td>
                                <td>{{ $l[1] }}</td>
                                <td>{{ $l[2] }}</td>
                                <td class="table__right">{{ $f($l[4], 3) }} {{ $l[3] }}</td>
                                <td class="table__right">{{ $f($l[4] / $base, 6) }} {{ $l[3] }}</td>
                                <td class="table__right">{{ $l[5] }}%</td>
                                <td class="table__right">{{ $l[3] === 'kg' ? $f($l[4] / $totalKg * 100, 2) . '%' : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel panel--fit">
            <div class="panel__head"><h3>Riwayat Revisi</h3></div>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr><th>Revisi</th><th>Tanggal</th><th>Perubahan</th><th>Oleh</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($history as $h)
                            <tr>
                                <td class="table__code">{{ $h[0] }}</td>
                                <td>{{ $h[1] }}</td>
                                <td style="white-space:normal">{{ $h[2] }}</td>
                                <td>{{ $h[3] }}</td>
                                <td><span class="badge badge--{{ $h[5] }}">{{ $h[4] }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <aside class="form-side">
        <section class="panel panel--fit">
            <div class="panel__head"><h3>Ringkasan</h3></div>
            <dl class="summary">
                <div><dt>Jumlah bahan</dt><dd>{{ count($lines) }}</dd></div>
                <div><dt>Massa formula / unit</dt><dd>{{ $f($massG, 2) }} g</dd></div>
                <div><dt>Berat isi bersih</dt><dd>{{ $fill }} g</dd></div>
                <div><dt>Perkiraan yield</dt><dd>{{ $f($yield, 1) }}%</dd></div>
                <div><dt>Dipakai di plan</dt><dd>3 plan</dd></div>
            </dl>
        </section>

        <section class="panel panel--fit">
            <div class="panel__head"><h3>Status Approval</h3></div>
            <ol class="flow">
                <li class="flow__item"><span class="flow__dot"><i data-lucide="check" aria-hidden="true"></i></span><div><strong>Disusun R&amp;D</strong><small>Rina Wulandari &middot; 25 Agu 2026</small></div></li>
                <li class="flow__item"><span class="flow__dot"><i data-lucide="check" aria-hidden="true"></i></span><div><strong>Review QA</strong><small>Dewi Anggraini &middot; 29 Agu 2026</small></div></li>
                <li class="flow__item is-current"><span class="flow__dot"><i data-lucide="check" aria-hidden="true"></i></span><div><strong>Released</strong><small>Manager R&amp;D &middot; 01 Sep 2026</small></div></li>
            </ol>
        </section>
    </aside>
</div>

@endsection