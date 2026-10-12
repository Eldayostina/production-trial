@extends('layouts.app')

@section('page_title', 'Audit Log')
@section('breadcrumb_parent', 'System')

@section('content')

@php
    // DATA DUMMY - nanti diisi otomatis dari setiap perubahan data (database).
    $actions = [
        'create'     => ['Buat', 'run'],
        'update'     => ['Ubah', 'wait'],
        'approve'    => ['Approve', 'ok'],
        'reject'     => ['Reject', 'fail'],
        'release'    => ['Release', 'done'],
        'login'      => ['Login', 'draft'],
        'login_fail' => ['Login Gagal', 'fail'],
    ];

    // [hari lalu, jam, pengguna, peran, modul, dokumen, aksi, deskripsi, IP, perubahan [[field, lama, baru]]]
    $raw = [
        [0, '10:15', 'Rina Wulandari', 'R&D', 'BOM', 'BOM-LC-P01-R03', 'update', 'Mengubah komposisi bahan BOM trial', '10.10.1.24',
            [['RM-2002 Maizena (qty / 1.000 pouch)', '-', '2 kg'], ['RM-2001 Tepung Tapioka (qty / 1.000 pouch)', '3 kg', '2,5 kg'], ['Alasan perubahan', '-', 'Perbaikan tekstur']]],
        [0, '09:42', 'Budi Santoso', 'Supervisor Produksi', 'Production Trial', 'TRL-2026-0048', 'update', 'Mengisi parameter proses tahap Sealing', '10.10.1.18',
            [['Suhu seal bar (aktual)', '-', '192 °C'], ['Status parameter', '-', 'Deviasi']]],
        [0, '08:05', 'Admin Produksi', 'Administrator', 'Autentikasi', '-', 'login', 'Login berhasil', '10.10.1.10', []],
        [1, '16:30', 'Dewi Anggraini', 'Quality Assurance', 'Trial Result', 'TRL-RES-2026-0044', 'approve', 'Review QC disetujui', '10.10.1.31',
            [['Status review QC', 'Menunggu', 'Disetujui']]],
        [1, '14:12', 'Siti Nurhaliza', 'Admin Gudang', 'Master Gudang', 'GD-CH01', 'update', 'Mengubah batas suhu dan PIC gudang', '10.10.2.15',
            [['Suhu maksimum', '5 °C', '4 °C'], ['PIC Gudang', 'Joko Susilo', 'Agus Pratama']]],
        [2, '11:20', 'Manager Produksi', 'Manager', 'Production Plan', 'PP-2026-0014', 'approve', 'Production Plan disetujui', '10.10.1.5',
            [['Status', 'Menunggu Approval', 'Disetujui']]],
        [2, '09:50', 'admin.gudang', 'Tidak dikenal', 'Autentikasi', '-', 'login_fail', 'Login gagal 3 kali: kata sandi salah', '103.28.14.77', []],
        [3, '15:44', 'Rina Wulandari', 'R&D', 'BOM', 'BOM-LC-P01-R03', 'create', 'Membuat revisi BOM baru dari Rev. 02', '10.10.1.24',
            [['No. BOM', '-', 'BOM-LC-P01-R03'], ['Revisi', '-', 'Rev. 03'], ['Status', '-', 'Draft']]],
        [4, '13:05', 'Manager R&D', 'Manager', 'BOM', 'BOM-LC-M01-R01', 'release', 'BOM di-release untuk produksi', '10.10.1.8',
            [['Status', 'Review QA', 'Released']]],
        [5, '10:30', 'Dewi Anggraini', 'Quality Assurance', 'Trial Result', 'TRL-RES-2026-0041', 'reject', 'Hasil trial ditolak: reject rate melebihi batas', '10.10.1.31',
            [['Keputusan', 'Lulus Bersyarat', 'Gagal - ulangi trial'], ['Catatan keputusan', '-', 'Reject rate 10,5% (batas 2%)']]],
        [6, '08:40', 'Budi Santoso', 'Supervisor Produksi', 'Production Plan', 'PP-2026-0017', 'create', 'Membuat production plan baru', '10.10.1.18',
            [['No. Plan', '-', 'PP-2026-0017'], ['Produk', '-', 'Life Cat Pouch Mackerel 85 g'], ['Qty target', '-', '3.000 pouch']]],
        [10, '14:00', 'Siti Nurhaliza', 'Admin Gudang', 'Master Gudang', 'GD-OLD01', 'update', 'Menonaktifkan gudang', '10.10.2.15',
            [['Status', 'Aktif', 'Nonaktif']]],
        [18, '09:15', 'Admin Produksi', 'Administrator', 'Production Trial', 'TRL-2026-0045', 'update', 'Trial ditahan karena deviasi', '10.10.1.10',
            [['Status', 'Berjalan', 'Ditahan'], ['Catatan deviasi', '-', 'Suhu filling tidak stabil']]],
        [25, '11:45', 'Rina Wulandari', 'R&D', 'BOM', 'BOM-LC-K01-R03', 'release', 'BOM di-release untuk produksi', '10.10.1.24',
            [['Status', 'Review QA', 'Released']]],
        [40, '10:00', 'Siti Nurhaliza', 'Admin Gudang', 'Master Gudang', 'GD-QRN01', 'create', 'Menambah gudang baru', '10.10.2.15',
            [['Kode gudang', '-', 'GD-QRN01'], ['Tipe', '-', 'Karantina']]],
    ];

    $logs = [];
    foreach ($raw as $r) {
        $d = now()->subDays($r[0]);
        $logs[] = [
            'date'  => $d->format('Y-m-d'),
            'show'  => $d->locale('id')->translatedFormat('d M Y'),
            'time'  => $r[1], 'user' => $r[2], 'role' => $r[3], 'module' => $r[4], 'doc' => $r[5],
            'action' => $r[6], 'desc' => $r[7], 'ip' => $r[8], 'changes' => $r[9],
        ];
    }

    $modules = array_values(array_unique(array_column($logs, 'module')));
    $users   = array_values(array_unique(array_column($logs, 'user')));
    sort($modules);
    sort($users);

    $total    = count($logs);
    $userCnt  = count(array_unique(array_column(array_filter($logs, fn ($l) => $l['action'] !== 'login_fail'), 'user')));
    $approvals = count(array_filter($logs, fn ($l) => in_array($l['action'], ['approve', 'release', 'reject'])));
    $failed   = count(array_filter($logs, fn ($l) => $l['action'] === 'login_fail'));
@endphp

<section class="page-head">
    <div>
        <h2>Audit Log</h2>
        <p>Rekam jejak seluruh aktivitas pengguna. Log hanya dapat dibaca, tidak bisa diubah atau dihapus.</p>
    </div>
    <div class="page-head__actions">
        <button type="button" class="btn btn--light"><i data-lucide="download" aria-hidden="true"></i><span>Export</span></button>
    </div>
</section>

<section class="kpi-grid" aria-label="Ringkasan audit log">
    <article class="kpi">
        <span class="kpi__icon kpi__icon--blue"><i data-lucide="history" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total Aktivitas</p>
            <p class="kpi__value">{{ $total }}</p>
            <p class="kpi__trend">Seluruh catatan</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--indigo"><i data-lucide="users" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Pengguna Aktif</p>
            <p class="kpi__value">{{ $userCnt }}</p>
            <p class="kpi__trend">Pernah melakukan aksi</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--green"><i data-lucide="badge-check" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Approval &amp; Release</p>
            <p class="kpi__value">{{ $approvals }}</p>
            <p class="kpi__trend">Approve, reject, release</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--amber"><i data-lucide="shield-alert" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Login Gagal</p>
            <p class="kpi__value">{{ $failed }}</p>
            <p class="kpi__trend kpi__trend--warn"><i data-lucide="clock" aria-hidden="true"></i>Perlu dipantau</p>
        </div>
    </article>
</section>

<section class="panel panel--fit">

    <div class="toolbar toolbar--filters">
        <div class="toolbar__right">
            <select class="select" id="fPeriod" aria-label="Periode">
                <option value="all">Semua periode</option>
                <option value="today">Hari ini</option>
                <option value="7">7 hari terakhir</option>
                <option value="30">30 hari terakhir</option>
                <option value="custom">Rentang kustom</option>
            </select>
            <div class="date-range" id="fRange" hidden>
                <input class="input input--sm" type="date" id="fFrom" aria-label="Dari tanggal">
                <span>s/d</span>
                <input class="input input--sm" type="date" id="fTo" aria-label="Sampai tanggal">
            </div>
            <select class="select" id="fModule" aria-label="Modul">
                <option value="">Semua modul</option>
                @foreach ($modules as $m)<option>{{ $m }}</option>@endforeach
            </select>
            <select class="select" id="fAction" aria-label="Aksi">
                <option value="">Semua aksi</option>
                @foreach ($actions as $key => $a)<option value="{{ $key }}">{{ $a[0] }}</option>@endforeach
            </select>
            <select class="select" id="fUser" aria-label="Pengguna">
                <option value="">Semua pengguna</option>
                @foreach ($users as $u)<option>{{ $u }}</option>@endforeach
            </select>
        </div>
        <div class="toolbar__right">
            <label class="search">
                <i data-lucide="search" aria-hidden="true"></i>
                <input type="search" id="fSearch" placeholder="Cari no. dokumen atau deskripsi..." aria-label="Cari audit log">
            </label>
            <button type="button" class="btn btn--light" id="fReset">Reset</button>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table" id="auditTable" data-today="{{ now()->format('Y-m-d') }}">
            <thead>
                <tr>
                    <th style="width:48px"><span class="sr-only">Detail</span></th>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Modul</th>
                    <th>Dokumen</th>
                    <th>Aksi</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody id="auditBody">
                @foreach ($logs as $i => $l)
                    @php [$aLabel, $aBadge] = $actions[$l['action']]; @endphp
                    <tr class="log-row" data-date="{{ $l['date'] }}" data-module="{{ $l['module'] }}" data-action="{{ $l['action'] }}" data-user="{{ $l['user'] }}"
                        data-search="{{ strtolower($l['doc'].' '.$l['desc'].' '.$l['user']) }}">
                        <td>
                            <button type="button" class="icon-btn icon-btn--sm log-toggle" aria-expanded="false" aria-controls="log-{{ $i }}" aria-label="Lihat detail perubahan">
                                <i data-lucide="chevron-right" aria-hidden="true"></i>
                            </button>
                        </td>
                        <td><span class="cell-title">{{ $l['show'] }}</span><small class="cell-sub">{{ $l['time'] }}</small></td>
                        <td><span class="cell-title">{{ $l['user'] }}</span><small class="cell-sub">{{ $l['role'] }}</small></td>
                        <td>{{ $l['module'] }}</td>
                        <td class="table__code">{{ $l['doc'] }}</td>
                        <td><span class="badge badge--{{ $aBadge }}">{{ $aLabel }}</span></td>
                        <td>{{ $l['desc'] }}</td>
                    </tr>
                    <tr class="log-detail" id="log-{{ $i }}" hidden>
                        <td colspan="7">
                            <div class="log-detail__grid">
                                <dl class="log-meta">
                                    <div><dt>Alamat IP</dt><dd>{{ $l['ip'] }}</dd></div>
                                    <div><dt>Peran</dt><dd>{{ $l['role'] }}</dd></div>
                                    <div><dt>ID Log</dt><dd>LOG-{{ str_pad(count($logs) - $i, 6, '0', STR_PAD_LEFT) }}</dd></div>
                                </dl>
                                @if (count($l['changes']))
                                    <table class="diff">
                                        <thead><tr><th>Field</th><th>Nilai Lama</th><th>Nilai Baru</th></tr></thead>
                                        <tbody>
                                            @foreach ($l['changes'] as $c)
                                                <tr>
                                                    <td>{{ $c[0] }}</td>
                                                    <td class="diff__old">{{ $c[1] }}</td>
                                                    <td class="diff__new">{{ $c[2] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="log-none">Tidak ada perubahan data pada aktivitas ini.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="empty" id="auditEmpty" hidden>
            <i data-lucide="search-x" aria-hidden="true"></i>
            <p>Tidak ada catatan yang cocok dengan filter.</p>
        </div>
    </div>

    <div class="panel__foot">
        <p id="auditCount">Menampilkan {{ $total }} dari {{ $total }} catatan</p>
        <p><i data-lucide="lock" aria-hidden="true" class="inline-icon"></i> Log bersifat hanya-baca</p>
    </div>

</section>

@endsection