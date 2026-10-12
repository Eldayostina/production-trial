@extends('layouts.app')

@section('page_title', 'User Management')
@section('breadcrumb_parent', 'Users & Access')

@section('content')

@php
    // DATA DUMMY - nanti diganti data dari database (Controller)
    $statuses = [
        'aktif'    => ['Aktif', 'ok'],
        'terkunci' => ['Terkunci', 'wait'],
        'nonaktif' => ['Nonaktif', 'obsolete'],
    ];

    // [NIK, nama, email, departemen, peran, status, login terakhir]
    $users = [
        ['EVO-0001', 'Admin Produksi',   'admin.produksi@evo.co.id', 'Production',        'Administrator',       'aktif',    'Hari ini, 08:05'],
        ['EVO-0002', 'Budi Santoso',     'budi.santoso@evo.co.id',   'Production',        'Supervisor Produksi', 'aktif',    'Hari ini, 09:42'],
        ['EVO-0003', 'Rina Wulandari',   'rina.w@evo.co.id',         'R&D',               'R&D',                 'aktif',    'Hari ini, 10:15'],
        ['EVO-0004', 'Dewi Anggraini',   'dewi.a@evo.co.id',         'Quality Assurance', 'Quality Assurance',   'aktif',    'Kemarin, 16:30'],
        ['EVO-0005', 'Siti Nurhaliza',   'siti.n@evo.co.id',         'Warehouse',         'Admin Gudang',        'aktif',    'Kemarin, 14:12'],
        ['EVO-0006', 'Joko Susilo',      'joko.s@evo.co.id',         'Warehouse',         'Admin Gudang',        'aktif',    '3 hari lalu'],
        ['EVO-0007', 'Hendra Wijaya',    'hendra.w@evo.co.id',       'Quality Assurance', 'Quality Assurance',   'terkunci', '2 hari lalu (login gagal 5x)'],
        ['EVO-0008', 'Agus Pratama',     'agus.p@evo.co.id',         'Production',        'Supervisor Produksi', 'aktif',    '4 hari lalu'],
        ['EVO-0009', 'Elda Yostina',     'elda.y@evo.co.id',         'Purchasing',        'Purchasing',          'nonaktif', '45 hari lalu'],
    ];

    $count = fn ($s) => count(array_filter($users, fn ($u) => $u[5] === $s));

    $initials = function ($name) {
        $p = preg_split('/\s+/', trim($name));
        return strtoupper(substr($p[0], 0, 1) . (isset($p[1]) ? substr($p[1], 0, 1) : ''));
    };
@endphp

<section class="page-head">
    <div>
        <h2>User Management</h2>
        <p>Kelola akun pengguna. Akun tidak dihapus, hanya dinonaktifkan agar jejak audit tetap utuh.</p>
    </div>
    <div class="page-head__actions">
        <button type="button" class="btn btn--light"><i data-lucide="download" aria-hidden="true"></i><span>Export</span></button>
        <a href="{{ route('users.create') }}" class="btn btn--primary"><i data-lucide="user-plus" aria-hidden="true"></i><span>Tambah Pengguna</span></a>
    </div>
</section>

<section class="kpi-grid" aria-label="Ringkasan pengguna">
    <article class="kpi">
        <span class="kpi__icon kpi__icon--blue"><i data-lucide="users" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total Pengguna</p>
            <p class="kpi__value">{{ count($users) }}</p>
            <p class="kpi__trend">Semua akun</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--green"><i data-lucide="user-check" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Aktif</p>
            <p class="kpi__value">{{ $count('aktif') }}</p>
            <p class="kpi__trend kpi__trend--up">Dapat login</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--amber"><i data-lucide="lock" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Terkunci</p>
            <p class="kpi__value">{{ $count('terkunci') }}</p>
            <p class="kpi__trend kpi__trend--warn"><i data-lucide="clock" aria-hidden="true"></i>Perlu dibuka kuncinya</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--indigo"><i data-lucide="user-x" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Nonaktif</p>
            <p class="kpi__value">{{ $count('nonaktif') }}</p>
            <p class="kpi__trend">Tidak dapat login</p>
        </div>
    </article>
</section>

<section class="panel panel--fit">

    <div class="toolbar">
        <div class="tabs" role="tablist" aria-label="Filter status">
            <button type="button" class="tabs__btn is-active" data-filter="all">Semua</button>
            <button type="button" class="tabs__btn" data-filter="aktif">Aktif</button>
            <button type="button" class="tabs__btn" data-filter="terkunci">Terkunci</button>
            <button type="button" class="tabs__btn" data-filter="nonaktif">Nonaktif</button>
        </div>

        <div class="toolbar__right">
            <label class="search">
                <i data-lucide="search" aria-hidden="true"></i>
                <input type="search" id="planSearch" placeholder="Cari nama, NIK, atau email..." aria-label="Cari pengguna">
            </label>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table" id="planTable">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>NIK</th>
                    <th>Departemen</th>
                    <th>Peran</th>
                    <th>Login Terakhir</th>
                    <th>Status</th>
                    <th class="table__right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $u)
                    @php [$label, $badge] = $statuses[$u[5]]; @endphp
                    <tr data-status="{{ $u[5] }}" data-search="{{ strtolower($u[0].' '.$u[1].' '.$u[2]) }}">
                        <td>
                            <div class="user-cell">
                                <span class="avatar">{{ $initials($u[1]) }}</span>
                                <div>
                                    <span class="cell-title">{{ $u[1] }}</span>
                                    <small class="cell-sub">{{ $u[2] }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="table__code"><a href="{{ route('users.edit', $u[0]) }}">{{ $u[0] }}</a></td>
                        <td>{{ $u[3] }}</td>
                        <td>{{ $u[4] }}</td>
                        <td>{{ $u[6] }}</td>
                        <td><span class="badge badge--{{ $badge }}">{{ $label }}</span></td>
                        <td>
                            <div class="table__actions">
                                <a href="{{ route('users.edit', $u[0]) }}" class="icon-btn icon-btn--sm" aria-label="Ubah pengguna"><i data-lucide="pencil" aria-hidden="true"></i></a>
                                <button type="button" class="icon-btn icon-btn--sm" aria-label="Lainnya"><i data-lucide="more-vertical" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="empty" id="planEmpty" hidden>
            <i data-lucide="search-x" aria-hidden="true"></i>
            <p>Tidak ada pengguna yang cocok.</p>
        </div>
    </div>

    <div class="panel__foot">
        <p>Menampilkan 1-{{ count($users) }} dari {{ count($users) }} data</p>
    </div>

</section>

@endsection