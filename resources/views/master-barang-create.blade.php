@extends('layouts.app')

@section('page_title', 'Tambah Barang')
@section('breadcrumb_parent', 'Master Barang')

@section('content')

@php
    // DATA DUMMY untuk pilihan
    $categories = [
        'FG'  => 'Finished Good - Produk jadi siap jual',
        'WIP' => 'Work in Progress - Setengah jadi',
        'RM'  => 'Raw Material - Bahan baku',
        'PM'  => 'Packaging Material - Kemasan',
        'CN'  => 'Consumable - Bahan penolong',
    ];

    $uoms = ['pcs', 'kg', 'g', 'liter', 'ml', 'pouch', 'kaleng', 'karton'];
    $warehouses = ['Cold Storage', 'Gudang Bahan Kering', 'Gudang Bahan Penolong', 'Gudang Kemasan', 'Gudang Produk Jadi'];
    $storageConditions = ['Frozen (-18 °C)', 'Chiller (0 - 4 °C)', 'Suhu Ruang (< 25 °C)', 'Sejuk Kering'];
@endphp

<section class="page-head">
    <div>
        <h2>Tambah Barang Baru</h2>
        <p>Isi data barang. Tanda <span class="req">*</span> wajib diisi.</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('master-barang') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
    </div>
</section>

<form id="itemForm" class="form-layout" novalidate>

    {{-- ===== KOLOM KIRI ===== --}}
    <div class="form-main">

        {{-- 1. Identitas --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">1</span>Identitas Barang</h3></div>
            <div class="panel__body form-grid">
                <div class="field">
                    <label class="field__label" for="itemCode">Kode Barang</label>
                    <input class="input" id="itemCode" type="text" value="Otomatis saat disimpan" readonly>
                    <small class="field__hint">Format: [Kategori]-[Urutan]. Contoh: FG-LC-P02, RM-1030.</small>
                </div>
                <div class="field">
                    <label class="field__label" for="itemStatus">Status <span class="req">*</span></label>
                    <select class="select" id="itemStatus" required>
                        <option>Aktif</option>
                        <option>Nonaktif</option>
                    </select>
                </div>
                <div class="field field--full">
                    <label class="field__label" for="itemName">Nama Barang <span class="req">*</span></label>
                    <input class="input" id="itemName" type="text" placeholder="Contoh: Life Cat Pouch Tuna & Chicken 85 g" required>
                </div>
                <div class="field">
                    <label class="field__label" for="itemCategory">Kategori <span class="req">*</span></label>
                    <select class="select" id="itemCategory" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="itemBrand">Merek</label>
                    <input class="input" id="itemBrand" type="text" value="Life Cat">
                </div>
            </div>
        </section>

        {{-- 2. Satuan & gudang --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">2</span>Satuan &amp; Penyimpanan</h3></div>
            <div class="panel__body form-grid">
                <div class="field">
                    <label class="field__label" for="itemUom">Satuan Dasar <span class="req">*</span></label>
                    <select class="select" id="itemUom" required>
                        <option value="">Pilih satuan</option>
                        @foreach ($uoms as $u)
                            <option>{{ $u }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="itemUomConv">Satuan Konversi</label>
                    <input class="input" id="itemUomConv" type="text" placeholder="Contoh: 1 karton = 24 pouch">
                    <small class="field__hint">Opsional, untuk satuan turunan.</small>
                </div>
                <div class="field">
                    <label class="field__label" for="itemWarehouse">Gudang Default <span class="req">*</span></label>
                    <select class="select" id="itemWarehouse" required>
                        <option value="">Pilih gudang</option>
                        @foreach ($warehouses as $w)
                            <option>{{ $w }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="itemStorage">Kondisi Penyimpanan <span class="req">*</span></label>
                    <select class="select" id="itemStorage" required>
                        <option value="">Pilih kondisi</option>
                        @foreach ($storageConditions as $c)
                            <option>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="itemShelfLife">Shelf Life (hari)</label>
                    <input class="input" id="itemShelfLife" type="number" min="0" placeholder="0">
                </div>
                <div class="field">
                    <label class="field__label" for="itemMinStock">Stok Minimum</label>
                    <div class="input-group">
                        <input class="input" id="itemMinStock" type="number" min="0" step="0.01" placeholder="0">
                        <span class="input-group__addon" id="uomAddon">-</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- 3. Spesifikasi --}}
        <section class="panel panel--fit">
            <div class="panel__head panel__head--row">
                <h3><span class="step-no">3</span>Spesifikasi &amp; Atribut</h3>
                <span class="hint-inline">Baris dinamis: tambah sesuai kebutuhan</span>
            </div>

            <div class="table-wrap">
                <table class="table table--form" id="specTable">
                    <thead>
                        <tr>
                            <th>Atribut</th>
                            <th>Nilai / Standar</th>
                            <th>Metode Uji</th>
                            <th>Rujukan</th>
                            <th class="table__right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="specBody">
                        <tr>
                            <td><input class="input input--sm" type="text" placeholder="Contoh: Kadar air"></td>
                            <td><input class="input input--sm" type="text" placeholder="Contoh: ≤ 12%"></td>
                            <td><input class="input input--sm" type="text" placeholder="Contoh: Oven method"></td>
                            <td><input class="input input--sm" type="text" placeholder="Contoh: SNI 01-2891"></td>
                            <td class="table__right">
                                <button type="button" class="btn--danger-ghost js-remove-row" aria-label="Hapus baris">
                                    <i data-lucide="trash-2" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="panel__body">
                <button type="button" class="btn btn--light" id="addSpec">
                    <i data-lucide="plus" aria-hidden="true"></i><span>Tambah Atribut</span>
                </button>
            </div>
        </section>

        {{-- 4. Supplier --}}
        <section class="panel panel--fit">
            <div class="panel__head panel__head--row">
                <h3><span class="step-no">4</span>Supplier Terdaftar</h3>
                <span class="hint-inline">Hanya untuk kategori RM / PM / CN</span>
            </div>

            <div class="table-wrap">
                <table class="table" id="supplierTable">
                    <thead>
                        <tr>
                            <th>Supplier</th>
                            <th>Harga Terakhir (Rp)</th>
                            <th>Lead Time (hari)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="supplierBody">
                        <tr>
                            <td><input class="input input--sm" type="text" placeholder="Nama supplier"></td>
                            <td><input class="input input--sm" type="number" min="0" placeholder="0"></td>
                            <td><input class="input input--sm" type="number" min="0" placeholder="0"></td>
                            <td>
                                <select class="select">
                                    <option>Aktif</option>
                                    <option>Nonaktif</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="panel__body">
                <button type="button" class="btn btn--light" id="addSupplier">
                    <i data-lucide="plus" aria-hidden="true"></i><span>Tambah Supplier</span>
                </button>
            </div>
        </section>

        {{-- 5. Catatan --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">5</span>Catatan</h3></div>
            <div class="panel__body form-grid">
                <div class="field field--full">
                    <label class="field__label" for="itemNotes">Catatan Tambahan</label>
                    <textarea class="input textarea" id="itemNotes" rows="2" placeholder="Contoh: khusus untuk varian premium, tidak dipakai varian reguler."></textarea>
                </div>
            </div>
        </section>

        {{-- Aksi --}}
        <div class="form-actions">
            <a href="{{ route('master-barang') }}" class="btn btn--light">Batal</a>
            <button type="button" class="btn btn--light" data-item-action="draft"><i data-lucide="save" aria-hidden="true"></i><span>Simpan Draft</span></button>
            <button type="button" class="btn btn--primary" data-item-action="submit"><i data-lucide="send" aria-hidden="true"></i><span>Simpan &amp; Aktifkan</span></button>
        </div>
    </div>

    {{-- ===== KOLOM KANAN ===== --}}
    <aside class="form-side">
        <section class="panel panel--fit">
            <div class="panel__head"><h3>Ringkasan Barang</h3></div>
            <dl class="summary">
                <div><dt>Kode</dt><dd>Otomatis</dd></div>
                <div><dt>Nama</dt><dd id="sumName">-</dd></div>
                <div><dt>Kategori</dt><dd id="sumCategory">-</dd></div>
                <div><dt>Satuan</dt><dd id="sumUom">-</dd></div>
                <div><dt>Gudang</dt><dd id="sumWarehouse">-</dd></div>
                <div><dt>Jumlah Atribut</dt><dd id="sumSpecs">0</dd></div>
                <div><dt>Jumlah Supplier</dt><dd id="sumSuppliers">0</dd></div>
                <div><dt>Status</dt><dd><span class="badge badge--ok">Aktif</span></dd></div>
            </dl>
        </section>

        <section class="panel panel--fit">
            <div class="panel__head"><h3>Alur Penyimpanan</h3></div>
            <ol class="flow">
                <li class="flow__item is-current">
                    <span class="flow__dot">1</span>
                    <div><strong>Diisi oleh</strong><small>Admin / R&amp;D / Gudang</small></div>
                </li>
                <li class="flow__item">
                    <span class="flow__dot">2</span>
                    <div><strong>Review R&amp;D</strong><small>Untuk kategori RM &amp; PM</small></div>
                </li>
                <li class="flow__item">
                    <span class="flow__dot">3</span>
                    <div><strong>Review QC</strong><small>Spesifikasi &amp; standar</small></div>
                </li>
                <li class="flow__item">
                    <span class="flow__dot">4</span>
                    <div><strong>Aktif &amp; Siap Dipakai</strong><small>Muncul di Plan, BOM, Trial</small></div>
                </li>
            </ol>
        </section>
    </aside>

</form>

@endsection