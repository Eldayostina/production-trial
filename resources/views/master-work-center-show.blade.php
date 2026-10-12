@extends('layouts.app')

@section('page_title', 'Detail Work Center')
@section('breadcrumb_parent', 'Master Work Center')

@section('content')

@php
    $code = $code ?? 'WC-POUCH-01';

    $maintenanceHistory = [
        ['05 Okt 2026', 'Preventive Maintenance Bulanan', 'Rian Hidayat', 'Selesai & Normal'],
        ['12 Agu 2026', 'Penggantian Seal Blister Sealing', 'Budi Santoso', 'Selesai & Normal'],
    ];

    $audit = [
        ['01 Okt 2026, 09.30', 'Admin Sistem', 'Update Kapasitas', 'Kapasitas dinaikkan dari 3200 ke 3500 pouch/jam'],
        ['15 Jan 2025, 08.00', 'Engineering', 'Dibuat', 'Registrasi awal line produksi pouch'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>{{ $code }} <span class="badge badge--ok">Aktif</span></h2>
        <p>Line Pouch 1 (Filling & Sealing) &middot; Produksi Pouch &middot; 3,500 Pouch / Jam</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('master.work-center') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
        <button type="button" class="btn btn--light"><i data-lucide="pencil" aria-hidden="true"></i><span>Edit</span></button>
        <button type="button" class="btn btn--primary"><i data-lucide="printer" aria-hidden="true"></i><span>Cetak</span></button>
    </div>
</section>

<div id="wcShowPage">

    {{-- Informasi Utama --}}
    <section class="panel panel--fit">
        <dl class="info-grid">
            <div><dt>Kode Work Center</dt><dd class="table__code">{{ $code }}</dd></div>
            <div><dt>Kategori Area</dt><dd>Produksi Pouch (Wet Food 85g)</dd></div>
            <div><dt>Kapasitas Standar</dt><dd>3,500 Pouch / Jam</dd></div>
            <div><dt>Penanggung Jawab (PIC)</dt><dd>Budi Santoso</dd></div>
            <div><dt>Tarif Overhead</dt><dd>Rp 150.000 / Jam</dd></div>
            <div><dt>Status Mesin</dt><dd><span class="badge badge--ok">Aktif (Ready)</span></dd></div>
        </dl>
    </section>

    <div class="form-layout">
        <div class="form-main">

            {{-- Riwayat Maintenance --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Riwayat Pemeliharaan (Maintenance)</h3>
                    <span class="badge badge--run">2 Catatan</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis Pekerjaan</th>
                                <th>Teknisi / PIC</th>
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($maintenanceHistory as $m)
                                <tr>
                                    <td>{{ $m[0] }}</td>
                                    <td><strong>{{ $m[1] }}</strong></td>
                                    <td>{{ $m[2] }}</td>
                                    <td><span class="badge badge--ok">{{ $m[3] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Riwayat Audit --}}
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Riwayat Audit & Perubahan</h3></div>
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

        {{-- Kolom Kanan --}}
        <aside class="form-side">
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Ringkasan</h3></div>
                <dl class="summary">
                    <div><dt>Kode</dt><dd>{{ $code }}</dd></div>
                    <div><dt>Area</dt><dd>Produksi Pouch</dd></div>
                    <div><dt>Kapasitas</dt><dd>3,500 Pcs/Jam</dd></div>
                    <div><dt>PIC</dt><dd>Budi Santoso</dd></div>
                    <div><dt>Status</dt><dd><span class="badge badge--ok">Aktif</span></dd></div>
                </dl>
            </section>
        </aside>
    </div>

</div>

@endsection