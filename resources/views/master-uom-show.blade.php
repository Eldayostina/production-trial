@extends('layouts.app')

@section('page_title', 'Detail Satuan (UoM)')
@section('breadcrumb_parent', 'Master Satuan')

@section('content')

@php
    $code = $code ?? 'KG';

    $relatedItems = [
        ['RM-1001', 'Daging Tuna Cooked Flake', 'Raw Material', 'Cold Storage'],
        ['RM-1005', 'Daging Ayam Giling', 'Raw Material', 'Cold Storage'],
        ['RM-2001', 'Tepung Tapioka', 'Raw Material', 'Gudang Bahan Kering'],
    ];

    $audit = [
        ['01 Okt 2026, 08.00', 'Admin Sistem', 'Dibuat', 'Inisialisasi master satuan dasar sistem'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>{{ $code }} <span class="badge badge--ok">Aktif</span></h2>
        <p>Kilogram &middot; Berat / Massa &middot; Digunakan oleh 85 SKU aktif di pabrik</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('master.uom') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
        <button type="button" class="btn btn--light"><i data-lucide="pencil" aria-hidden="true"></i><span>Edit</span></button>
    </div>
</section>

<div id="uomShowPage">

    {{-- Informasi Utama --}}
    <section class="panel panel--fit">
        <dl class="info-grid">
            <div><dt>Kode Satuan</dt><dd class="table__code">{{ $code }}</dd></div>
            <div><dt>Nama Satuan</dt><dd>Kilogram</dd></div>
            <div><dt>Kategori</dt><dd>Berat / Massa</dd></div>
            <div><dt>Status Acuan</dt><dd>Satuan Dasar Utama (Base UoM)</dd></div>
            <div><dt>Total SKU Terkait</dt><dd>85 Barang</dd></div>
            <div><dt>Status Sistem</dt><dd><span class="badge badge--ok">Aktif</span></dd></div>
        </dl>
    </section>

    <div class="form-layout">
        <div class="form-main">

            {{-- Daftar Barang / SKU yang Menggunakan Satuan Ini --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Contoh SKU yang Menggunakan Satuan Ini</h3>
                    <span class="badge badge--run">85 SKU</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Gudang Default</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($relatedItems as $item)
                                <tr>
                                    <td class="table__code">{{ $item[0] }}</td>
                                    <td><strong>{{ $item[1] }}</strong></td>
                                    <td><span class="badge badge--draft">{{ $item[2] }}</span></td>
                                    <td>{{ $item[3] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Riwayat Audit --}}
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

        {{-- Kolom Kanan --}}
        <aside class="form-side">
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Ringkasan</h3></div>
                <dl class="summary">
                    <div><dt>Kode</dt><dd>{{ $code }}</dd></div>
                    <div><dt>Kategori</dt><dd>Berat</dd></div>
                    <div><dt>SKU Aktif</dt><dd>85</dd></div>
                    <div><dt>Status</dt><dd><span class="badge badge--ok">Aktif</span></dd></div>
                </dl>
            </section>
        </aside>
    </div>

</div>

@endsection