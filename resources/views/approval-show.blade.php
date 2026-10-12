@extends('layouts.app')

@section('page_title', 'Tinjau Approval')
@section('breadcrumb_parent', 'Approval')

@section('content')

@php
    // DATA DUMMY - nomor di URL belum dipakai untuk query.

    $stages = [
        ['Diajukan',            'done'],
        ['Review Quality Control', 'done'],
        ['Review R&D',          'current'],
        ['Persetujuan Manager', 'pending'],
        ['Selesai',             'pending'],
    ];

    // [tanggal, user, peran, aksi, catatan]
    $history = [
        ['08 Okt 2026, 14.20', 'Rina Wulandari', 'R&D',        'Menyetujui (review tahap 2)', 'Formula sesuai standar R&D. Lanjut ke Manager.'],
        ['08 Okt 2026, 10.05', 'Dewi Anggraini', 'Quality Control', 'Menyetujui (review tahap 1)', 'Kriteria QC pada dokumen sudah lengkap.'],
        ['07 Okt 2026, 16.40', 'Admin Produksi', 'Production',  'Mengajukan',                  'Dokumen diajukan untuk review.'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>TRL-RES-2026-0042 <span class="badge badge--run">Sedang Direview</span></h2>
        <p>Trial Result &middot; Life Cat Pouch Mackerel 85 g &middot; Diajukan oleh Budi Santoso</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('approval') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
        <a href="{{ route('trial-result.show', 'TRL-RES-2026-0042') }}" class="btn btn--light"><i data-lucide="external-link" aria-hidden="true"></i><span>Lihat Dokumen Asli</span></a>
    </div>
</section>

<div id="approvalPage">

    {{-- Info dokumen --}}
    <section class="panel panel--fit">
        <dl class="info-grid">
            <div><dt>No. Dokumen</dt><dd class="table__code">TRL-RES-2026-0042</dd></div>
            <div><dt>Jenis</dt><dd>Trial Result</dd></div>
            <div><dt>Diajukan Oleh</dt><dd>Budi Santoso</dd></div>
            <div><dt>Tanggal Pengajuan</dt><dd>07 Okt 2026</dd></div>
            <div><dt>Ref. Production Trial</dt><dd class="table__code">TRL-2026-0042</dd></div>
            <div><dt>Ref. Production Plan</dt><dd class="table__code">PP-2026-0014</dd></div>
            <div><dt>Prioritas</dt><dd>Normal</dd></div>
            <div><dt>Tahap Saat Ini</dt><dd>Review R&amp;D</dd></div>
        </dl>
    </section>

    <div class="form-layout">
        <div class="form-main">

            {{-- Ringkasan dokumen --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Ringkasan Dokumen</h3>
                    <span class="hint-inline">Data penting dari dokumen asli</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <tbody>
                            <tr><th style="width:220px">Produk</th><td>Life Cat Pouch Mackerel 85 g (FG-LC-M01)</td></tr>
                            <tr><th>No. Batch</th><td class="table__code">LCM01-260918-A</td></tr>
                            <tr><th>Qty Diproses</th><td>3.000 pouch</td></tr>
                            <tr><th>Output Baik / Reject</th><td>2.910 / 90 pouch</td></tr>
                            <tr><th>Yield</th><td>97,0%</td></tr>
                            <tr><th>Reject Rate</th><td>2,0%</td></tr>
                            <tr><th>Hasil Evaluasi Kriteria</th><td><span class="badge badge--ok">Semua kriteria terpenuhi</span></td></tr>
                            <tr><th>Keputusan Reviewer</th><td>Lulus - dapat dilanjutkan ke scale-up</td></tr>
                            <tr><th>Catatan Reviewer</th><td>Parameter retort stabil. Yield sesuai target. Lanjutkan ke tahap scale-up.</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Keputusan --}}
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Keputusan Approval</h3></div>

                <div class="alert alert--warn" id="apprWarn" hidden>
                    <i data-lucide="alert-triangle" aria-hidden="true"></i>
                    <p>Catatan wajib diisi untuk semua keputusan. Jika menolak, tuliskan alasan penolakan dan saran perbaikan.</p>
                </div>

                <div class="panel__body form-grid">
                    <div class="field field--full">
                        <label class="field__label" for="apprDecision">Keputusan <span class="req">*</span></label>
                        <select class="select" id="apprDecision" required>
                            <option value="">Pilih keputusan</option>
                            <option value="approve">Setujui</option>
                            <option value="reject">Tolak</option>
                            <option value="revise">Minta Revisi</option>
                        </select>
                    </div>
                    <div class="field field--full">
                        <label class="field__label" for="apprNote">Catatan Keputusan <span class="req">*</span></label>
                        <textarea class="input textarea" id="apprNote" rows="3" placeholder="Dasar pertimbangan keputusan..." required></textarea>
                    </div>
                    <div class="field field--full" id="apprRejectField" hidden>
                        <label class="field__label" for="apprReject">Alasan Penolakan &amp; Saran Perbaikan <span class="req">*</span></label>
                        <textarea class="input textarea" id="apprReject" rows="2" placeholder="Contoh: kriteria X belum memenuhi spesifikasi, ulangi trial dengan parameter Y."></textarea>
                    </div>
                </div>
            </section>

            {{-- Riwayat approval --}}
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Riwayat Approval</h3></div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal &amp; Waktu</th>
                                <th>User</th>
                                <th>Peran</th>
                                <th>Aksi</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($history as $h)
                                <tr>
                                    <td>{{ $h[0] }}</td>
                                    <td>{{ $h[1] }}</td>
                                    <td>{{ $h[2] }}</td>
                                    <td><span class="badge badge--{{ str_contains($h[3], 'Menyetujui') ? 'ok' : 'draft' }}">{{ $h[3] }}</span></td>
                                    <td>{{ $h[4] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Aksi --}}
            <div class="form-actions">
                <a href="{{ route('approval') }}" class="btn btn--light">Batal</a>
                <button type="button" class="btn btn--light" data-appr-action="revise"><i data-lucide="edit-3" aria-hidden="true"></i><span>Minta Revisi</span></button>
                <button type="button" class="btn btn--light" data-appr-action="reject"><i data-lucide="x" aria-hidden="true"></i><span>Tolak</span></button>
                <button type="button" class="btn btn--primary" data-appr-action="approve"><i data-lucide="check" aria-hidden="true"></i><span>Setujui</span></button>
            </div>
        </div>

        {{-- Kolom kanan --}}
        <aside class="form-side">
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Ringkasan</h3></div>
                <dl class="summary">
                    <div><dt>Jenis</dt><dd>Trial Result</dd></div>
                    <div><dt>Pengaju</dt><dd>Budi Santoso</dd></div>
                    <div><dt>Tanggal Pengajuan</dt><dd>07 Okt 2026</dd></div>
                    <div><dt>Tahap</dt><dd>Review R&amp;D</dd></div>
                    <div><dt>Menunggu</dt><dd>1 hari</dd></div>
                    <div><dt>Status</dt><dd><span class="badge badge--run">Sedang Direview</span></dd></div>
                </dl>
            </section>

            <section class="panel panel--fit">
                <div class="panel__head"><h3>Alur Approval</h3></div>
                <ol class="stepper">
                    @foreach ($stages as $i => $s)
                        <li class="stepper__item is-{{ $s[1] }}">
                            <span class="stepper__dot">
                                @if ($s[1] === 'done') <i data-lucide="check" aria-hidden="true"></i> @else {{ $i + 1 }} @endif
                            </span>
                            <span class="stepper__label">{{ $s[0] }}</span>
                        </li>
                    @endforeach
                </ol>
            </section>
        </aside>
    </div>

</div>

@endsection