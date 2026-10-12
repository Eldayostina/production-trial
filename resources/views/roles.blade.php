@extends('layouts.app')

@section('page_title', 'Roles & Permission')
@section('breadcrumb_parent', 'Users & Access')

@section('content')

@php
    // DATA DUMMY - nanti diganti data dari database (Controller)
    $actions = ['view' => 'Lihat', 'create' => 'Buat', 'edit' => 'Ubah', 'approve' => 'Approve', 'export' => 'Ekspor'];
    $letter  = ['v' => 'view', 'c' => 'create', 'e' => 'edit', 'a' => 'approve', 'x' => 'export'];

    // [key, nama modul, grup, aksi yang berlaku]
    $defs = [
        ['dashboard', 'Dashboard',          'OPERASIONAL', 'v'],
        ['plan',      'Production Plan',    'OPERASIONAL', 'vceax'],
        ['trial',     'Production Trial',   'OPERASIONAL', 'vceax'],
        ['result',    'Trial Result',       'OPERASIONAL', 'vceax'],
        ['transfer',  'Stock Transfer',     'OPERASIONAL', 'vceax'],
        ['report',    'Reports',            'OPERASIONAL', 'vx'],
        ['bom',       'Bill of Materials',  'MASTER DATA', 'vceax'],
        ['item',      'Master Barang',      'MASTER DATA', 'vcex'],
        ['gudang',    'Master Gudang',      'MASTER DATA', 'vcex'],
        ['audit',     'Audit Log',          'SYSTEM',      'vx'],
        ['users',     'User Management',    'SYSTEM',      'vce'],
        ['roles',     'Roles & Permission', 'SYSTEM',      'vce'],
    ];

    $modules = [];
    foreach ($defs as $d) {
        $modules[] = [
            'key' => $d[0], 'label' => $d[1], 'group' => $d[2],
            'allowed' => array_map(fn ($c) => $letter[$c], str_split($d[3])),
        ];
    }

    // nama => [deskripsi, jumlah pengguna, peran sistem, izin per modul (huruf)]
    $roleDefs = [
        'Administrator' => ['Akses penuh sistem, termasuk pengguna dan pengaturan.', 1, true, null],
        'Manager Produksi' => ['Menyetujui Production Plan dan Trial Result.', 0, false,
            ['dashboard' => 'v', 'plan' => 'vax', 'trial' => 'vx', 'result' => 'vax', 'transfer' => 'va', 'report' => 'vx', 'bom' => 'v', 'item' => 'v', 'gudang' => 'v']],
        'Supervisor Produksi' => ['Membuat Production Plan dan menjalankan trial.', 2, false,
            ['dashboard' => 'v', 'plan' => 'vcex', 'trial' => 'vcex', 'result' => 'vce', 'transfer' => 'vc', 'report' => 'v', 'bom' => 'v', 'item' => 'v', 'gudang' => 'v']],
        'R&D' => ['Menyusun BOM dan formula, review hasil trial.', 1, false,
            ['dashboard' => 'v', 'plan' => 'v', 'trial' => 'v', 'result' => 'vea', 'report' => 'v', 'bom' => 'vcea', 'item' => 'vce']],
        'Quality Assurance' => ['Review BOM dan Trial Result, melepas lot dari karantina.', 2, false,
            ['dashboard' => 'v', 'plan' => 'va', 'trial' => 've', 'result' => 'vea', 'report' => 'vx', 'bom' => 'va', 'item' => 'v', 'gudang' => 'v', 'audit' => 'v']],
        'Admin Gudang' => ['Mengelola gudang, penerimaan barang, dan Stock Transfer.', 2, false,
            ['dashboard' => 'v', 'plan' => 'v', 'transfer' => 'vceax', 'report' => 'v', 'item' => 'vcex', 'gudang' => 'vcex']],
        'Purchasing' => ['Mengelola permintaan pembelian dan data supplier.', 1, false,
            ['dashboard' => 'v', 'plan' => 'v', 'report' => 'v', 'item' => 'v', 'gudang' => 'v']],
        'Auditor' => ['Hanya baca. Dapat melihat seluruh modul dan Audit Log.', 0, false,
            ['dashboard' => 'v', 'plan' => 'vx', 'trial' => 'vx', 'result' => 'vx', 'transfer' => 'vx', 'report' => 'vx', 'bom' => 'vx', 'item' => 'vx', 'gudang' => 'vx', 'audit' => 'vx', 'users' => 'v', 'roles' => 'v']],
    ];

    $roles = [];
    foreach ($roleDefs as $name => $r) {
        $perms = [];
        foreach ($modules as $m) {
            if ($r[3] === null) {
                $perms[$m['key']] = $m['allowed'];                       // administrator: semua
            } elseif (isset($r[3][$m['key']])) {
                $perms[$m['key']] = array_map(fn ($c) => $letter[$c], str_split($r[3][$m['key']]));
            }
        }
        $roles[$name] = ['desc' => $r[0], 'users' => $r[1], 'system' => $r[2], 'perms' => $perms];
    }

    $lastGroup = null;
@endphp

<section class="page-head">
    <div>
        <h2>Roles &amp; Permission</h2>
        <p>Atur modul dan aksi yang boleh dilakukan setiap peran. Satu pengguna memiliki satu peran.</p>
    </div>
    <div class="page-head__actions">
        <button type="button" class="btn btn--primary" id="btnNewRole"><i data-lucide="plus" aria-hidden="true"></i><span>Tambah Peran</span></button>
    </div>
</section>

<div class="roles-layout" id="rolesPage"
     data-roles="{{ json_encode($roles) }}"
     data-modules="{{ json_encode($modules) }}">

    {{-- ===== Kiri: daftar peran ===== --}}
    <div class="roles-side">
        <section class="panel panel--fit">
            <div class="panel__head"><h3>Peran</h3></div>
            <div class="role-list" id="roleList"></div>
        </section>

        <section class="panel panel--fit" id="newRolePanel" hidden>
            <div class="panel__head"><h3>Peran Baru</h3></div>
            <div class="panel__body form-grid form-grid--1">
                <div class="field">
                    <label class="field__label" for="nrName">Nama Peran <span class="req">*</span></label>
                    <input class="input" id="nrName" type="text" maxlength="40" placeholder="Contoh: Supervisor QC">
                </div>
                <div class="field">
                    <label class="field__label" for="nrDesc">Deskripsi</label>
                    <input class="input" id="nrDesc" type="text" maxlength="120" placeholder="Tugas utama peran ini">
                </div>
                <div class="field">
                    <label class="field__label" for="nrCopy">Salin izin dari</label>
                    <select class="select" id="nrCopy"></select>
                </div>
                <div class="new-role__actions">
                    <button type="button" class="btn btn--light" id="nrCancel">Batal</button>
                    <button type="button" class="btn btn--primary" id="nrCreate">Buat Peran</button>
                </div>
            </div>
        </section>
    </div>

    {{-- ===== Kanan: matriks izin ===== --}}
    <section class="panel panel--fit">
        <div class="panel__head panel__head--row">
            <div>
                <h3 id="roleName">-</h3>
                <small class="cell-sub" id="roleDesc">-</small>
            </div>
            <span class="badge badge--ok" id="roleCount">-</span>
        </div>

        <div class="alert alert--info" id="lockNote" hidden>
            <i data-lucide="lock" aria-hidden="true"></i>
            <p>Peran sistem. Administrator selalu memiliki akses penuh dan tidak dapat diubah.</p>
        </div>
        <div class="alert alert--warn" id="sodNote" hidden>
            <i data-lucide="alert-triangle" aria-hidden="true"></i>
            <p id="sodText"></p>
        </div>

        <div class="table-wrap">
            <table class="table matrix">
                <thead>
                    <tr>
                        <th>Modul</th>
                        @foreach ($actions as $k => $label)
                            <th class="matrix__center">
                                <label class="matrix__head">
                                    <input type="checkbox" class="js-col" data-action="{{ $k }}" aria-label="Pilih semua {{ $label }}">
                                    <span>{{ $label }}</span>
                                </label>
                            </th>
                        @endforeach
                        <th class="matrix__center">Semua</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modules as $m)
                        @if ($m['group'] !== $lastGroup)
                            @php $lastGroup = $m['group']; @endphp
                            <tr class="matrix__group"><td colspan="{{ count($actions) + 2 }}">{{ $m['group'] }}</td></tr>
                        @endif
                        <tr>
                            <td>{{ $m['label'] }}</td>
                            @foreach ($actions as $k => $label)
                                @if (in_array($k, $m['allowed']))
                                    <td class="matrix__perm">
                                        <input type="checkbox" class="js-perm" data-module="{{ $m['key'] }}" data-action="{{ $k }}" aria-label="{{ $label }} {{ $m['label'] }}">
                                    </td>
                                @else
                                    <td class="matrix__na">&ndash;</td>
                                @endif
                            @endforeach
                            <td class="matrix__perm">
                                <input type="checkbox" class="js-row" data-module="{{ $m['key'] }}" aria-label="Semua izin {{ $m['label'] }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="panel__foot">
            <p id="roleSummary">-</p>
            <div class="foot-actions">
                <button type="button" class="btn btn--light" id="resetRole">Batalkan Perubahan</button>
                <button type="button" class="btn btn--primary" id="saveRole"><i data-lucide="save" aria-hidden="true"></i><span>Simpan Izin</span></button>
            </div>
        </div>
    </section>

</div>

@endsection