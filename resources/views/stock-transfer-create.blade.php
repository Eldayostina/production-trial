@extends('layouts.app')

@section('page_title', 'Buat Stock Transfer')
@section('breadcrumb_parent', 'Stock Transfer')

@section('content')

@php
    // DATA DUMMY - nanti diganti data dari database
    $plans = [
        'PP-2026-0018' => ['Life Cat Pouch Tuna & Chicken 85 g', 'Line Pouch 1',  '10 Okt - 14 Okt 2026'],
        'PP-2026-0017' => ['Life Cat Pouch Mackerel 85 g',       'Line Pouch 2',  '12 Okt - 15 Okt 2026'],
        'PP-2026-0016' => ['Life Cat Kaleng Tuna 400 g',         'Line Kaleng 1', '06 Okt - 09 Okt 2026'],
        'PP-2026-0015' => ['Life Cat Pouch Tuna & Chicken 85 g', 'Line Pouch 1',  '01 Okt - 07 Okt 2026'],
    ];

    // Gudang dummy
    $warehouses = [
        'Cold Storage', 'Gudang Bahan Kering', 'Gudang Bahan Penolong', 'Gudang Kemasan',
    ];
    $destinations = [
        'Area Produksi Line Pouch 1', 'Area Produksi Line Pouch 2', 'Area Produksi Line Kaleng 1', 'Gudang Transit',
    ];

    // Material dummy per gudang (untuk dipilih user) - nanti dari database
    // [kode, nama, batch, expiry, uom, stok tersedia, gudang asal]
    $materials = [
        ['RM-1001', 'Daging Tuna Cooked Flake',       'LOT-241001', '20 Okt 2026', 'kg',  120,  'Cold Storage'],
        ['RM-1005', 'Daging Ayam Giling',             'LOT-241005', '22 Okt 2026', 'kg',  300,  'Cold Storage'],
        ['RM-1010', 'Hati Ayam',                      'LOT-241010', '25 Okt 2026', 'kg',  150,  'Cold Storage'],
        ['RM-2001', 'Tepung Tapioka',                 'LOT-242001', '15 Des 2026', 'kg',  400,  'Gudang Bahan Kering'],
        ['RM-2010', 'Guar Gum',                       'LOT-242010', '10 Jan 2027', 'kg',  6,    'Gudang Bahan Kering'],
        ['RM-2030', 'Minyak Ikan',                    'LOT-242030', '05 Des 2026', 'kg',  60,   'Gudang Bahan Penolong'],
        ['PK-3001', 'Retort Pouch 85 g Life Cat Tuna','LOT-243001', '30 Jun 2027', 'pcs', 60000,'Gudang Kemasan'],
        ['PK-3101', 'Karton Master isi 24 Pouch',     'LOT-243101', '30 Jun 2027', 'pcs', 2500, 'Gudang Kemasan'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>Buat Stock Transfer</h2>
        <p>Kirim material dari gudang ke area produksi. Tanda <span class="req">*</span> wajib diisi.</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('stock-transfer') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
    </div>
</section>

<form id="stForm" class="form-layout" novalidate data-materials="{{ json_encode($materials) }}">

    {{-- ===== KOLOM KIRI ===== --}}
    <div class="form-main">

        {{-- 1. Informasi umum --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">1</span>Informasi Umum</h3></div>
            <div class="panel__body form-grid">
                <div class="field">
                    <label class="field__label" for="stNo">No. Transfer</label>
                    <input class="input" id="stNo" type="text" value="Otomatis saat disimpan" readonly>
                    <small class="field__hint">Dibuat otomatis oleh sistem.</small>
                </div>
                <div class="field">
                    <label class="field__label" for="stDate">Tanggal Transfer <span class="req">*</span></label>
                    <input class="input" id="stDate" type="date" value="{{ now()->format('Y-m-d') }}" required>
                </div>
                <div class="field">
                    <label class="field__label" for="stPriority">Prioritas <span class="req">*</span></label>
                    <select class="select" id="stPriority" required>
                        <option>Normal</option>
                        <option>Tinggi</option>
                        <option>Urgent</option>
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="stDept">Departemen Pemohon <span class="req">*</span></label>
                    <select class="select" id="stDept" required>
                        <option value="">Pilih departemen</option>
                        <option>Production</option>
                        <option>R&amp;D</option>
                        <option>Quality Control</option>
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="stRefPlan">Ref. Production Plan <span class="req">*</span></label>
                    <select class="select" id="stRefPlan" required>
                        <option value="">Pilih Production Plan</option>
                        @foreach ($plans as $no => $p)
                            <option value="{{ $no }}">{{ $no }} - {{ $p[0] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="stRefDoc">No. Referensi</label>
                    <input class="input" id="stRefDoc" type="text" placeholder="Contoh: ECN-2026-014 / Permintaan Trial">
                </div>
                <div class="field field--full">
                    <label class="field__label" for="stPurpose">Tujuan Transfer <span class="req">*</span></label>
                    <textarea class="input textarea" id="stPurpose" rows="2" placeholder="Contoh: Pemenuhan material untuk trial PP-2026-0018 batch pertama." required></textarea>
                </div>
            </div>
        </section>

        {{-- 2. Gudang --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">2</span>Gudang Asal &amp; Tujuan</h3></div>
            <div class="panel__body form-grid">
                <div class="field">
                    <label class="field__label" for="stFrom">Gudang Asal <span class="req">*</span></label>
                    <select class="select" id="stFrom" required>
                        <option value="">Pilih gudang asal</option>
                        @foreach ($warehouses as $w)
                            <option>{{ $w }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="stTo">Gudang Tujuan <span class="req">*</span></label>
                    <select class="select" id="stTo" required>
                        <option value="">Pilih gudang tujuan</option>
                        @foreach ($destinations as $d)
                            <option>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="stNeedDate">Dibutuhkan di Area Produksi <span class="req">*</span></label>
                    <input class="input" id="stNeedDate" type="date" required>
                </div>
                <div class="field">
                    <label class="field__label" for="stPic">PIC Gudang <span class="req">*</span></label>
                    <select class="select" id="stPic" required>
                        <option value="">Pilih PIC gudang</option>
                        <option>Joko Susilo - Supervisor Gudang</option>
                        <option>Siti Rahma - Admin Gudang</option>
                        <option>Hendra Gunawan - Operator Gudang</option>
                    </select>
                </div>
            </div>
        </section>

        {{-- 3. Daftar material --}}
        <section class="panel panel--fit">
            <div class="panel__head panel__head--row">
                <h3><span class="step-no">3</span>Daftar Material yang Ditransfer</h3>
                <span class="hint-inline">Batch/lot &amp; expiry wajib untuk traceability</span>
            </div>

            <div class="alert alert--warn" id="stAlert" hidden>
                <i data-lucide="alert-triangle" aria-hidden="true"></i>
                <p id="stAlertText"></p>
            </div>

            <div class="table-wrap">
                <table class="table" id="stTable">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Material</th>
                            <th>Batch / Lot</th>
                            <th>Expiry</th>
                            <th>Satuan</th>
                            <th class="table__right">Stok Tersedia</th>
                            <th class="table__right">Qty Kirim</th>
                            <th class="table__right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="stBody"></tbody>
                </table>
            </div>

            <div class="panel__body">
                <button type="button" class="btn btn--light" id="stAddRow">
                    <i data-lucide="plus" aria-hidden="true"></i><span>Tambah Baris Material</span>
                </button>
            </div>
        </section>

        {{-- 4. Catatan & lampiran --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">4</span>Catatan &amp; Lampiran</h3></div>
            <div class="panel__body form-grid">
                <div class="field field--full">
                    <label class="field__label" for="stNotes">Catatan</label>
                    <textarea class="input textarea" id="stNotes" rows="2" placeholder="Instruksi khusus untuk operator gudang atau produksi..."></textarea>
                </div>
                <div class="field field--full">
                    <label class="field__label" for="stFile">Lampiran</label>
                    <input class="input input--file" id="stFile" type="file" multiple>
                    <small class="field__hint">Dokumen R&amp;D, surat jalan, atau berita acara (PDF, JPG, PNG, XLSX).</small>
                </div>
            </div>
        </section>

        {{-- Aksi --}}
        <div class="form-actions">
            <a href="{{ route('stock-transfer') }}" class="btn btn--light">Batal</a>
            <button type="button" class="btn btn--light" data-st-action="draft"><i data-lucide="save" aria-hidden="true"></i><span>Simpan Draft</span></button>
            <button type="button" class="btn btn--primary" data-st-action="submit"><i data-lucide="send" aria-hidden="true"></i><span>Ajukan Approval</span></button>
        </div>
    </div>

    {{-- ===== KOLOM KANAN ===== --}}
    <aside class="form-side">
        <section class="panel panel--fit">
            <div class="panel__head"><h3>Ringkasan Transfer</h3></div>
            <dl class="summary">
                <div><dt>No. Transfer</dt><dd>Otomatis</dd></div>
                <div><dt>Dari Gudang</dt><dd id="sumFrom">-</dd></div>
                <div><dt>Ke Gudang</dt><dd id="sumTo">-</dd></div>
                <div><dt>Ref. Plan</dt><dd id="sumPlan">-</dd></div>
                <div><dt>Jumlah Item</dt><dd id="sumItem">0</dd></div>
                <div><dt>Total Qty Kirim</dt><dd id="sumQty">-</dd></div>
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
                    <div><strong>Review Gudang Asal</strong><small>Supervisor Gudang</small></div>
                </li>
                <li class="flow__item">
                    <span class="flow__dot">3</span>
                    <div><strong>Persetujuan Manager</strong><small>Manager Produksi</small></div>
                </li>
                <li class="flow__item">
                    <span class="flow__dot">4</span>
                    <div><strong>Dikirim ke Area Produksi</strong><small>Gudang &amp; Production Trial</small></div>
                </li>
                <li class="flow__item">
                    <span class="flow__dot">5</span>
                    <div><strong>Diterima di Area Produksi</strong><small>PIC Produksi</small></div>
                </li>
            </ol>
        </section>
    </aside>

</form>

@endsection