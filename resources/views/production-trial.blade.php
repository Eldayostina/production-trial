@extends('layouts.app')

@section('page_title', 'Production Trial')
@section('breadcrumb_parent', 'Production')

@section('content')

@php
    // DATA DUMMY - nanti diganti data dari database (Controller)
    $statuses = [
        'scheduled' => ['Terjadwal', 'draft'],
        'running'   => ['Berjalan', 'run'],
        'qc'        => ['Menunggu QC', 'wait'],
        'finished'  => ['Selesai', 'ok'],
        'hold'      => ['Ditahan', 'fail'],
    ];

    // [no trial, produk, no batch, ref plan, line, mulai, tahap, progress, status]
    $trials = [
        ['TRL-2026-0048', 'Life Cat Pouch Tuna & Chicken 85 g', 'LCP01-261010-A', 'PP-2026-0018', 'Line Pouch 1',  '10 Okt 2026', 'Tahap 6/8 - Retort',          70,  'running'],
        ['TRL-2026-0047', 'Life Cat Pouch Mackerel 85 g',       'LCM01-261012-A', 'PP-2026-0017', 'Line Pouch 2',  '12 Okt 2026', 'Belum dimulai',               0,   'scheduled'],
        ['TRL-2026-0046', 'Life Cat Kaleng Tuna 400 g',         'LCK01-261006-A', 'PP-2026-0016', 'Line Kaleng 1', '06 Okt 2026', 'Tahap 8/8 - Inkubasi & QC',   100, 'qc'],
        ['TRL-2026-0045', 'Life Cat Pouch Tuna & Chicken 85 g', 'LCP01-261001-A', 'PP-2026-0015', 'Line Pouch 1',  '01 Okt 2026', 'Tahap 4/8 - Filling',         50,  'hold'],
        ['TRL-2026-0044', 'Life Cat Pouch Tuna & Chicken 85 g', 'LCP01-260925-A', 'PP-2026-0012', 'Line Pouch 1',  '25 Sep 2026', 'Selesai',                     100, 'finished'],
        ['TRL-2026-0043', 'Life Cat Kaleng Tuna 400 g',         'LCK01-260922-A', 'PP-2026-0011', 'Line Kaleng 1', '22 Sep 2026', 'Selesai',                     100, 'finished'],
    ];
@endphp

<section class="page-head">
    <div>
        <h2>Production Trial</h2>
        <p>Pelaksanaan trial berdasarkan Production Plan yang sudah disetujui</p>
    </div>
    <div class="page-head__actions">
        <button type="button" class="btn btn--light"><i data-lucide="download" aria-hidden="true"></i><span>Export</span></button>
        <button type="button" class="btn btn--primary" id="btnStartTrial"><i data-lucide="play" aria-hidden="true"></i><span>Mulai Trial dari Plan</span></button>
    </div>
</section>

<section class="kpi-grid" aria-label="Ringkasan production trial">
    <article class="kpi">
        <span class="kpi__icon kpi__icon--blue"><i data-lucide="flask-conical" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total Trial</p>
            <p class="kpi__value">48</p>
            <p class="kpi__trend">Oktober 2026</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--indigo"><i data-lucide="play-circle" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Sedang Berjalan</p>
            <p class="kpi__value">12</p>
            <p class="kpi__trend">Di 3 line produksi</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--amber"><i data-lucide="alert-triangle" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Ditahan / Deviasi</p>
            <p class="kpi__value">2</p>
            <p class="kpi__trend kpi__trend--warn"><i data-lucide="clock" aria-hidden="true"></i>Perlu keputusan</p>
        </div>
    </article>
    <article class="kpi">
        <span class="kpi__icon kpi__icon--green"><i data-lucide="clipboard-check" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Menunggu QC</p>
            <p class="kpi__value">5</p>
            <p class="kpi__trend">Inkubasi &amp; pengujian</p>
        </div>
    </article>
</section>

<section class="panel panel--fit">

    <div class="toolbar">
        <div class="tabs" role="tablist" aria-label="Filter status">
            <button type="button" class="tabs__btn is-active" data-filter="all">Semua</button>
            <button type="button" class="tabs__btn" data-filter="scheduled">Terjadwal</button>
            <button type="button" class="tabs__btn" data-filter="running">Berjalan</button>
            <button type="button" class="tabs__btn" data-filter="qc">Menunggu QC</button>
            <button type="button" class="tabs__btn" data-filter="finished">Selesai</button>
        </div>

        <div class="toolbar__right">
            <label class="search">
                <i data-lucide="search" aria-hidden="true"></i>
                <input type="search" id="planSearch" placeholder="Cari no. trial, batch, atau produk..." aria-label="Cari production trial">
            </label>
            <select class="select" aria-label="Filter line">
                <option>Semua Line</option>
                <option>Line Pouch 1</option>
                <option>Line Pouch 2</option>
                <option>Line Kaleng 1</option>
            </select>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table" id="planTable">
            <thead>
                <tr>
                    <th>No. Trial</th>
                    <th>Produk</th>
                    <th>No. Batch</th>
                    <th>Ref. Plan</th>
                    <th>Line</th>
                    <th>Mulai</th>
                    <th>Progress Tahap</th>
                    <th>Status</th>
                    <th class="table__right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trials as $t)
                    @php [$label, $badge] = $statuses[$t[8]]; @endphp
                    <tr data-status="{{ $t[8] }}" data-search="{{ strtolower($t[0].' '.$t[1].' '.$t[2]) }}">
                        <td class="table__code"><a href="{{ route('production-trial.show', $t[0]) }}">{{ $t[0] }}</a></td>
                        <td><span class="cell-title">{{ $t[1] }}</span></td>
                        <td>{{ $t[2] }}</td>
                        <td>{{ $t[3] }}</td>
                        <td>{{ $t[4] }}</td>
                        <td>{{ $t[5] }}</td>
                        <td>
                            <div class="progress" role="progressbar" aria-valuenow="{{ $t[7] }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress__track"><div class="progress__fill" style="width: {{ $t[7] }}%"></div></div>
                                <span class="progress__text">{{ $t[7] }}%</span>
                            </div>
                            <small class="cell-sub">{{ $t[6] }}</small>
                        </td>
                        <td><span class="badge badge--{{ $badge }}">{{ $label }}</span></td>
                        <td>
                            <div class="table__actions">
                                <a href="{{ route('production-trial.show', $t[0]) }}" class="icon-btn icon-btn--sm" aria-label="Buka trial"><i data-lucide="eye" aria-hidden="true"></i></a>
                                <button type="button" class="icon-btn icon-btn--sm" aria-label="Lainnya"><i data-lucide="more-vertical" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="empty" id="planEmpty" hidden>
            <i data-lucide="search-x" aria-hidden="true"></i>
            <p>Tidak ada production trial yang cocok.</p>
        </div>
    </div>

    <div class="panel__foot">
        <p>Menampilkan 1-6 dari 48 data</p>
        <nav class="pagination" aria-label="Halaman">
            <button type="button" class="pagination__btn" disabled aria-label="Sebelumnya"><i data-lucide="chevron-left" aria-hidden="true"></i></button>
            <button type="button" class="pagination__btn is-active" aria-current="page">1</button>
            <button type="button" class="pagination__btn">2</button>
            <button type="button" class="pagination__btn" aria-label="Berikutnya"><i data-lucide="chevron-right" aria-hidden="true"></i></button>
        </nav>
    </div>

</section>

{{-- ===== MODAL: Mulai Trial dari Plan ===== --}}
<div class="modal" id="startTrialModal" role="dialog" aria-modal="true" aria-labelledby="startTrialTitle" hidden>

    <div class="modal__backdrop" data-modal-close></div>

    <div class="modal__dialog">
        <header class="modal__head">
            <div>
                <h3 id="startTrialTitle">Mulai Trial dari Production Plan</h3>
                <p class="modal__sub">Pilih plan yang sudah disetujui, lalu sistem akan membuat Trial baru.</p>
            </div>
            <button type="button" class="icon-btn" data-modal-close aria-label="Tutup">
                <i data-lucide="x" aria-hidden="true"></i>
            </button>
        </header>

        {{-- STEPPER MODAL (2 langkah) --}}
        <ol class="modal__steps" id="startTrialSteps">
            <li class="modal__step is-active" data-step="1">
                <span class="modal__step-dot">1</span>
                <span class="modal__step-label">Pilih Plan</span>
            </li>
            <li class="modal__step" data-step="2">
                <span class="modal__step-dot">2</span>
                <span class="modal__step-label">Konfirmasi</span>
            </li>
        </ol>

        {{-- STEP 1: PILIH PLAN --}}
        <div class="modal__body" data-step-panel="1">
            <div class="alert alert--warn" style="margin-bottom:12px">
                <i data-lucide="info" aria-hidden="true"></i>
                <p>Hanya plan dengan status <strong>Disetujui</strong> yang bisa dijalankan. Plan lain ditampilkan sebagai referensi.</p>
            </div>

            <div class="table-wrap">
                <table class="table table--form" id="trialPlanTable">
                    <thead>
                        <tr>
                            <th style="width:36px"></th>
                            <th>No. Plan</th>
                            <th>Produk</th>
                            <th>Target</th>
                            <th>Line</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-status="approved">
                            <td><input type="radio" name="planChoice" value="PP-2026-0014" data-line="Line Pouch 2" data-product="Life Cat Pouch Mackerel 85 g" data-target="4.000 pouch"></td>
                            <td class="table__code">PP-2026-0014</td>
                            <td>Life Cat Pouch Mackerel 85 g</td>
                            <td>4.000 pouch</td>
                            <td>Line Pouch 2</td>
                            <td>15 Okt - 20 Okt 2026</td>
                            <td><span class="badge badge--done">Disetujui</span></td>
                        </tr>
                        <tr data-status="approved">
                            <td><input type="radio" name="planChoice" value="PP-2026-0012" data-line="Line Pouch 1" data-product="Life Cat Pouch Tuna &amp; Chicken 85 g" data-target="2.500 pouch"></td>
                            <td class="table__code">PP-2026-0012</td>
                            <td>Life Cat Pouch Tuna &amp; Chicken 85 g</td>
                            <td>2.500 pouch</td>
                            <td>Line Pouch 1</td>
                            <td>25 Sep - 28 Sep 2026</td>
                            <td><span class="badge badge--done">Disetujui</span></td>
                        </tr>
                        <tr data-status="approval">
                            <td><input type="radio" name="planChoice" value="PP-2026-0017" disabled data-line="Line Pouch 2" data-product="Life Cat Pouch Mackerel 85 g" data-target="3.000 pouch"></td>
                            <td class="table__code">PP-2026-0017</td>
                            <td>Life Cat Pouch Mackerel 85 g</td>
                            <td>3.000 pouch</td>
                            <td>Line Pouch 2</td>
                            <td>12 Okt - 15 Okt 2026</td>
                            <td><span class="badge badge--wait">Menunggu Approval</span></td>
                        </tr>
                        <tr data-status="draft">
                            <td><input type="radio" name="planChoice" value="PP-2026-0013" disabled data-line="Line Kaleng 1" data-product="Life Cat Kaleng Tuna 400 g" data-target="1.500 kaleng"></td>
                            <td class="table__code">PP-2026-0013</td>
                            <td>Life Cat Kaleng Tuna 400 g</td>
                            <td>1.500 kaleng</td>
                            <td>Line Kaleng 1</td>
                            <td>20 Okt - 23 Okt 2026</td>
                            <td><span class="badge badge--draft">Draft</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- STEP 2: KONFIRMASI --}}
        <div class="modal__body" data-step-panel="2" hidden>
            <div class="summary" style="padding:0">
                <div><dt>Production Plan</dt><dd id="confirmPlan">-</dd></div>
                <div><dt>Produk</dt><dd id="confirmProduct">-</dd></div>
                <div><dt>Target</dt><dd id="confirmTarget">-</dd></div>
                <div><dt>Line</dt><dd id="confirmLine">-</dd></div>
            </div>

            <div class="alert" style="background:#EFF6FF;border-bottom:1px solid #BFDBFE;color:#1E40AF;margin-top:12px">
                <i data-lucide="info" aria-hidden="true"></i>
                <p>Nomor Trial &amp; No. Batch akan dibuat otomatis oleh sistem saat tombol <strong>Mulai Trial</strong> ditekan. Anda akan diarahkan ke halaman eksekusi trial.</p>
            </div>
        </div>

        <footer class="modal__foot">
            <button type="button" class="btn btn--light" data-modal-close>Batal</button>
            <div class="modal__foot-right">
                <button type="button" class="btn btn--light" id="modalBack" hidden>
                    <i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span>
                </button>
                <button type="button" class="btn btn--primary" id="modalNext">
                    <span>Lanjut</span><i data-lucide="arrow-right" aria-hidden="true"></i>
                </button>
                <button type="button" class="btn btn--primary" id="modalStart" hidden>
                    <i data-lucide="play" aria-hidden="true"></i><span>Mulai Trial</span>
                </button>
            </div>
        </footer>
    </div>
</div>

@endsection