@extends('layouts.app')

@section('page_title', 'Tambah Satuan (UoM)')
@section('breadcrumb_parent', 'Master Satuan')

@section('content')

@php
    $categories = [
        'berat'   => 'Berat / Massa (kg, g, mg)',
        'unit'    => 'Unit / Pcs / Satuan Hitung',
        'volume'  => 'Volume / Cairan (liter, ml)',
        'kemasan' => 'Kemasan Sekunder (karton, box, palet)',
    ];
@endphp

<section class="page-head">
    <div>
        <h2>Tambah Satuan Baru</h2>
        <p>Daftarkan satuan ukuran atau faktor konversi baru. Tanda <span class="req">*</span> wajib diisi.</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('master.uom') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
    </div>
</section>

<form id="uomForm" class="form-layout" novalidate>

    {{-- ===== KOLOM KIRI ===== --}}
    <div class="form-main">

        {{-- 1. Identitas UoM --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">1</span>Identitas Satuan (UoM)</h3></div>
            <div class="panel__body form-grid">
                <div class="field">
                    <label class="field__label" for="uomCode">Kode Satuan <span class="req">*</span></label>
                    <input class="input" id="uomCode" type="text" placeholder="Mis. KG, PCS, BOX" required>
                    <small class="field__hint">Gunakan huruf kapital singkat (maks. 10 karakter).</small>
                </div>
                <div class="field">
                    <label class="field__label" for="uomCategory">Kategori Satuan <span class="req">*</span></label>
                    <select class="select" id="uomCategory" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field field--full">
                    <label class="field__label" for="uomName">Nama Lengkap Satuan <span class="req">*</span></label>
                    <input class="input" id="uomName" type="text" placeholder="Contoh: Kilogram atau Karton Box 24 Pcs" required>
                </div>
            </div>
        </section>

        {{-- 2. Konversi --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">2</span>Aturan Konversi (Opsional)</h3></div>
            <div class="panel__body form-grid">
                <div class="field">
                    <label class="field__label" for="baseUom">Satuan Dasar Acuan</label>
                    <select class="select" id="baseUom">
                        <option value="">Tidak ada (Satuan Dasar)</option>
                        <option value="KG">KG (Kilogram)</option>
                        <option value="PCS">PCS (Pieces)</option>
                        <option value="LITER">LITER</option>
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="convFactor">Faktor Pengali (Multiplier)</label>
                    <input class="input" id="convFactor" type="number" step="0.0001" placeholder="Contoh: 1000 atau 24">
                    <small class="field__hint">Contoh: 1 Karton = 24 Pcs, maka faktor pengali adalah 24.</small>
                </div>
            </div>
        </section>

        {{-- Aksi --}}
        <div class="form-actions">
            <a href="{{ route('master.uom') }}" class="btn btn--light">Batal</a>
            <button type="button" class="btn btn--primary" id="saveUomBtn"><i data-lucide="save" aria-hidden="true"></i><span>Simpan Satuan</span></button>
        </div>
    </div>

    {{-- ===== KOLOM KANAN ===== --}}
    <aside class="form-side">
        <section class="panel panel--fit">
            <div class="panel__head"><h3>Ringkasan Satuan</h3></div>
            <dl class="summary">
                <div><dt>Kode</dt><dd id="sumCode">-</dd></div>
                <div><dt>Nama</dt><dd id="sumName">-</dd></div>
                <div><dt>Kategori</dt><dd id="sumCategory">-</dd></div>
                <div><dt>Status</dt><dd><span class="badge badge--ok">Aktif</span></dd></div>
            </dl>
        </section>

        <section class="panel panel--fit">
            <div class="panel__head"><h3>Panduan Penggunaan</h3></div>
            <ol class="flow">
                <li class="flow__item is-current">
                    <span class="flow__dot">1</span>
                    <div><strong>Satuan Dasar</strong><small>Digunakan untuk stok mutlak (KG, PCS)</small></div>
                </li>
                <li class="flow__item">
                    <span class="flow__dot">2</span>
                    <div><strong>Satuan Turunan</strong><small>Untuk pembelian atau pengemasan</small></div>
                </li>
            </ol>
        </section>
    </aside>

</form>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('uomForm');
    if (!form) return;

    var codeInput = document.getElementById('uomCode');
    var nameInput = document.getElementById('uomName');
    var catSelect = document.getElementById('uomCategory');

    var sumCode = document.getElementById('sumCode');
    var sumName = document.getElementById('sumName');
    var sumCategory = document.getElementById('sumCategory');

    codeInput.addEventListener('input', function() { sumCode.textContent = this.value.toUpperCase() || '-'; });
    nameInput.addEventListener('input', function() { sumName.textContent = this.value || '-'; });
    catSelect.addEventListener('change', function() { sumCategory.textContent = this.options[this.selectedIndex].text || '-'; });

    document.getElementById('saveUomBtn').addEventListener('click', function() {
        alert('Satuan (UoM) berhasil disimpan!');
        window.location.href = "{{ route('master.uom') }}";
    });
});
</script>
@endsection