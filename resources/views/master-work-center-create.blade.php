@extends('layouts.app')

@section('page_title', 'Tambah Work Center')
@section('breadcrumb_parent', 'Master Work Center')

@section('content')

@php
    $categories = [
        'pouch'       => 'Produksi Pouch (Wet Food 85g)',
        'kaleng'      => 'Produksi Kaleng (Wet Food 400g)',
        'kitchen'     => 'Preparation / Dapur (Cooking & Mixing)',
        'sterilisasi' => 'Sterilisasi Termal (Retort / Autoclave)',
        'packaging'   => 'Secondary Packaging & Boxing',
    ];
@endphp

<section class="page-head">
    <div>
        <h2>Tambah Work Center Baru</h2>
        <p>Daftarkan lini produksi atau mesin baru. Tanda <span class="req">*</span> wajib diisi.</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('master.work-center') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
    </div>
</section>

<form id="wcForm" class="form-layout" novalidate>

    {{-- ===== KOLOM KIRI ===== --}}
    <div class="form-main">

        {{-- 1. Identitas --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">1</span>Identitas Work Center</h3></div>
            <div class="panel__body form-grid">
                <div class="field">
                    <label class="field__label" for="wcCode">Kode Work Center</label>
                    <input class="input" id="wcCode" type="text" value="Otomatis saat disimpan" readonly>
                    <small class="field__hint">Format otomatis: [AREA]-[NO]. Contoh: WC-POUCH-03.</small>
                </div>
                <div class="field">
                    <label class="field__label" for="wcStatus">Status Awal <span class="req">*</span></label>
                    <select class="select" id="wcStatus" required>
                        <option value="aktif">Aktif (Ready for Trial)</option>
                        <option value="maintenance">Dalam Pemeliharaan (Maintenance)</option>
                    </select>
                </div>
                <div class="field field--full">
                    <label class="field__label" for="wcName">Nama Work Center / Mesin <span class="req">*</span></label>
                    <input class="input" id="wcName" type="text" placeholder="Contoh: Line Pouch 3 (Filling & Sealing)" required>
                </div>
                <div class="field">
                    <label class="field__label" for="wcCategory">Kategori / Area Pabrik <span class="req">*</span></label>
                    <select class="select" id="wcCategory" required>
                        <option value="">Pilih kategori area</option>
                        @foreach ($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="wcPic">Penanggung Jawab (PIC) <span class="req">*</span></label>
                    <input class="input" id="wcPic" type="text" placeholder="Nama Supervisor / Leader" required>
                </div>
            </div>
        </section>

        {{-- 2. Kapasitas & Operasional --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">2</span>Kapasitas &amp; Biaya Overhead</h3></div>
            <div class="panel__body form-grid">
                <div class="field">
                    <label class="field__label" for="wcCapacity">Kapasitas Standar <span class="req">*</span></label>
                    <input class="input" id="wcCapacity" type="text" placeholder="Contoh: 3500 Pouch / Jam" required>
                </div>
                <div class="field">
                    <label class="field__label" for="wcOverhead">Tarif Overhead / Jam (IDR)</label>
                    <input class="input" id="wcOverhead" type="text" value="150.000">
                    <small class="field__hint">Digunakan untuk kalkulasi biaya trial produksi.</small>
                </div>
            </div>
        </section>

        {{-- 3. Catatan --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">3</span>Deskripsi &amp; Catatan</h3></div>
            <div class="panel__body form-grid">
                <div class="field field--full">
                    <label class="field__label" for="wcNotes">Catatan Teknis Mesin</label>
                    <textarea class="input textarea" id="wcNotes" rows="3" placeholder="Jelaskan spesifikasi detail, jenis kemasan yang didukung, atau batasan operasional..."></textarea>
                </div>
            </div>
        </section>

        {{-- Aksi --}}
        <div class="form-actions">
            <a href="{{ route('master.work-center') }}" class="btn btn--light">Batal</a>
            <button type="button" class="btn btn--primary" id="saveWcBtn"><i data-lucide="save" aria-hidden="true"></i><span>Simpan Work Center</span></button>
        </div>
    </div>

    {{-- ===== KOLOM KANAN ===== --}}
    <aside class="form-side">
        <section class="panel panel--fit">
            <div class="panel__head"><h3>Ringkasan Work Center</h3></div>
            <dl class="summary">
                <div><dt>Kode</dt><dd>Otomatis</dd></div>
                <div><dt>Nama</dt><dd id="sumName">-</dd></div>
                <div><dt>Area</dt><dd id="sumCategory">-</dd></div>
                <div><dt>Kapasitas</dt><dd id="sumCapacity">-</dd></div>
                <div><dt>Status</dt><dd><span class="badge badge--ok">Aktif</span></dd></div>
            </dl>
        </section>

        <section class="panel panel--fit">
            <div class="panel__head"><h3>Alur Integrasi</h3></div>
            <ol class="flow">
                <li class="flow__item is-current">
                    <span class="flow__dot">1</span>
                    <div><strong>Registrasi WC</strong><small>Oleh Engineering / Produksi</small></div>
                </li>
                <li class="flow__item">
                    <span class="flow__dot">2</span>
                    <div><strong>Penetapan Line</strong><small>Dipilih saat Production Plan & Trial</small></div>
                </li>
                <li class="flow__item">
                    <span class="flow__dot">3</span>
                    <div><strong>Eksekusi Trial</strong><small>Pencatatan parameter & output</small></div>
                </li>
            </ol>
        </section>
    </aside>

</form>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('wcForm');
    if (!form) return;

    var nameInput = document.getElementById('wcName');
    var catSelect = document.getElementById('wcCategory');
    var capInput = document.getElementById('wcCapacity');

    var sumName = document.getElementById('sumName');
    var sumCategory = document.getElementById('sumCategory');
    var sumCapacity = document.getElementById('sumCapacity');

    nameInput.addEventListener('input', function() { sumName.textContent = this.value || '-'; });
    catSelect.addEventListener('change', function() { sumCategory.textContent = this.options[this.selectedIndex].text || '-'; });
    capInput.addEventListener('input', function() { sumCapacity.textContent = this.value || '-'; });

    document.getElementById('saveWcBtn').addEventListener('click', function() {
        alert('Work Center berhasil disimpan!');
        window.location.href = "{{ route('master.work-center') }}";
    });
});
</script>
@endsection