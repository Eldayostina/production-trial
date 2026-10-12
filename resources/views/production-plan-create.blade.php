@extends('layouts.app')

@section('page_title', 'Buat Production Plan')
@section('breadcrumb_parent', 'Production Plan')

@section('content')

@php
    // DATA DUMMY - nanti diganti data dari database (Master Barang & BOM)
        $products = [
        'P01' => [
            'name' => 'Life Cat Pouch Tuna & Chicken 85 g', 'code' => 'FG-LC-P01', 'bom' => 'BOM-LC-P01 Rev. 02', 'uom' => 'pouch',
            'materials' => [
                ['code' => 'RM-1001', 'name' => 'Daging Tuna Cooked Flake',      'per_unit' => 0.030,  'uom' => 'kg',   'stock' => 120,   'warehouse' => 'Cold Storage'],
                ['code' => 'RM-1005', 'name' => 'Daging Ayam Giling',            'per_unit' => 0.020,  'uom' => 'kg',   'stock' => 300,   'warehouse' => 'Cold Storage'],
                ['code' => 'RM-1010', 'name' => 'Hati Ayam',                     'per_unit' => 0.008,  'uom' => 'kg',   'stock' => 150,   'warehouse' => 'Cold Storage'],
                ['code' => 'RM-2001', 'name' => 'Tepung Tapioka',                'per_unit' => 0.003,  'uom' => 'kg',   'stock' => 400,   'warehouse' => 'Gudang Bahan Kering'],
                ['code' => 'RM-2010', 'name' => 'Guar Gum',                      'per_unit' => 0.0008, 'uom' => 'kg',   'stock' => 6,     'warehouse' => 'Gudang Bahan Kering'],
                ['code' => 'RM-2011', 'name' => 'Karagenan',                     'per_unit' => 0.0005, 'uom' => 'kg',   'stock' => 4,     'warehouse' => 'Gudang Bahan Kering'],
                ['code' => 'RM-2020', 'name' => 'Taurine',                       'per_unit' => 0.0001, 'uom' => 'kg',   'stock' => 2,     'warehouse' => 'Gudang Bahan Kering'],
                ['code' => 'RM-2021', 'name' => 'Premix Vitamin & Mineral Kucing','per_unit' => 0.0006, 'uom' => 'kg',  'stock' => 8,     'warehouse' => 'Gudang Bahan Kering'],
                ['code' => 'RM-2030', 'name' => 'Minyak Ikan',                   'per_unit' => 0.001,  'uom' => 'kg',   'stock' => 60,    'warehouse' => 'Gudang Bahan Penolong'],
                ['code' => 'RM-2040', 'name' => 'Garam',                         'per_unit' => 0.0004, 'uom' => 'kg',   'stock' => 50,    'warehouse' => 'Gudang Bahan Kering'],
                ['code' => 'RM-2050', 'name' => 'Perisa Palatant Tuna',          'per_unit' => 0.001,  'uom' => 'kg',   'stock' => 25,    'warehouse' => 'Gudang Bahan Penolong'],
                ['code' => 'PK-3001', 'name' => 'Retort Pouch 85 g Life Cat Tuna','per_unit' => 1,     'uom' => 'pcs',  'stock' => 60000, 'warehouse' => 'Gudang Kemasan'],
                ['code' => 'PK-3101', 'name' => 'Karton Master isi 24 Pouch',    'per_unit' => 0.0417, 'uom' => 'pcs',  'stock' => 2500,  'warehouse' => 'Gudang Kemasan'],
            ],
        ],
        'M01' => [
            'name' => 'Life Cat Pouch Mackerel 85 g', 'code' => 'FG-LC-M01', 'bom' => 'BOM-LC-M01 Rev. 01', 'uom' => 'pouch',
            'materials' => [
                ['code' => 'RM-1020', 'name' => 'Daging Ikan Makarel',           'per_unit' => 0.040,  'uom' => 'kg',   'stock' => 500,   'warehouse' => 'Cold Storage'],
                ['code' => 'RM-1005', 'name' => 'Daging Ayam Giling',            'per_unit' => 0.010,  'uom' => 'kg',   'stock' => 300,   'warehouse' => 'Cold Storage'],
                ['code' => 'RM-2001', 'name' => 'Tepung Tapioka',                'per_unit' => 0.003,  'uom' => 'kg',   'stock' => 400,   'warehouse' => 'Gudang Bahan Kering'],
                ['code' => 'RM-2010', 'name' => 'Guar Gum',                      'per_unit' => 0.0008, 'uom' => 'kg',   'stock' => 6,     'warehouse' => 'Gudang Bahan Kering'],
                ['code' => 'RM-2020', 'name' => 'Taurine',                       'per_unit' => 0.0001, 'uom' => 'kg',   'stock' => 2,     'warehouse' => 'Gudang Bahan Kering'],
                ['code' => 'RM-2021', 'name' => 'Premix Vitamin & Mineral Kucing','per_unit' => 0.0006, 'uom' => 'kg',  'stock' => 8,     'warehouse' => 'Gudang Bahan Kering'],
                ['code' => 'RM-2030', 'name' => 'Minyak Ikan',                   'per_unit' => 0.001,  'uom' => 'kg',   'stock' => 60,    'warehouse' => 'Gudang Bahan Penolong'],
                ['code' => 'RM-2051', 'name' => 'Perisa Palatant Makarel',       'per_unit' => 0.001,  'uom' => 'kg',   'stock' => 20,    'warehouse' => 'Gudang Bahan Penolong'],
                ['code' => 'PK-3002', 'name' => 'Retort Pouch 85 g Life Cat Mackerel','per_unit' => 1, 'uom' => 'pcs',  'stock' => 45000, 'warehouse' => 'Gudang Kemasan'],
                ['code' => 'PK-3101', 'name' => 'Karton Master isi 24 Pouch',    'per_unit' => 0.0417, 'uom' => 'pcs',  'stock' => 2500,  'warehouse' => 'Gudang Kemasan'],
            ],
        ],
        'K01' => [
            'name' => 'Life Cat Kaleng Tuna 400 g', 'code' => 'FG-LC-K01', 'bom' => 'BOM-LC-K01 Rev. 03', 'uom' => 'kaleng',
            'materials' => [
                ['code' => 'RM-1002', 'name' => 'Daging Tuna Chunk',             'per_unit' => 0.180,  'uom' => 'kg',   'stock' => 900,   'warehouse' => 'Cold Storage'],
                ['code' => 'RM-1005', 'name' => 'Daging Ayam Giling',            'per_unit' => 0.080,  'uom' => 'kg',   'stock' => 300,   'warehouse' => 'Cold Storage'],
                ['code' => 'RM-2001', 'name' => 'Tepung Tapioka',                'per_unit' => 0.010,  'uom' => 'kg',   'stock' => 400,   'warehouse' => 'Gudang Bahan Kering'],
                ['code' => 'RM-2010', 'name' => 'Guar Gum',                      'per_unit' => 0.003,  'uom' => 'kg',   'stock' => 6,     'warehouse' => 'Gudang Bahan Kering'],
                ['code' => 'RM-2020', 'name' => 'Taurine',                       'per_unit' => 0.0004, 'uom' => 'kg',   'stock' => 2,     'warehouse' => 'Gudang Bahan Kering'],
                ['code' => 'RM-2021', 'name' => 'Premix Vitamin & Mineral Kucing','per_unit' => 0.0025, 'uom' => 'kg',  'stock' => 8,     'warehouse' => 'Gudang Bahan Kering'],
                ['code' => 'RM-2030', 'name' => 'Minyak Ikan',                   'per_unit' => 0.004,  'uom' => 'kg',   'stock' => 60,    'warehouse' => 'Gudang Bahan Penolong'],
                ['code' => 'PK-3201', 'name' => 'Kaleng 401 Lacquer Putih',      'per_unit' => 1,      'uom' => 'pcs',  'stock' => 30000, 'warehouse' => 'Gudang Kemasan'],
                ['code' => 'PK-3202', 'name' => 'Tutup Kaleng Easy Open',        'per_unit' => 1,      'uom' => 'pcs',  'stock' => 30000, 'warehouse' => 'Gudang Kemasan'],
                ['code' => 'PK-3210', 'name' => 'Label Kaleng Life Cat Tuna',    'per_unit' => 1,      'uom' => 'pcs',  'stock' => 25000, 'warehouse' => 'Gudang Kemasan'],
                ['code' => 'PK-3102', 'name' => 'Karton Master isi 24 Kaleng',   'per_unit' => 0.0417, 'uom' => 'pcs',  'stock' => 1500,  'warehouse' => 'Gudang Kemasan'],
            ],
        ],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>Buat Production Plan</h2>
        <p>Isi data rencana produksi untuk kegiatan trial. Tanda <span class="req">*</span> wajib diisi.</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('production-plan') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
    </div>
</section>

<form id="planForm" class="form-layout" novalidate data-products="{{ json_encode($products) }}">

    {{-- ===== KOLOM KIRI: FORM ===== --}}
    <div class="form-main">

        {{-- 1. Informasi umum --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">1</span>Informasi Umum</h3></div>
            <div class="panel__body form-grid">
                <div class="field">
                    <label class="field__label" for="planNo">No. Plan</label>
                    <input class="input" id="planNo" type="text" value="PP-2026-0019" readonly>
                    <small class="field__hint">Dibuat otomatis oleh sistem.</small>
                </div>
                <div class="field">
                    <label class="field__label" for="planDate">Tanggal Plan <span class="req">*</span></label>
                    <input class="input" id="planDate" type="date" value="{{ now()->format('Y-m-d') }}" required>
                </div>
                <div class="field">
                    <label class="field__label" for="trialType">Tipe Trial <span class="req">*</span></label>
                    <select class="select" id="trialType" required>
                        <option value="">Pilih tipe trial</option>
                        <option>Trial Formula Baru</option>
                        <option>Trial Supplier Bahan Baku Baru</option>
                        <option>Trial Parameter Retort (Sterilisasi)</option>
                        <option>Trial Kemasan Baru</option>
                        <option>Trial Varian Rasa Baru</option>
                        <option>Trial Parameter Mesin Filling / Sealing</option>
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="priority">Prioritas <span class="req">*</span></label>
                    <select class="select" id="priority" required>
                        <option>Normal</option>
                        <option>Tinggi</option>
                        <option>Urgent</option>
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="department">Departemen Pemohon <span class="req">*</span></label>
                    <select class="select" id="department" required>
                        <option value="">Pilih departemen</option>
                        <option>Production</option>
                        <option>R&amp;D</option>
                        <option>Engineering</option>
                        <option>Quality Control</option>
                        <option>Purchasing</option>
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="refDoc">No. Referensi</label>
                    <input class="input" id="refDoc" type="text" placeholder="Contoh: ECN-2026-014 / Permintaan Trial">
                </div>
                <div class="field field--full">
                    <label class="field__label" for="purpose">Tujuan Trial <span class="req">*</span></label>
                    <textarea class="input textarea" id="purpose" rows="3" placeholder="Jelaskan tujuan dan latar belakang trial ini..." required></textarea>
                </div>
            </div>
        </section>

        {{-- 2. Produk & target --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">2</span>Produk &amp; Target Produksi</h3></div>
            <div class="panel__body form-grid">
                <div class="field field--full">
                    <label class="field__label" for="product">Produk <span class="req">*</span></label>
                    <select class="select" id="product" required>
                        <option value="">Pilih produk</option>
                        @foreach ($products as $key => $p)
                            <option value="{{ $key }}">{{ $p['code'] }} - {{ $p['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="productCode">Kode Produk</label>
                    <input class="input" id="productCode" type="text" value="-" readonly>
                </div>
                <div class="field">
                    <label class="field__label" for="bom">Versi BOM</label>
                    <input class="input" id="bom" type="text" value="-" readonly>
                </div>
                <div class="field">
                    <label class="field__label" for="qty">Qty Target <span class="req">*</span></label>
                    <div class="input-group">
                        <input class="input" id="qty" type="number" min="1" step="1" placeholder="0" required>
                        <span class="input-group__addon" id="uom">pcs</span>
                    </div>
                </div>
                <div class="field">
                    <label class="field__label" for="line">Work Center / Line <span class="req">*</span></label>
                    <select class="select" id="line" required>
                        <option value="">Pilih line</option>
                        <option>Line Pouch 1</option>
                        <option>Line Pouch 2</option>
                        <option>Line Kaleng 1</option>
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="shift">Shift <span class="req">*</span></label>
                    <select class="select" id="shift" required>
                        <option value="">Pilih shift</option>
                        <option>Shift 1 (07.00 - 15.00)</option>
                        <option>Shift 2 (15.00 - 23.00)</option>
                        <option>Shift 3 (23.00 - 07.00)</option>
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="pic">Penanggung Jawab (PIC) <span class="req">*</span></label>
                    <select class="select" id="pic" required>
                        <option value="">Pilih PIC</option>
                        <option>Budi Santoso - Supervisor Produksi</option>
                        <option>Rina Wulandari - Engineer Proses</option>
                        <option>Agus Pratama - Engineer Mold</option>
                    </select>
                </div>
            </div>
        </section>

        {{-- 3. Jadwal --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">3</span>Jadwal Pelaksanaan</h3></div>
            <div class="panel__body form-grid form-grid--3">
                <div class="field">
                    <label class="field__label" for="startDate">Tanggal Mulai <span class="req">*</span></label>
                    <input class="input" id="startDate" type="date" required>
                </div>
                <div class="field">
                    <label class="field__label" for="endDate">Tanggal Selesai <span class="req">*</span></label>
                    <input class="input" id="endDate" type="date" required>
                </div>
                <div class="field">
                    <label class="field__label" for="duration">Durasi</label>
                    <input class="input" id="duration" type="text" value="-" readonly>
                </div>
            </div>
        </section>

        {{-- 4. Kebutuhan material --}}
        <section class="panel panel--fit">
            <div class="panel__head panel__head--row">
                <h3><span class="step-no">4</span>Kebutuhan Material (dari BOM)</h3>
                <span class="hint-inline">Dihitung otomatis: Qty Target x Pemakaian per unit</span>
            </div>

            <div class="alert alert--warn" id="matAlert" hidden>
                <i data-lucide="alert-triangle" aria-hidden="true"></i>
                <p id="matAlertText"></p>
            </div>

            <div class="table-wrap">
                <table class="table" id="matTable" hidden>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Material</th>
                            <th class="table__right">Per Unit</th>
                            <th class="table__right">Kebutuhan</th>
                            <th class="table__right">Stok</th>
                            <th>Gudang</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="matBody"></tbody>
                </table>

                <div class="empty" id="matEmpty">
                    <i data-lucide="list-tree" aria-hidden="true"></i>
                    <p>Pilih produk dan isi Qty Target untuk melihat kebutuhan material.</p>
                </div>
            </div>
        </section>

        {{-- 5. Kriteria & catatan --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">5</span>Kriteria Keberhasilan &amp; Catatan</h3></div>
            <div class="panel__body form-grid">
                <div class="field field--full">
                    <label class="field__label" for="criteria">Kriteria Keberhasilan Trial <span class="req">*</span></label>
                    <textarea class="input textarea" id="criteria" rows="3" placeholder="Contoh: Reject rate di bawah 2%, dimensi sesuai drawing, cycle time maksimal 45 detik." required></textarea>
                </div>
                <div class="field field--full">
                    <label class="field__label" for="notes">Catatan Tambahan</label>
                    <textarea class="input textarea" id="notes" rows="2" placeholder="Instruksi khusus untuk operator, QC, atau gudang..."></textarea>
                </div>
                <div class="field field--full">
                    <label class="field__label" for="attachment">Lampiran</label>
                    <input class="input input--file" id="attachment" type="file" multiple>
                    <small class="field__hint">Drawing, spesifikasi, atau dokumen pendukung (PDF, JPG, PNG, XLSX).</small>
                </div>
            </div>
        </section>

        {{-- Aksi --}}
        <div class="form-actions">
            <a href="{{ route('production-plan') }}" class="btn btn--light">Batal</a>
            <button type="button" class="btn btn--light" data-action="draft"><i data-lucide="save" aria-hidden="true"></i><span>Simpan Draft</span></button>
            <button type="button" class="btn btn--primary" data-action="submit"><i data-lucide="send" aria-hidden="true"></i><span>Ajukan Approval</span></button>
        </div>
    </div>

    {{-- ===== KOLOM KANAN: RINGKASAN ===== --}}
    <aside class="form-side">
        <section class="panel panel--fit">
            <div class="panel__head"><h3>Ringkasan Plan</h3></div>
            <dl class="summary">
                <div><dt>No. Plan</dt><dd>PP-2026-0019</dd></div>
                <div><dt>Produk</dt><dd id="sumProduct">-</dd></div>
                <div><dt>Target</dt><dd id="sumQty">-</dd></div>
                <div><dt>Work Center</dt><dd id="sumLine">-</dd></div>
                <div><dt>Periode</dt><dd id="sumPeriod">-</dd></div>
                <div><dt>Material</dt><dd id="sumMaterial">-</dd></div>
                <div><dt>Status</dt><dd><span class="badge badge--draft">Draft</span></dd></div>
            </dl>
        </section>

        <section class="panel panel--fit">
            <div class="panel__head"><h3>Alur Approval</h3></div>
            <ol class="flow">
                <li class="flow__item is-current">
                    <span class="flow__dot">1</span>
                    <div><strong>Dibuat oleh</strong><small>Admin Produksi</small></div>
                </li>
                <li class="flow__item">
                    <span class="flow__dot">2</span>
                    <div><strong>Review Quality Control</strong><small>Supervisor QC</small></div>
                </li>
                <li class="flow__item">
                    <span class="flow__dot">3</span>
                    <div><strong>Persetujuan</strong><small>Manager Produksi</small></div>
                </li>
                <li class="flow__item">
                    <span class="flow__dot">4</span>
                    <div><strong>Siap Stock Transfer</strong><small>Gudang &amp; Production Trial</small></div>
                </li>
            </ol>
        </section>
    </aside>

</form>

@endsection