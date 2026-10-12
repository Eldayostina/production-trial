@extends('layouts.app')

@section('page_title', 'Detail Stock Transfer')
@section('breadcrumb_parent', 'Stock Transfer')

@section('content')

@php
    // DATA DUMMY - nomor di URL belum dipakai untuk query.
    $stages = [
        ['Draft',                    'done'],
        ['Menunggu Approval',        'done'],
        ['Disetujui & Siap Kirim',   'done'],
        ['Dikirim',                  'current'],
        ['Diterima di Area Produksi','pending'],
    ];

    // [kode, nama, batch, expiry, uom, qty request, qty kirim, qty terima]
    $items = [
        ['RM-1001', 'Daging Tuna Cooked Flake',       'LOT-241001', '20 Okt 2026', 'kg',  150.0, 150.0, 149.5],
        ['RM-1005', 'Daging Ayam Giling',             'LOT-241005', '22 Okt 2026', 'kg',  100.0, 100.0, 100.0],
        ['RM-1010', 'Hati Ayam',                      'LOT-241010', '25 Okt 2026', 'kg',  40.0,  40.0,  40.0 ],
        ['RM-2001', 'Tepung Tapioka',                 'LOT-242001', '15 Des 2026', 'kg',  15.0,  15.0,  15.0 ],
        ['RM-2010', 'Guar Gum',                       'LOT-242010', '10 Jan 2027', 'kg',  4.0,   4.0,   4.0  ],
        ['RM-2020', 'Taurine',                        'LOT-242020', '01 Feb 2027', 'kg',  0.5,   0.5,   0.5  ],
        ['RM-2021', 'Premix Vitamin & Mineral Kucing','LOT-242021', '10 Feb 2027', 'kg',  3.0,   3.0,   3.0  ],
        ['PK-3001', 'Retort Pouch 85 g Life Cat Tuna','LOT-243001', '30 Jun 2027', 'pcs', 5250,  5250,  5250 ],
    ];

    $totalReq = 0; $totalSent = 0; $totalRecv = 0;
    foreach ($items as $it) { $totalReq += $it[5]; $totalSent += $it[6]; $totalRecv += $it[7]; }
@endphp

<section class="page-head">
    <div>
        <h2>{{ $no }} <span class="badge badge--run">Dikirim</span></h2>
        <p>Cold Storage &rarr; Area Produksi Line Pouch 1 &middot; Ref. PP-2026-0018</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('stock-transfer') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
        <button type="button" class="btn btn--light" data-st-action="hold"><i data-lucide="pause-circle" aria-hidden="true"></i><span>Tahan Transfer</span></button>
        <button type="button" class="btn btn--primary" data-st-action="receive"><i data-lucide="package-check" aria-hidden="true"></i><span>Konfirmasi Diterima</span></button>
    </div>
</section>

<div id="stPage">

    {{-- Informasi transfer --}}
    <section class="panel panel--fit">
        <dl class="info-grid">
            <div><dt>Tanggal Transfer</dt><dd>10 Okt 2026</dd></div>
            <div><dt>Prioritas</dt><dd>Normal</dd></div>
            <div><dt>Ref. Production Plan</dt><dd class="table__code">PP-2026-0018</dd></div>
            <div><dt>Ref. Trial</dt><dd class="table__code">TRL-2026-0048</dd></div>
            <div><dt>Gudang Asal</dt><dd>Cold Storage</dd></div>
            <div><dt>Gudang Tujuan</dt><dd>Area Produksi Line Pouch 1</dd></div>
            <div><dt>PIC Gudang</dt><dd>Joko Susilo</dd></div>
            <div><dt>PIC Produksi</dt><dd>Budi Santoso</dd></div>
        </dl>
    </section>

    {{-- Stepper tahapan --}}
    <section class="panel panel--fit">
        <div class="panel__head"><h3>Tahapan Transfer</h3></div>
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

    <div class="form-layout">
        <div class="form-main">

            {{-- Daftar item --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Item Transfer</h3>
                    <span class="hint-inline">Selisih &gt; 1% otomatis ditandai</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Material</th>
                                <th>Batch / Lot</th>
                                <th>Expiry</th>
                                <th class="table__right">Qty Request</th>
                                <th class="table__right">Qty Kirim</th>
                                <th class="table__right">Qty Terima</th>
                                <th class="table__right">Selisih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $it)
                                @php
                                    $diff = $it[7] - $it[6];
                                    $pct  = $it[6] > 0 ? ($diff / $it[6]) * 100 : 0;
                                    $cls  = abs($pct) > 1 ? ($diff < 0 ? 'is-over' : 'is-under') : '';
                                @endphp
                                <tr>
                                    <td class="table__code">{{ $it[0] }}</td>
                                    <td>{{ $it[1] }}</td>
                                    <td>{{ $it[2] }}</td>
                                    <td>{{ $it[3] }}</td>
                                    <td class="table__right">{{ number_format($it[5], 2, ',', '.') }} {{ $it[4] }}</td>
                                    <td class="table__right">{{ number_format($it[6], 2, ',', '.') }} {{ $it[4] }}</td>
                                    <td class="table__right">{{ number_format($it[7], 2, ',', '.') }} {{ $it[4] }}</td>
                                    <td class="table__right {{ $cls }}">
                                        {{ ($diff > 0 ? '+' : '') . number_format($diff, 2, ',', '.') }} {{ $it[4] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Catatan & aktivitas --}}
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Catatan</h3></div>
                <div class="panel__body">
                    <p>Material dikirim bertahap. Batch LOT-241001 sisa 0,5 kg untuk penyesuaian timbangan di area produksi. Pastikan cold chain terjaga selama pengiriman.</p>
                </div>
            </section>
        </div>

        {{-- Kolom kanan --}}
        <aside class="form-side">
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Ringkasan</h3></div>
                <dl class="summary">
                    <div><dt>Jumlah Item</dt><dd>{{ count($items) }}</dd></div>
                    <div><dt>Total Request</dt><dd>{{ number_format($totalReq, 1, ',', '.') }}</dd></div>
                    <div><dt>Total Kirim</dt><dd>{{ number_format($totalSent, 1, ',', '.') }}</dd></div>
                    <div><dt>Total Terima</dt><dd>{{ number_format($totalRecv, 1, ',', '.') }}</dd></div>
                    <div><dt>Status</dt><dd><span class="badge badge--run">Dikirim</span></dd></div>
                </dl>
            </section>

            <section class="panel panel--fit">
                <div class="panel__head"><h3>Riwayat Aktivitas</h3></div>
                <ol class="flow">
                    <li class="flow__item is-current"><span class="flow__dot">4</span>
                        <div><strong>Dikirim ke area produksi</strong><small>10 Okt 2026, 08.20 &middot; Joko Susilo</small></div>
                    </li>
                    <li class="flow__item"><span class="flow__dot">3</span>
                        <div><strong>Disetujui Manager Produksi</strong><small>10 Okt 2026, 07.45 &middot; Manager Produksi</small></div>
                    </li>
                    <li class="flow__item"><span class="flow__dot">2</span>
                        <div><strong>Review Gudang Asal</strong><small>10 Okt 2026, 07.10 &middot; Supervisor Gudang</small></div>
                    </li>
                    <li class="flow__item"><span class="flow__dot">1</span>
                        <div><strong>Transfer dibuat</strong><small>10 Okt 2026, 06.50 &middot; Admin Produksi</small></div>
                    </li>
                </ol>
            </section>
        </aside>
    </div>

</div>

@endsection