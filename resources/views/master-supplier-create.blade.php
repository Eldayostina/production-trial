@extends('layouts.app')

@section('page_title', 'Tambah Supplier')
@section('breadcrumb_parent', 'Master Supplier')

@section('content')

@php
    $categories = [
        'RM' => 'Raw Material - Bahan Baku Utama (Tuna, Ayam, Daging)',
        'PM' => 'Packaging Material - Bahan Kemasan (Pouch, Kaleng, Karton)',
        'CN' => 'Consumable / Bahan Penolong (Minyak, Bumbu, Vitamin)',
    ];
@endphp

<section class="page-head">
    <div>
        <h2>Tambah Supplier Baru</h2>
        <p>Daftarkan vendor atau pemasok baru ke dalam sistem ERP. Tanda <span class="req">*</span> wajib diisi.</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('master.supplier') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
    </div>
</section>

<form id="supplierForm" class="form-layout" novalidate>

    {{-- ===== KOLOM KIRI ===== --}}
    <div class="form-main">

        {{-- 1. Identitas Supplier --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">1</span>Informasi Vendor &amp; Kategori</h3></div>
            <div class="panel__body form-grid">
                <div class="field">
                    <label class="field__label" for="supCode">Kode Vendor</label>
                    <input class="input" id="supCode" type="text" value="Otomatis saat disimpan" readonly>
                    <small class="field__hint">Dibuat otomatis oleh sistem ERP.</small>
                </div>
                <div class="field">
                    <label class="field__label" for="supCategory">Kategori Pasokan <span class="req">*</span></label>
                    <select class="select" id="supCategory" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field field--full">
                    <label class="field__label" for="supName">Nama Perusahaan / Supplier <span class="req">*</span></label>
                    <input class="input" id="supName" type="text" placeholder="Contoh: PT Samudra Tuna Jaya" required>
                </div>
                <div class="field">
                    <label class="field__label" for="supLeadTime">Rata-rata Lead Time (Hari) <span class="req">*</span></label>
                    <input class="input" id="supLeadTime" type="number" min="1" placeholder="Mis. 7" required>
                </div>
                <div class="field">
                    <label class="field__label" for="supStatus">Status Kerjasama</label>
                    <select class="select" id="supStatus">
                        <option value="aktif">Aktif (Approved Vendor)</option>
                        <option value="evaluasi">Dalam Evaluasi QC</option>
                    </select>
                </div>
            </div>
        </section>

        {{-- 2. Kontak & Alamat --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">2</span>Kontak Person &amp; Alamat</h3></div>
            <div class="panel__body form-grid">
                <div class="field">
                    <label class="field__label" for="supPic">Nama Contact Person (PIC) <span class="req">*</span></label>
                    <input class="input" id="supPic" type="text" placeholder="Mis. Bpk. Hendra (Sales Manager)" required>
                </div>
                <div class="field">
                    <label class="field__label" for="supPhone">Nomor Telepon / WhatsApp <span class="req">*</span></label>
                    <input class="input" id="supPhone" type="text" placeholder="Mis. 0812-3456-7890" required>
                </div>
                <div class="field field--full">
                    <label class="field__label" for="supAddress">Alamat Kantor / Pabrik Supplier <span class="req">*</span></label>
                    <textarea class="input textarea" id="supAddress" rows="2" placeholder="Masukkan alamat lengkap lokasi gudang/pabrik vendor..." required></textarea>
                </div>
            </div>
        </section>

        {{-- Aksi --}}
        <div class="form-actions">
            <a href="{{ route('master.supplier') }}" class="btn btn--light">Batal</a>
            <button type="button" class="btn btn--primary" id="saveSupplierBtn"><i data-lucide="save" aria-hidden="true"></i><span>Simpan Supplier</span></button>
        </div>
    </div>

    {{-- ===== KOLOM KANAN ===== --}}
    <aside class="form-side">
        <section class="panel panel--fit">
            <div class="panel__head"><h3>Ringkasan Supplier</h3></div>
            <dl class="summary">
                <div><dt>Kode</dt><dd>Otomatis</dd></div>
                <div><dt>Nama</dt><dd id="sumName">-</dd></div>
                <div><dt>Kategori</dt><dd id="sumCategory">-</dd></div>
                <div><dt>Lead Time</dt><dd id="sumLeadTime">-</dd></div>
                <div><dt>Status</dt><dd><span class="badge badge--ok">Aktif</span></dd></div>
            </dl>
        </section>

        <section class="panel panel--fit">
            <div class="panel__head"><h3>Alur Approved Vendor</h3></div>
            <ol class="flow">
                <li class="flow__item is-current">
                    <span class="flow__dot">1</span>
                    <div><strong>Registrasi Vendor</strong><small>Oleh Purchasing / Procurement</small></div>
                </li>
                <li class="flow__item">
                    <span class="flow__dot">2</span>
                    <div><strong>Audit &amp; Quality Approval</strong><small>Verifikasi spesifikasi oleh QC &amp; R&amp;D</small></div>
                </li>
                <li class="flow__item">
                    <span class="flow__dot">3</span>
                    <div><strong>Siap PO &amp; Production Trial</strong><small>Digunakan dalam pembelian bahan baku</small></div>
                </li>
            </ol>
        </section>
    </aside>

</form>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('supplierForm');
    if (!form) return;

    var nameInput = document.getElementById('supName');
    var catSelect = document.getElementById('supCategory');
    var leadInput = document.getElementById('supLeadTime');

    var sumName = document.getElementById('sumName');
    var sumCategory = document.getElementById('sumCategory');
    var sumLeadTime = document.getElementById('sumLeadTime');

    nameInput.addEventListener('input', function() { sumName.textContent = this.value || '-'; });
    catSelect.addEventListener('change', function() { sumCategory.textContent = this.options[this.selectedIndex].text || '-'; });
    leadInput.addEventListener('input', function() { sumLeadTime.textContent = (this.value ? this.value + ' Hari' : '-'); });

    document.getElementById('saveSupplierBtn').addEventListener('click', function() {
        alert('Data Supplier berhasil disimpan!');
        window.location.href = "{{ route('master.supplier') }}";
    });
});
</script>
@endsection