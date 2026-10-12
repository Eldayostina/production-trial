@extends('layouts.app')

@section('page_title', $kode ? 'Ubah Gudang' : 'Tambah Gudang')
@section('breadcrumb_parent', 'Master Gudang')

@section('content')

@php
    // DATA DUMMY - nanti diganti query database berdasarkan $kode.
    // [nama, tipe, kondisi, suhu min, suhu max, kapasitas, satuan, terisi %, PIC, status, zona [[kode, nama]]]
    $all = [
        'GD-CS01'  => ['Cold Storage',          'Bahan Baku',       'frozen',  -22, -18, 50000, 'kg',     72, 'Agus Pratama',   'aktif',    [['A1', 'Rak A1 - Daging Tuna'], ['A2', 'Rak A2 - Daging Ayam'], ['B1', 'Rak B1 - Hati & Jeroan']]],
        'GD-CH01'  => ['Chiller Bahan Baku',    'Bahan Baku',       'chiller', 0,   4,   10000, 'kg',     45, 'Agus Pratama',   'aktif',    []],
        'GD-DRY01' => ['Gudang Bahan Kering',   'Bahan Baku',       'ambient', null, 30, 80000, 'kg',     58, 'Siti Nurhaliza', 'aktif',    [['R1', 'Rak R1 - Tepung & Pati'], ['R2', 'Rak R2 - Hidrokoloid & Premix']]],
        'GD-AUX01' => ['Gudang Bahan Penolong', 'Bahan Penolong',   'ambient', null, 30, 20000, 'kg',     33, 'Siti Nurhaliza', 'aktif',    []],
        'GD-PKG01' => ['Gudang Kemasan',        'Kemasan',          'ambient', null, 35, 600,   'pallet', 64, 'Joko Susilo',    'aktif',    []],
        'GD-FG01'  => ['Gudang Barang Jadi',    'Barang Jadi',      'ambient', null, 30, 800,   'pallet', 51, 'Joko Susilo',    'aktif',    []],
        'GD-QRN01' => ['Area Karantina',        'Karantina',        'ambient', null, 30, 100,   'pallet', 20, 'Dewi Anggraini', 'aktif',    []],
        'GD-PRD01' => ['Staging Produksi',      'Staging Produksi', 'ambient', null, 30, 30,    'pallet', 40, 'Budi Santoso',   'aktif',    []],
        'GD-OLD01' => ['Gudang Lama (Tidak Dipakai)', 'Bahan Baku', 'ambient', null, 35, 5000,  'kg',     0,  'Siti Nurhaliza', 'nonaktif', []],
    ];

    $g = $kode ? ($all[$kode] ?? null) : null;
    abort_if($kode && ! $g, 404);

    $v = fn ($i, $d = '') => $g ? ($g[$i] ?? $d) : $d;

    $types  = ['Bahan Baku', 'Bahan Penolong', 'Kemasan', 'Barang Jadi', 'Karantina', 'Staging Produksi', 'Reject / Hold'];
    $conds  = ['frozen' => 'Beku (Frozen)', 'chiller' => 'Dingin (Chiller)', 'ambient' => 'Suhu Ruang'];
    $pics   = ['Agus Pratama', 'Siti Nurhaliza', 'Joko Susilo', 'Dewi Anggraini', 'Budi Santoso'];
    $rules  = [
        ['fefo',     'Metode FEFO (First Expired First Out)',  'Pengambilan barang mengutamakan expiry paling dekat.', true],
        ['lot',      'Wajib catat lot & tanggal kedaluwarsa',  'Setiap penerimaan dan transfer harus mencantumkan lot dan expiry.', true],
        ['qc',       'Perlu release QC sebelum barang keluar', 'Dipakai untuk area karantina. Barang baru bisa ditransfer setelah QC melepas lot.', false],
        ['source',   'Boleh jadi sumber Stock Transfer ke produksi', 'Material di gudang ini dapat diminta oleh Production Plan.', true],
        ['receive',  'Boleh menerima barang dari supplier',    'Gudang ini dapat dipilih pada penerimaan barang (Goods Receipt).', false],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>{{ $kode ? 'Ubah Gudang' : 'Tambah Gudang' }}</h2>
        <p>Tanda <span class="req">*</span> wajib diisi. Gudang baru hanya perlu didaftarkan sekali.</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('gudang') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
    </div>
</section>

<form id="gudangForm" class="form-layout" novalidate
      data-edit="{{ $kode ? '1' : '0' }}"
      data-filled="{{ $v(7, 0) }}"
      data-zones="{{ json_encode($v(10, [])) }}">

    <div class="form-main">

        {{-- 1. Informasi --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">1</span>Informasi Gudang</h3></div>
            <div class="panel__body form-grid">
                <div class="field">
                    <label class="field__label" for="gKode">Kode Gudang <span class="req">*</span></label>
                    <input class="input" id="gKode" type="text" maxlength="12" placeholder="Contoh: GD-CS02"
                           value="{{ $kode }}" {{ $kode ? 'readonly' : '' }}>
                    <small class="field__hint">{{ $kode ? 'Kode tidak dapat diubah setelah disimpan.' : 'Huruf besar, angka, dan tanda strip. Harus unik, tidak bisa diubah setelah disimpan.' }}</small>
                </div>
                <div class="field">
                    <label class="field__label" for="gNama">Nama Gudang <span class="req">*</span></label>
                    <input class="input" id="gNama" type="text" maxlength="60" placeholder="Contoh: Cold Storage 2" value="{{ $v(0) }}">
                </div>
                <div class="field">
                    <label class="field__label" for="gTipe">Tipe Gudang <span class="req">*</span></label>
                    <select class="select" id="gTipe">
                        <option value="">Pilih tipe</option>
                        @foreach ($types as $t)
                            <option @selected($v(1) === $t)>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="gPlant">Plant / Lokasi <span class="req">*</span></label>
                    <select class="select" id="gPlant">
                        <option>Pabrik Utama - Cikarang</option>
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="gPic">PIC Gudang <span class="req">*</span></label>
                    <select class="select" id="gPic">
                        <option value="">Pilih PIC</option>
                        @foreach ($pics as $p)
                            <option @selected($v(8) === $p)>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="gStatus">Status <span class="req">*</span></label>
                    <select class="select" id="gStatus">
                        <option value="aktif" @selected($v(9, 'aktif') === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected($v(9) === 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="alert alert--warn" id="gAlert" hidden>
                <i data-lucide="alert-triangle" aria-hidden="true"></i>
                <p>Gudang ini masih berisi stok. Gudang yang masih berisi stok tidak dapat dinonaktifkan. Kosongkan lewat Stock Transfer lebih dulu.</p>
            </div>
        </section>

        {{-- 2. Penyimpanan --}}
        <section class="panel panel--fit">
            <div class="panel__head panel__head--row">
                <h3><span class="step-no">2</span>Kondisi Penyimpanan</h3>
                <span class="hint-inline">Suhu terisi otomatis, bisa disesuaikan</span>
            </div>
            <div class="panel__body form-grid">
                <div class="field field--full">
                    <label class="field__label" for="gKondisi">Kondisi Penyimpanan <span class="req">*</span></label>
                    <select class="select" id="gKondisi">
                        <option value="">Pilih kondisi</option>
                        @foreach ($conds as $key => $label)
                            <option value="{{ $key }}" @selected($v(2) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="gTmin">Suhu Minimum <span class="req" id="gTreq" hidden>*</span></label>
                    <div class="input-group">
                        <input class="input" id="gTmin" type="number" step="any" value="{{ $v(3) }}">
                        <span class="input-group__addon">°C</span>
                    </div>
                </div>
                <div class="field">
                    <label class="field__label" for="gTmax">Suhu Maksimum <span class="req">*</span></label>
                    <div class="input-group">
                        <input class="input" id="gTmax" type="number" step="any" value="{{ $v(4) }}">
                        <span class="input-group__addon">°C</span>
                    </div>
                </div>
                <div class="field">
                    <label class="field__label" for="gKap">Kapasitas <span class="req">*</span></label>
                    <input class="input" id="gKap" type="number" min="1" step="1" placeholder="0" value="{{ $v(5) }}">
                </div>
                <div class="field">
                    <label class="field__label" for="gUnit">Satuan Kapasitas <span class="req">*</span></label>
                    <select class="select" id="gUnit">
                        @foreach (['kg', 'pallet', 'm³'] as $u)
                            <option @selected($v(6, 'kg') === $u)>{{ $u }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>

        {{-- 3. Aturan --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">3</span>Aturan Operasional</h3></div>
            <div class="checklist">
                @foreach ($rules as $r)
                    <label class="check">
                        <input type="checkbox" class="js-rule" value="{{ $r[0] }}" {{ ($g ? $g[2] !== null : true) && $r[3] ? 'checked' : '' }}>
                        <span><strong>{{ $r[1] }}</strong><small>{{ $r[2] }}</small></span>
                    </label>
                @endforeach
            </div>
        </section>

        {{-- 4. Zona --}}
        <section class="panel panel--fit">
            <div class="panel__head panel__head--row">
                <h3><span class="step-no">4</span>Zona / Rak (opsional)</h3>
                <span class="hint-inline">Sub-lokasi di dalam gudang</span>
            </div>
            <div id="zoneList"></div>
            <div class="panel__foot">
                <button type="button" class="btn btn--light" id="addZone"><i data-lucide="plus" aria-hidden="true"></i><span>Tambah Zona</span></button>
                <p>Dipakai untuk menunjuk posisi barang saat penerimaan dan transfer.</p>
            </div>
        </section>

        <div class="form-actions">
            <a href="{{ route('gudang') }}" class="btn btn--light">Batal</a>
            <button type="button" class="btn btn--primary" id="saveGudang"><i data-lucide="save" aria-hidden="true"></i><span>{{ $kode ? 'Simpan Perubahan' : 'Simpan Gudang' }}</span></button>
        </div>
    </div>

    <aside class="form-side">
        <section class="panel panel--fit">
            <div class="panel__head"><h3>Ringkasan</h3></div>
            <dl class="summary">
                <div><dt>Kode</dt><dd id="sumKode">-</dd></div>
                <div><dt>Nama</dt><dd id="sumNama">-</dd></div>
                <div><dt>Tipe</dt><dd id="sumTipe">-</dd></div>
                <div><dt>Kondisi</dt><dd id="sumKondisi">-</dd></div>
                <div><dt>Kapasitas</dt><dd id="sumKap">-</dd></div>
                <div><dt>Jumlah zona</dt><dd id="sumZona">0</dd></div>
                <div><dt>Status</dt><dd id="sumStatus">-</dd></div>
            </dl>
        </section>

        <section class="panel panel--fit">
            <div class="panel__head"><h3>Panduan Singkat</h3></div>
            <ol class="flow">
                <li class="flow__item is-current"><span class="flow__dot">1</span><div><strong>Daftarkan gudang sekali</strong><small>Hanya jika gudang benar-benar baru</small></div></li>
                <li class="flow__item"><span class="flow__dot">2</span><div><strong>Dipakai di transaksi</strong><small>Penerimaan barang &amp; Stock Transfer</small></div></li>
                <li class="flow__item"><span class="flow__dot">3</span><div><strong>Stok dihitung per gudang &amp; lot</strong><small>Bukan diisi manual di sini</small></div></li>
            </ol>
        </section>
    </aside>

</form>

@endsection