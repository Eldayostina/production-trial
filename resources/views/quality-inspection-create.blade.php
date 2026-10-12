@extends('layouts.app')

@section('page_title', 'Buat Quality Inspection (Lot QC)')
@section('breadcrumb_parent', 'Quality Inspection')

@section('content')

@php
    $inspectionTypes = [
        'incoming'  => 'Incoming Inspection (Penerimaan Bahan Baku / Kemasan)',
        'inprocess' => 'In-Process Inspection (Pengujian Saat Proses Masak / Retort)',
        'final'     => 'Final Inspection (Pengujian Produk Jadi Sebelum Gudang)',
        'release'   => 'Release Inspection (Uji Mikrobiologi & Inkubasi 7 Hari)',
    ];

    $materials = [
        'RM-1001' => 'Daging Tuna Cooked Flake (Raw Material)',
        'RM-1005' => 'Daging Ayam Giling (Raw Material)',
        'PK-3001' => 'Retort Pouch 85 g Life Cat Tuna (Packaging)',
        'FG-LC-P01' => 'Life Cat Pouch Tuna & Chicken 85 g (Finished Good)',
        'FG-LC-K01' => 'Life Cat Kaleng Tuna 400 g (Finished Good)',
    ];
@endphp

<section class="page-head">
    <div>
        <h2>Buat Lot Inspeksi Kualitas Baru</h2>
        <p>Standar SAP QM: Pencatatan parameter pengujian dan spesifikasi mutu produk wet food</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('quality-inspection') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
    </div>
</section>

<form id="qiForm" class="form-layout" novalidate>

    {{-- ===== KOLOM KIRI ===== --}}
    <div class="form-main">

        {{-- 1. Header Lot Inspeksi --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">1</span>Header Lot Inspeksi (SAP QM)</h3></div>
            <div class="panel__body form-grid">
                <div class="field">
                    <label class="field__label" for="qiNo">No. Inspeksi / Lot</label>
                    <input class="input" id="qiNo" type="text" value="Otomatis saat disimpan" readonly>
                    <small class="field__hint">Format sistem: QI-YYYY-XXXX</small>
                </div>
                <div class="field">
                    <label class="field__label" for="qiType">Jenis Inspeksi <span class="req">*</span></label>
                    <select class="select" id="qiType" required>
                        <option value="">Pilih jenis pengujian</option>
                        @foreach ($inspectionTypes as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="qiBatch">No. Referensi Batch / Lot <span class="req">*</span></label>
                    <input class="input" id="qiBatch" type="text" placeholder="Mis. LOT-241001 atau LCP01-261010-A" required>
                </div>
                <div class="field">
                    <label class="field__label" for="qiMaterial">Material / Produk <span class="req">*</span></label>
                    <select class="select" id="qiMaterial" required>
                        <option value="">Pilih material atau produk</option>
                        @foreach ($materials as $code => $mat)
                            <option value="{{ $code }}">{{ $code }} - {{ $mat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="qiStage">Tahap Produksi / Proses <span class="req">*</span></label>
                    <input class="input" id="qiStage" type="text" placeholder="Mis. Retort (Sterilisasi) / Penerimaan" required>
                </div>
                <div class="field">
                    <label class="field__label" for="qiInspector">Inspektur / Petugas QC <span class="req">*</span></label>
                    <input class="input" id="qiInspector" type="text" value="Dewi Anggraini, S.TP" required>
                </div>
            </div>
        </section>

        {{-- 2. Karakteristik Mutu (Inspection Characteristics) --}}
        <section class="panel panel--fit">
            <div class="panel__head panel__head--row">
                <h3><span class="step-no">2</span>Karakteristik &amp; Parameter Uji</h3>
                <span class="hint-inline">Batas spesifikasi R&amp;D / QA</span>
            </div>

            <div class="table-wrap">
                <table class="table" id="paramTable">
                    <thead>
                        <tr>
                            <th>Parameter Uji</th>
                            <th>Target / Standar</th>
                            <th>Nilai Aktual</th>
                            <th>Satuan</th>
                            <th>Status Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Suhu Retort / Sterilisasi</strong></td>
                            <td>121.1 °C (Min 120 °C)</td>
                            <td><input class="input input--sm" type="text" value="121.5"></td>
                            <td>°C</td>
                            <td><span class="badge badge--ok">Lulus</span></td>
                        </tr>
                        <tr>
                            <td><strong>Nilai F0 (Sterilisasi Termal)</strong></td>
                            <td>≥ 4.0 menit</td>
                            <td><input class="input input--sm" type="text" value="4.2"></td>
                            <td>Menit</td>
                            <td><span class="badge badge--ok">Lulus</span></td>
                        </tr>
                        <tr>
                            <td><strong>Berat Isi (Net Weight Pouch)</strong></td>
                            <td>85 g ± 2 g</td>
                            <td><input class="input input--sm" type="text" value="85.4"></td>
                            <td>Gram</td>
                            <td><span class="badge badge--ok">Lulus</span></td>
                        </tr>
                        <tr>
                            <td><strong>Uji Kebocoran Seal (Burst Test)</strong></td>
                            <td>Tidak bocor / Kuat</td>
                            <td><input class="input input--sm" type="text" value="Passed"></td>
                            <td>Visual</td>
                            <td><span class="badge badge--ok">Lulus</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- 3. Keputusan Mutu (Usage Decision) --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">3</span>Keputusan Mutu &amp; Catatan QC</h3></div>
            <div class="panel__body form-grid">
                <div class="field">
                    <label class="field__label" for="qiDecision">Keputusan Penggunaan (Usage Decision) <span class="req">*</span></label>
                    <select class="select" id="qiDecision" required>
                        <option value="wait">Menunggu Hasil Pengujian Lengkap</option>
                        <option value="ok">Lulus (Released / Accept)</option>
                        <option value="fail">Gagal (Rejected / Scrap)</option>
                        <option value="hold">Ditahan Sementara (Blocked for Review)</option>
                    </select>
                </div>
                <div class="field field--full">
                    <label class="field__label" for="qiNotes">Catatan Analisis QC / Keterangan Tambahan</label>
                    <textarea class="input textarea" id="qiNotes" rows="2" placeholder="Masukkan catatan hasil uji laboratorium, sensorik, atau mikrobiologi..."></textarea>
                </div>
            </div>
        </section>

        {{-- Aksi --}}
        <div class="form-actions">
            <a href="{{ route('quality-inspection') }}" class="btn btn--light">Batal</a>
            <button type="button" class="btn btn--primary" id="saveQiBtn"><i data-lucide="save" aria-hidden="true"></i><span>Simpan Lot Inspeksi</span></button>
        </div>
    </div>

    {{-- ===== KOLOM KANAN ===== --}}
    <aside class="form-side">
        <section class="panel panel--fit">
            <div class="panel__head"><h3>Ringkasan Lot QC</h3></div>
            <dl class="summary">
                <div><dt>No. Inspeksi</dt><dd>Otomatis</dd></div>
                <div><dt>Jenis</dt><dd id="sumType">-</dd></div>
                <div><dt>Material</dt><dd id="sumMaterial">-</dd></div>
                <div><dt>Inspektur</dt><dd>Dewi Anggraini</dd></div>
                <div><dt>Keputusan</dt><dd><span class="badge badge--wait" id="sumDecisionBadge">Menunggu</span></dd></div>
            </dl>
        </section>

        <section class="panel panel--fit">
            <div class="panel__head"><h3>Alur SAP Quality Management</h3></div>
            <ol class="flow">
                <li class="flow__item is-current">
                    <span class="flow__dot">1</span>
                    <div><strong>Buka Lot Inspeksi</strong><small>Pencatatan parameter parameter uji</small></div>
                </li>
                <li class="flow__item">
                    <span class="flow__dot">2</span>
                    <div><strong>Pengujian Lab / Lapangan</strong><small>Input nilai aktual vs standar R&D</small></div>
                </li>
                <li class="flow__item">
                    <span class="flow__dot">3</span>
                    <div><strong>Usage Decision (UD)</strong><small>Release batch atau karantina</small></div>
                </li>
            </ol>
        </section>
    </aside>

</form>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('qiForm');
    if (!form) return;

    var typeSelect = document.getElementById('qiType');
    var matSelect = document.getElementById('qiMaterial');
    var decisionSelect = document.getElementById('qiDecision');

    var sumType = document.getElementById('sumType');
    var sumMaterial = document.getElementById('sumMaterial');
    var sumDecisionBadge = document.getElementById('sumDecisionBadge');

    typeSelect.addEventListener('change', function() {
        sumType.textContent = this.options[this.selectedIndex].text.split('(')[0] || '-';
    });

    matSelect.addEventListener('change', function() {
        sumMaterial.textContent = this.options[this.selectedIndex].text.split('-')[0].trim() || '-';
    });

    decisionSelect.addEventListener('change', function() {
        var val = this.value;
        var text = this.options[this.selectedIndex].text;
        sumDecisionBadge.textContent = text.split('(')[0].trim();
        
        sumDecisionBadge.className = 'badge';
        if (val === 'ok') sumDecisionBadge.classList.add('badge--ok');
        else if (val === 'fail') sumDecisionBadge.classList.add('badge--fail');
        else if (val === 'hold') sumDecisionBadge.classList.add('badge--warn');
        else sumDecisionBadge.classList.add('badge--wait');
    });

    document.getElementById('saveQiBtn').addEventListener('click', function() {
        alert('Lot Quality Inspection berhasil disimpan!');
        window.location.href = "{{ route('quality-inspection') }}";
    });
});
</script>
@endsection