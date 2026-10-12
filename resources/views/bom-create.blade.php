@extends('layouts.app')

@section('page_title', 'Buat BOM')
@section('breadcrumb_parent', 'Bill of Materials')

@section('content')

@php
    // DATA DUMMY - nanti diganti data dari Master Barang (database)
    $items = [
        'RM-1001' => ['name' => 'Daging Tuna Cooked Flake',         'type' => 'Bahan Baku',     'uom' => 'kg',  'status' => 'Aktif'],
        'RM-1002' => ['name' => 'Daging Tuna Chunk',                'type' => 'Bahan Baku',     'uom' => 'kg',  'status' => 'Aktif'],
        'RM-1005' => ['name' => 'Daging Ayam Giling',               'type' => 'Bahan Baku',     'uom' => 'kg',  'status' => 'Aktif'],
        'RM-1010' => ['name' => 'Hati Ayam',                        'type' => 'Bahan Baku',     'uom' => 'kg',  'status' => 'Aktif'],
        'RM-1020' => ['name' => 'Daging Ikan Makarel',              'type' => 'Bahan Baku',     'uom' => 'kg',  'status' => 'Aktif'],
        'RM-2001' => ['name' => 'Tepung Tapioka',                   'type' => 'Bahan Baku',     'uom' => 'kg',  'status' => 'Aktif'],
        'RM-2002' => ['name' => 'Maizena',                          'type' => 'Bahan Baku',     'uom' => 'kg',  'status' => 'Trial'],
        'RM-2010' => ['name' => 'Guar Gum',                         'type' => 'Bahan Baku',     'uom' => 'kg',  'status' => 'Aktif'],
        'RM-2011' => ['name' => 'Karagenan',                        'type' => 'Bahan Baku',     'uom' => 'kg',  'status' => 'Aktif'],
        'RM-2020' => ['name' => 'Taurine',                          'type' => 'Bahan Baku',     'uom' => 'kg',  'status' => 'Aktif'],
        'RM-2021' => ['name' => 'Premix Vitamin & Mineral Kucing',  'type' => 'Bahan Baku',     'uom' => 'kg',  'status' => 'Aktif'],
        'RM-2040' => ['name' => 'Garam',                            'type' => 'Bahan Baku',     'uom' => 'kg',  'status' => 'Aktif'],
        'RM-2030' => ['name' => 'Minyak Ikan',                      'type' => 'Bahan Penolong', 'uom' => 'kg',  'status' => 'Aktif'],
        'RM-2050' => ['name' => 'Perisa Palatant Tuna',             'type' => 'Bahan Penolong', 'uom' => 'kg',  'status' => 'Aktif'],
        'RM-2090' => ['name' => 'Air Proses',                       'type' => 'Bahan Penolong', 'uom' => 'kg',  'status' => 'Aktif'],
        'PK-3001' => ['name' => 'Retort Pouch 85 g Life Cat Tuna',  'type' => 'Kemasan',        'uom' => 'pcs', 'status' => 'Aktif'],
        'PK-3101' => ['name' => 'Karton Master isi 24 Pouch',       'type' => 'Kemasan',        'uom' => 'pcs', 'status' => 'Aktif'],
    ];

    // Barang jadi dari Master Barang (fill = berat isi bersih, gram)
    $products = [
        'FG-LC-P01' => ['name' => 'Life Cat Pouch Tuna & Chicken 85 g', 'uom' => 'pouch',  'fill' => 85],
        'FG-LC-M01' => ['name' => 'Life Cat Pouch Mackerel 85 g',       'uom' => 'pouch',  'fill' => 85],
        'FG-LC-K01' => ['name' => 'Life Cat Kaleng Tuna 400 g',         'uom' => 'kaleng', 'fill' => 400],
    ];

    // BOM yang bisa disalin: [kode bahan, qty untuk base, scrap %]
    $templates = [
        'BOM-LC-P01-R02' => [
            'product' => 'FG-LC-P01', 'base' => 1000,
            'lines' => [
                ['RM-1001', 30, 3], ['RM-1005', 20, 3], ['RM-1010', 8, 3], ['RM-2001', 3, 2],
                ['RM-2010', 0.8, 2], ['RM-2011', 0.5, 2], ['RM-2020', 0.1, 1], ['RM-2021', 0.6, 1],
                ['RM-2030', 1, 2], ['RM-2040', 0.4, 1], ['RM-2050', 1, 2], ['RM-2090', 25, 0],
                ['PK-3001', 1000, 2], ['PK-3101', 41.7, 1],
            ],
        ],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>Buat BOM</h2>
        <p>Susun formula produk. Bahan dipilih dari Master Barang. Tanda <span class="req">*</span> wajib diisi.</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('bom') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
    </div>
</section>

<form id="bomForm" class="form-layout" novalidate
      data-items="{{ json_encode($items) }}"
      data-products="{{ json_encode($products) }}"
      data-templates="{{ json_encode($templates) }}">

    <div class="form-main">

        {{-- 1. Header --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">1</span>Informasi BOM</h3></div>
            <div class="panel__body form-grid">
                <div class="field">
                    <label class="field__label" for="bomNo">No. BOM</label>
                    <input class="input" id="bomNo" type="text" value="Otomatis saat disimpan" readonly>
                </div>
                <div class="field">
                    <label class="field__label" for="bomRev">Revisi</label>
                    <input class="input" id="bomRev" type="text" value="Rev. 01 (otomatis)" readonly>
                </div>
                <div class="field field--full">
                    <label class="field__label" for="bomTemplate">Salin dari BOM yang ada (opsional)</label>
                    <select class="select" id="bomTemplate">
                        <option value="">Mulai dari kosong</option>
                        @foreach ($templates as $key => $t)
                            <option value="{{ $key }}">{{ $key }} - {{ $products[$t['product']]['name'] }}</option>
                        @endforeach
                    </select>
                    <small class="field__hint">Dipakai untuk membuat revisi baru dari formula yang sudah ada.</small>
                </div>
                <div class="field field--full">
                    <label class="field__label" for="bomProduct">Produk Jadi <span class="req">*</span></label>
                    <select class="select" id="bomProduct" required>
                        <option value="">Pilih produk (dari Master Barang)</option>
                        @foreach ($products as $code => $p)
                            <option value="{{ $code }}">{{ $code }} - {{ $p['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="bomType">Tipe BOM <span class="req">*</span></label>
                    <select class="select" id="bomType">
                        <option>BOM Trial (R&amp;D)</option>
                        <option>BOM Produksi</option>
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="bomEffective">Tanggal Efektif <span class="req">*</span></label>
                    <input class="input" id="bomEffective" type="date" value="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="field">
                    <label class="field__label" for="bomBase">Base Quantity <span class="req">*</span></label>
                    <div class="input-group">
                        <input class="input" id="bomBase" type="number" min="1" step="1" value="1000">
                        <span class="input-group__addon" id="bomUom">unit</span>
                    </div>
                    <small class="field__hint">Jumlah produk yang menjadi dasar formula di bawah.</small>
                </div>
                <div class="field">
                    <label class="field__label" for="bomFill">Berat Isi Bersih</label>
                    <div class="input-group">
                        <input class="input" id="bomFill" type="text" value="-" readonly>
                        <span class="input-group__addon">g</span>
                    </div>
                </div>
                <div class="field">
                    <label class="field__label" for="bomPic">PIC R&amp;D <span class="req">*</span></label>
                    <select class="select" id="bomPic">
                        <option>Rina Wulandari - R&amp;D</option>
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="bomRef">No. Dokumen R&amp;D / ECN</label>
                    <input class="input" id="bomRef" type="text" placeholder="Contoh: ECN-2026-014">
                </div>
            </div>
        </section>

        {{-- 2. Komposisi --}}
        <section class="panel panel--fit">
            <div class="panel__head panel__head--row">
                <h3><span class="step-no">2</span>Komposisi Bahan</h3>
                <span class="hint-inline">Qty diisi untuk satu Base Quantity</span>
            </div>

            <div class="alert alert--warn" id="bomAlert" hidden>
                <i data-lucide="alert-triangle" aria-hidden="true"></i>
                <p id="bomAlertText"></p>
            </div>

            <div class="table-wrap">
                <table class="table bom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bahan</th>
                            <th>Jenis</th>
                            <th>Qty untuk Base</th>
                            <th class="table__right">Per Unit</th>
                            <th>Scrap %</th>
                            <th class="table__right">% Formula</th>
                            <th class="table__right">Hapus</th>
                        </tr>
                    </thead>
                    <tbody id="bomLines"></tbody>
                </table>
            </div>

            <div class="panel__foot">
                <button type="button" class="btn btn--light" id="addLine"><i data-lucide="plus" aria-hidden="true"></i><span>Tambah Bahan</span></button>
                <p>Bahan belum ada? Ajukan ke admin data untuk didaftarkan di Master Barang.</p>
            </div>
        </section>

        {{-- 3. Perubahan --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">3</span>Alasan &amp; Catatan Perubahan</h3></div>
            <div class="panel__body form-grid">
                <div class="field field--full">
                    <label class="field__label" for="bomReason">Alasan / Catatan Perubahan <span class="req">*</span></label>
                    <textarea class="input textarea" id="bomReason" rows="3" placeholder="Contoh: Penambahan maizena 0,002 kg per pouch untuk memperbaiki tekstur."></textarea>
                </div>
            </div>
        </section>

        <div class="form-actions">
            <a href="{{ route('bom') }}" class="btn btn--light">Batal</a>
            <button type="button" class="btn btn--light" data-bom-action="draft"><i data-lucide="save" aria-hidden="true"></i><span>Simpan Draft</span></button>
            <button type="button" class="btn btn--primary" data-bom-action="submit"><i data-lucide="send" aria-hidden="true"></i><span>Ajukan Review QA</span></button>
        </div>
    </div>

    <aside class="form-side">
        <section class="panel panel--fit">
            <div class="panel__head"><h3>Ringkasan</h3></div>
            <dl class="summary">
                <div><dt>Produk</dt><dd id="sumProduct">-</dd></div>
                <div><dt>Base Qty</dt><dd id="sumBase">-</dd></div>
                <div><dt>Jumlah bahan</dt><dd id="sumLines">0</dd></div>
                <div><dt>Massa formula / unit</dt><dd id="sumMass">-</dd></div>
                <div><dt>Berat isi bersih</dt><dd id="sumFill">-</dd></div>
                <div><dt>Perkiraan yield</dt><dd id="sumYield">-</dd></div>
                <div><dt>Status</dt><dd><span class="badge badge--draft">Draft</span></dd></div>
            </dl>
        </section>

        <section class="panel panel--fit">
            <div class="panel__head"><h3>Alur BOM</h3></div>
            <ol class="flow">
                <li class="flow__item is-current"><span class="flow__dot">1</span><div><strong>Disusun R&amp;D</strong><small>Status Draft</small></div></li>
                <li class="flow__item"><span class="flow__dot">2</span><div><strong>Review Quality Assurance</strong><small>Cek keamanan pangan &amp; regulasi</small></div></li>
                <li class="flow__item"><span class="flow__dot">3</span><div><strong>Release oleh Manager R&amp;D</strong><small>Formula terkunci</small></div></li>
                <li class="flow__item"><span class="flow__dot">4</span><div><strong>Dipakai Production Plan</strong><small>Hanya BOM Released yang bisa dipilih</small></div></li>
            </ol>
        </section>
    </aside>

</form>

@endsection