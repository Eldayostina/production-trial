@extends('layouts.app')

@section('page_title', 'Detail Barang')
@section('breadcrumb_parent', 'Master Barang')

@section('content')

@php
    // DATA DUMMY - kode di URL belum dipakai untuk query.

    // [atribut, nilai, metode uji, rujukan]
    $specs = [
        ['Kadar air',        '≤ 12%',           'Oven method',       'SNI 01-2891'],
        ['Kadar protein',    '≥ 45%',           'Kjeldahl',          'SNI 01-2891'],
        ['Kadar lemak',      '≤ 8%',            'Soxhlet',           'SNI 01-2891'],
        ['Cemaran mikroba',  'TPC < 10^4 cfu/g', 'Plate count',      'BPOM'],
        ['Logam berat',      'Pb < 0,3 ppm',    'AAS',               'BPOM'],
    ];

    // [supplier, harga terakhir (Rp), lead time (hari), status]
    $suppliers = [
        ['PT Samudra Tuna Jaya',     85000, 7,  'Aktif'],
        ['CV Bahari Nusantara',      87000, 10, 'Aktif'],
        ['PT Nelayan Sejahtera',     86000, 8,  'Nonaktif'],
    ];

    // [gudang, stok, min, status]
    $stockByWarehouse = [
        ['Cold Storage', 120.0, 100, 'Aman'],
    ];

    // [tanggal, supplier, harga, sumber]
    $priceHistory = [
        ['01 Okt 2026', 'PT Samudra Tuna Jaya', 85000, 'PO-2026-0142'],
        ['05 Sep 2026', 'PT Samudra Tuna Jaya', 83000, 'PO-2026-0130'],
        ['01 Agu 2026', 'CV Bahari Nusantara',  88000, 'PO-2026-0119'],
    ];

    // [tanggal, user, aksi, catatan]
    $audit = [
        ['10 Okt 2026, 08.12', 'Rina Wulandari', 'Ubah spesifikasi',    'Update kadar protein dari 40% ke 45%'],
        ['05 Okt 2026, 14.50', 'Admin Sistem',   'Ubah harga rata-rata','Sesuai PO terakhir'],
        ['01 Jan 2025, 09.00', 'Admin Sistem',   'Dibuat',              'Barang baru dibuat'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>{{ $code }} <span class="badge badge--ok">Aktif</span></h2>
        <p>Daging Tuna Cooked Flake &middot; Raw Material &middot; kg</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('master-barang') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
        <button type="button" class="btn btn--light"><i data-lucide="pencil" aria-hidden="true"></i><span>Edit</span></button>
        <button type="button" class="btn btn--primary"><i data-lucide="printer" aria-hidden="true"></i><span>Cetak</span></button>
    </div>
</section>

<div id="itemPage">

    {{-- Informasi barang --}}
    <section class="panel panel--fit">
        <dl class="info-grid">
            <div><dt>Kode Barang</dt><dd class="table__code">{{ $code }}</dd></div>
            <div><dt>Kategori</dt><dd>Raw Material</dd></div>
            <div><dt>Satuan Dasar</dt><dd>kg</dd></div>
            <div><dt>Merek</dt><dd>Life Cat</dd></div>
            <div><dt>Gudang Default</dt><dd>Cold Storage</dd></div>
            <div><dt>Shelf Life</dt><dd>90 hari</dd></div>
            <div><dt>Kondisi Penyimpanan</dt><dd>-18 °C (Frozen)</dd></div>
            <div><dt>Status</dt><dd><span class="badge badge--ok">Aktif</span></dd></div>
        </dl>
    </section>

    <div class="form-layout">
        <div class="form-main">

            {{-- Spesifikasi --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Spesifikasi &amp; Atribut</h3>
                    <span class="hint-inline">Standar kualitas bahan</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Atribut</th>
                                <th>Nilai</th>
                                <th>Metode Uji</th>
                                <th>Rujukan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($specs as $s)
                                <tr>
                                    <td>{{ $s[0] }}</td>
                                    <td><strong>{{ $s[1] }}</strong></td>
                                    <td>{{ $s[2] }}</td>
                                    <td>{{ $s[3] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Supplier --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Supplier Terdaftar</h3>
                    <span class="badge badge--wait">{{ count($suppliers) }} supplier</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Supplier</th>
                                <th class="table__right">Harga Terakhir</th>
                                <th class="table__right">Lead Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliers as $s)
                                <tr>
                                    <td>{{ $s[0] }}</td>
                                    <td class="table__right">Rp {{ number_format($s[1], 0, ',', '.') }}/kg</td>
                                    <td class="table__right">{{ $s[2] }} hari</td>
                                    <td><span class="badge badge--{{ $s[3] === 'Aktif' ? 'ok' : 'draft' }}">{{ $s[3] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Riwayat harga --}}
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Riwayat Harga</h3></div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Supplier</th>
                                <th class="table__right">Harga</th>
                                <th>Sumber</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($priceHistory as $p)
                                <tr>
                                    <td>{{ $p[0] }}</td>
                                    <td>{{ $p[1] }}</td>
                                    <td class="table__right">Rp {{ number_format($p[2], 0, ',', '.') }}/kg</td>
                                    <td class="table__code">{{ $p[3] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Audit --}}
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Riwayat Audit</h3></div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>User</th>
                                <th>Aksi</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($audit as $a)
                                <tr>
                                    <td>{{ $a[0] }}</td>
                                    <td>{{ $a[1] }}</td>
                                    <td>{{ $a[2] }}</td>
                                    <td>{{ $a[3] }}</td>
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
                    <div><dt>Kode</dt><dd>{{ $code }}</dd></div>
                    <div><dt>Kategori</dt><dd>Raw Material</dd></div>
                    <div><dt>Satuan</dt><dd>kg</dd></div>
                    <div><dt>Shelf Life</dt><dd>90 hari</dd></div>
                    <div><dt>Supplier Aktif</dt><dd>{{ count($suppliers) - 1 }}</dd></div>
                    <div><dt>Status</dt><dd><span class="badge badge--ok">Aktif</span></dd></div>
                </dl>
            </section>

   
        </aside>
    </div>

</div>

@endsection