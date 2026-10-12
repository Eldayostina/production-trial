@extends('layouts.app')

@section('page_title', 'Detail Supplier')
@section('breadcrumb_parent', 'Master Supplier')

@section('content')

@php
    $code = $code ?? 'SUP-001';

    $suppliedItems = [
        ['RM-1001', 'Daging Tuna Cooked Flake', 'Raw Material', 'Rp 85.000 / kg', '7 Hari'],
        ['RM-1002', 'Daging Tuna Chunk', 'Raw Material', 'Rp 92.000 / kg', '7 Hari'],
    ];

    $recentPo = [
        ['PO-2026-0142', '01 Okt 2026', '500 kg', 'Rp 42.500.000', 'Selesai & Lulus QC'],
        ['PO-2026-0118', '15 Agu 2026', '400 kg', 'Rp 34.000.000', 'Selesai & Lulus QC'],
    ];

    $audit = [
        ['01 Okt 2026, 10.00', 'Purchasing Dept', 'Evaluasi Tahunan', 'Performa pengiriman tepat waktu 98% (Rating 4.8)'],
        ['10 Jan 2025, 09.00', 'Admin Sistem', 'Dibuat', 'Registrasi awal vendor approved'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>{{ $code }} <span class="badge badge--ok">Approved Vendor</span></h2>
        <p>PT Samudra Tuna Jaya &middot; Raw Material &middot; Kontak: Bpk. Hendra (0812-3456-7890)</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('master.supplier') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
        <button type="button" class="btn btn--light"><i data-lucide="pencil" aria-hidden="true"></i><span>Edit</span></button>
    </div>
</section>

<div id="supplierShowPage">

    {{-- Informasi Utama --}}
    <section class="panel panel--fit">
        <dl class="info-grid">
            <div><dt>Kode Vendor</dt><dd class="table__code">{{ $code }}</dd></div>
            <div><dt>Nama Perusahaan</dt><dd>PT Samudra Tuna Jaya</dd></div>
            <div><dt>Kategori Pasokan</dt><dd>Raw Material (Bahan Baku)</dd></div>
            <div><dt>Contact Person (PIC)</dt><dd>Bpk. Hendra (Sales Manager)</dd></div>
            <div><dt>Telepon / WA</dt><dd>0812-3456-7890</dd></div>
            <div><dt>Rata-rata Lead Time</dt><dd>7 Hari</dd></div>
            <div><dt>Rating Kinerja QC</dt><dd><strong>⭐ 4.8 / 5.0 (Sangat Baik)</strong></dd></div>
            <div><dt>Status Kerjasama</dt><dd><span class="badge badge--ok">Aktif</span></dd></div>
        </dl>
    </section>

    <div class="form-layout">
        <div class="form-main">

            {{-- Bahan Baku yang Disuplai --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Daftar Barang / Material Disuplai</h3>
                    <span class="badge badge--run">2 SKU</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th class="table__right">Harga Kontrak Terakhir</th>
                                <th>Lead Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliedItems as $item)
                                <tr>
                                    <td class="table__code">{{ $item[0] }}</td>
                                    <td><strong>{{ $item[1] }}</strong></td>
                                    <td><span class="badge badge--draft">{{ $item[2] }}</span></td>
                                    <td class="table__right">{{ $item[3] }}</td>
                                    <td>{{ $item[4] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Riwayat Purchase Order (PO) --}}
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Riwayat Purchase Order (PO) Terakhir</h3></div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. PO</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th class="table__right">Total Nilai</th>
                                <th>Status QC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentPo as $po)
                                <tr>
                                    <td class="table__code">{{ $po[0] }}</td>
                                    <td>{{ $po[1] }}</td>
                                    <td>{{ $po[2] }}</td>
                                    <td class="table__right">{{ $po[3] }}</td>
                                    <td><span class="badge badge--ok">{{ $po[4] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Riwayat Audit & Evaluasi --}}
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Riwayat Audit & Evaluasi Vendor</h3></div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Departemen</th>
                                <th>Jenis Aksi</th>
                                <th>Catatan Penilaian</th>
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

        {{-- Kolom Kanan --}}
        <aside class="form-side">
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Ringkasan Vendor</h3></div>
                <dl class="summary">
                    <div><dt>Kode</dt><dd>{{ $code }}</dd></div>
                    <div><dt>Kategori</dt><dd>Raw Material</dd></div>
                    <div><dt>Lead Time</dt><dd>7 Hari</dd></div>
                    <div><dt>Rating</dt><dd>⭐ 4.8</dd></div>
                    <div><dt>Status</dt><dd><span class="badge badge--ok">Aktif</span></dd></div>
                </dl>
            </section>
        </aside>
    </div>

</div>

@endsection