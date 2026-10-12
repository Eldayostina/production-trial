@extends('layouts.app')

@section('page_title', $id ? 'Ubah Pengguna' : 'Tambah Pengguna')
@section('breadcrumb_parent', 'User Management')

@section('content')

@php
    // DATA DUMMY - nanti diganti query database berdasarkan $id.
    // [nama, email, telepon, departemen, jabatan, peran, status, akun sendiri (1/0), administrator terakhir (1/0)]
    $all = [
        'EVO-0001' => ['Admin Produksi', 'admin.produksi@evo.co.id', '0812-1000-0001', 'Production',        'Administrator Sistem', 'Administrator',       'aktif',    1, 1],
        'EVO-0002' => ['Budi Santoso',   'budi.santoso@evo.co.id',   '0812-1000-0002', 'Production',        'Supervisor Produksi',  'Supervisor Produksi', 'aktif',    0, 0],
        'EVO-0003' => ['Rina Wulandari', 'rina.w@evo.co.id',         '0812-1000-0003', 'R&D',               'Formulator',           'R&D',                 'aktif',    0, 0],
        'EVO-0004' => ['Dewi Anggraini', 'dewi.a@evo.co.id',         '0812-1000-0004', 'Quality Assurance', 'Supervisor QA',        'Quality Assurance',   'aktif',    0, 0],
        'EVO-0005' => ['Siti Nurhaliza', 'siti.n@evo.co.id',         '0812-1000-0005', 'Warehouse',         'Admin Gudang',         'Admin Gudang',        'aktif',    0, 0],
        'EVO-0006' => ['Joko Susilo',    'joko.s@evo.co.id',         '0812-1000-0006', 'Warehouse',         'Staf Gudang',          'Admin Gudang',        'aktif',    0, 0],
        'EVO-0007' => ['Hendra Wijaya',  'hendra.w@evo.co.id',       '0812-1000-0007', 'Quality Assurance', 'Analis QC',            'Quality Assurance',   'terkunci', 0, 0],
        'EVO-0008' => ['Agus Pratama',   'agus.p@evo.co.id',         '0812-1000-0008', 'Production',        'Foreman Line',         'Supervisor Produksi', 'aktif',    0, 0],
        'EVO-0009' => ['Maya Lestari',   'maya.l@evo.co.id',         '0812-1000-0009', 'Purchasing',        'Staf Purchasing',      'Purchasing',          'nonaktif', 0, 0],
    ];

    $u = $id ? ($all[$id] ?? null) : null;
    abort_if($id && ! $u, 404);

    $v = fn ($i, $d = '') => $u ? ($u[$i] ?? $d) : $d;

    $depts = ['Production', 'R&D', 'Quality Assurance', 'Warehouse', 'Purchasing', 'PPIC', 'Management'];

    $roles = [
        'Administrator'        => 'Akses penuh sistem, termasuk pengelolaan pengguna dan pengaturan.',
        'Manager Produksi'     => 'Menyetujui Production Plan dan Trial Result. Melihat seluruh modul produksi.',
        'Supervisor Produksi'  => 'Membuat Production Plan, menjalankan trial, dan mengisi parameter proses.',
        'R&D'                  => 'Menyusun BOM dan formula trial, melakukan review hasil trial.',
        'Quality Assurance'    => 'Review BOM dan Trial Result, melepas lot dari karantina.',
        'Admin Gudang'         => 'Mengelola Master Gudang, penerimaan barang, dan Stock Transfer.',
        'Purchasing'           => 'Mengelola permintaan pembelian dan data supplier.',
        'Auditor'              => 'Hanya baca. Dapat melihat seluruh modul dan Audit Log.',
    ];
@endphp

<section class="page-head">
    <div>
        <h2>{{ $id ? 'Ubah Pengguna' : 'Tambah Pengguna' }}</h2>
        <p>Tanda <span class="req">*</span> wajib diisi. Hak akses detail per peran diatur di Roles &amp; Permission.</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('users') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
    </div>
</section>

<form id="userForm" class="form-layout" novalidate
      data-edit="{{ $id ? '1' : '0' }}"
      data-self="{{ $v(7, 0) }}"
      data-lastadmin="{{ $v(8, 0) }}"
      data-roles="{{ json_encode($roles) }}">

    <div class="form-main">

        {{-- 1. Data pengguna --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">1</span>Data Pengguna</h3></div>
            <div class="panel__body form-grid">
                <div class="field">
                    <label class="field__label" for="uNik">NIK Karyawan <span class="req">*</span></label>
                    <input class="input" id="uNik" type="text" maxlength="12" placeholder="Contoh: EVO-0010" value="{{ $id }}" {{ $id ? 'readonly' : '' }}>
                    <small class="field__hint">{{ $id ? 'NIK tidak dapat diubah setelah disimpan.' : 'Harus unik, tidak bisa diubah setelah disimpan.' }}</small>
                </div>
                <div class="field">
                    <label class="field__label" for="uNama">Nama Lengkap <span class="req">*</span></label>
                    <input class="input" id="uNama" type="text" maxlength="80" value="{{ $v(0) }}">
                </div>
                <div class="field">
                    <label class="field__label" for="uEmail">Email Perusahaan <span class="req">*</span></label>
                    <input class="input" id="uEmail" type="email" placeholder="nama@evo.co.id" value="{{ $v(1) }}">
                    <small class="field__hint">Dipakai untuk login dan pengiriman password sementara.</small>
                </div>
                <div class="field">
                    <label class="field__label" for="uTelp">No. Telepon</label>
                    <input class="input" id="uTelp" type="tel" placeholder="08xx-xxxx-xxxx" value="{{ $v(2) }}">
                </div>
                <div class="field">
                    <label class="field__label" for="uDept">Departemen <span class="req">*</span></label>
                    <select class="select" id="uDept">
                        <option value="">Pilih departemen</option>
                        @foreach ($depts as $d)
                            <option @selected($v(3) === $d)>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="uJabatan">Jabatan</label>
                    <input class="input" id="uJabatan" type="text" maxlength="60" placeholder="Contoh: Supervisor Produksi" value="{{ $v(4) }}">
                </div>
            </div>
        </section>

        {{-- 2. Akses --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">2</span>Peran &amp; Status</h3></div>
            <div class="panel__body form-grid">
                <div class="field">
                    <label class="field__label" for="uRole">Peran <span class="req">*</span></label>
                    <select class="select" id="uRole">
                        <option value="">Pilih peran</option>
                        @foreach ($roles as $name => $desc)
                            <option @selected($v(5) === $name)>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="uStatus">Status <span class="req">*</span></label>
                    <select class="select" id="uStatus">
                        <option value="aktif" @selected($v(6, 'aktif') === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected($v(6) === 'nonaktif')>Nonaktif</option>
                        @if ($v(6) === 'terkunci')
                            <option value="terkunci" selected>Terkunci (login gagal berulang)</option>
                        @endif
                    </select>
                </div>
                <div class="field field--full">
                    <div class="role-note" id="roleNote">Pilih peran untuk melihat ringkasan hak aksesnya.</div>
                </div>
            </div>
            <div class="alert alert--warn" id="uAlert" hidden>
                <i data-lucide="alert-triangle" aria-hidden="true"></i>
                <p id="uAlertText"></p>
            </div>
        </section>

        {{-- 3. Keamanan --}}
        <section class="panel panel--fit">
            <div class="panel__head"><h3><span class="step-no">3</span>Keamanan Akun</h3></div>
            @if ($id)
                <div class="security">
                    <div class="security__row">
                        <div>
                            <strong>Reset password</strong>
                            <small>Sistem membuat password sementara baru dan mengirimkannya ke email pengguna. Pengguna wajib menggantinya saat login.</small>
                        </div>
                        <button type="button" class="btn btn--light" data-user-action="reset"><i data-lucide="key-round" aria-hidden="true"></i><span>Reset Password</span></button>
                    </div>
                    @if ($v(6) === 'terkunci')
                        <div class="security__row">
                            <div>
                                <strong>Buka kunci akun</strong>
                                <small>Akun terkunci setelah 5 kali login gagal. Buka kunci setelah identitas pengguna dipastikan.</small>
                            </div>
                            <button type="button" class="btn btn--light" data-user-action="unlock"><i data-lucide="lock-open" aria-hidden="true"></i><span>Buka Kunci</span></button>
                        </div>
                    @endif
                </div>
            @else
                <div class="panel__body">
                    <ul class="plain-list">
                        <li>Password sementara <strong>dibuat otomatis</strong> oleh sistem, bukan diketik admin.</li>
                        <li>Password dikirim ke email pengguna setelah akun disimpan.</li>
                        <li>Pengguna <strong>wajib mengganti password</strong> saat login pertama.</li>
                    </ul>
                </div>
            @endif
        </section>

        <div class="form-actions">
            <a href="{{ route('users') }}" class="btn btn--light">Batal</a>
            <button type="button" class="btn btn--primary" id="saveUser"><i data-lucide="save" aria-hidden="true"></i><span>{{ $id ? 'Simpan Perubahan' : 'Simpan Pengguna' }}</span></button>
        </div>
    </div>

    <aside class="form-side">
        <section class="panel panel--fit">
            <div class="panel__head"><h3>Ringkasan</h3></div>
            <dl class="summary">
                <div><dt>NIK</dt><dd id="sumNik">-</dd></div>
                <div><dt>Nama</dt><dd id="sumNama">-</dd></div>
                <div><dt>Departemen</dt><dd id="sumDept">-</dd></div>
                <div><dt>Peran</dt><dd id="sumRole">-</dd></div>
                <div><dt>Status</dt><dd id="sumStatus">-</dd></div>
            </dl>
        </section>

        <section class="panel panel--fit">
            <div class="panel__head"><h3>Catatan Keamanan</h3></div>
            <ol class="flow">
                <li class="flow__item is-current"><span class="flow__dot">1</span><div><strong>Satu orang, satu akun</strong><small>Akun tidak dibagi bersama</small></div></li>
                <li class="flow__item"><span class="flow__dot">2</span><div><strong>Akses sesuai tugas</strong><small>Berikan peran paling sempit yang cukup</small></div></li>
                <li class="flow__item"><span class="flow__dot">3</span><div><strong>Nonaktifkan saat resign</strong><small>Akun tidak dihapus agar Audit Log utuh</small></div></li>
            </ol>
        </section>
    </aside>

</form>

@endsection