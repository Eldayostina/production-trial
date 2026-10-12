@extends('layouts.app')

@section('page_title', 'Detail Stok Material')
@section('breadcrumb_parent', 'Stock Overview')

@section('content')

@php
    // DATA DUMMY - nomor/kode di URL belum dipakai untuk query.

    // [gudang, stok, stok minimum, status]
    $perWarehouse = [
        ['Cold Storage',            120.0, 100, 'safe'],
        ['Gudang Bahan Kering',     0.0,   0,   'out'],
        ['Gudang Bahan Penolong',   0.0,   0,   'out'],
        ['Gudang Kemasan',          0.0,   0,   'out'],
    ];

    // [batch, gudang, expiry, qty, umur hari, status]
    $batches = [
        ['LOT-241001', 'Cold Storage', '20 Okt 2026', 120.0, 10,  'expiry'],
        ['LOT-240918', 'Cold Storage', '28 Des 2026', 0.0,   0,   'out'],
    ];

    // [tanggal, tipe, ref, gudang, qty masuk, qty keluar, saldo, user]
    $movements = [
        ['10 Okt 2026', 'Transfer Masuk', 'ST-2026-0087', 'Cold Storage', 150.0, 0,     270.0, 'Joko Susilo'],
        ['09 Okt 2026', 'Pemakaian Trial','TRL-2026-0046','Cold Storage', 0,     120.0, 120.0, 'Budi Santoso'],
        ['05 Okt 2026', 'Penerimaan',     'PO-2026-0142', 'Cold Storage', 200.0, 0,     240.0, 'Siti Rahma'],
        ['01 Okt 2026', 'Pemakaian Trial','TRL-2026-0044','Cold Storage', 0,     80.0,  40.0,  'Budi Santoso'],
    ];

    $totalStok = 0;
    foreach ($perWarehouse as $w) { $totalStok += $w[1]; }
@endphp

<section class="page-head">
    <div>
        <h2>{{ $code }} <span class="badge badge--ok">Aman</span></h2>
        <p>Daging Tuna Cooked Flake &middot; Bahan Baku &middot; Satuan kg</p>
    </div>
    <div class="page-head__actions">
        <a href="{{ route('stock-overview') }}" class="btn btn--light"><i data-lucide="arrow-left" aria-hidden="true"></i><span>Kembali</span></a>
        <a href="{{ route('stock-transfer.create') }}" class="btn btn--primary"><i data-lucide="arrow-left-right" aria-hidden="true"></i><span>Buat Stock Transfer</span></a>
    </div>
</section>

<div id="stockPage">

    {{-- Info material --}}
    <section class="panel panel--fit">
        <dl class="info-grid">
            <div><dt>Kode Material</dt><dd class="table__code">{{ $code }}</dd></div>
            <div><dt>Nama</dt><dd>Daging Tuna Cooked Flake</dd></div>
            <div><dt>Kategori</dt><dd>Bahan Baku</dd></div>
            <div><dt>Satuan</dt><dd>kg</dd></div>
            <div><dt>Stok Total</dt><dd>{{ number_format($totalStok, 2, ',', '.') }} kg</dd></div>
            <div><dt>Stok Minimum</dt><dd>100 kg</dd></div>
            <div><dt>Reorder Point</dt><dd>120 kg</dd></div>
            <div><dt>Lead Time Supplier</dt><dd>7 hari</dd></div>
        </dl>
    </section>

    <div class="form-layout">
        <div class="form-main">

            {{-- Stok per gudang --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Stok per Gudang</h3>
                    <span class="hint-inline">Total di semua gudang: {{ number_format($totalStok, 2, ',', '.') }} kg</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Gudang</th>
                                <th class="table__right">Stok</th>
                                <th class="table__right">Stok Minimum</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($perWarehouse as $w)
                                @php
                                    if ($w[1] <= 0) { $lbl = 'Habis';    $bd = 'fail'; }
                                    elseif ($w[1] < $w[2]) { $lbl = 'Menipis'; $bd = 'wait'; }
                                    else { $lbl = 'Aman'; $bd = 'ok'; }
                                @endphp
                                <tr>
                                    <td>{{ $w[0] }}</td>
                                    <td class="table__right">{{ number_format($w[1], 2, ',', '.') }} kg</td>
                                    <td class="table__right">{{ number_format($w[2], 0, ',', '.') }} kg</td>
                                    <td><span class="badge badge--{{ $bd }}">{{ $lbl }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Daftar batch (FIFO) --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Daftar Batch (FIFO)</h3>
                    <span class="hint-inline">Batch terdekat expiry dipakai lebih dulu</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Batch / Lot</th>
                                <th>Gudang</th>
                                <th>Expiry</th>
                                <th class="table__right">Sisa Qty</th>
                                <th class="table__right">Umur (hari)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($batches as $b)
                                @php
                                    if ($b[3] <= 0) { $lbl = 'Habis'; $bd = 'draft'; }
                                    elseif ($b[5] === 'expiry') { $lbl = 'Expiry Dekat'; $bd = 'wait'; }
                                    else { $lbl = 'Aman'; $bd = 'ok'; }
                                @endphp
                                <tr>
                                    <td class="table__code">{{ $b[0] }}</td>
                                    <td>{{ $b[1] }}</td>
                                    <td>{{ $b[2] }}</td>
                                    <td class="table__right">{{ number_format($b[3], 2, ',', '.') }} kg</td>
                                    <td class="table__right">{{ $b[4] }} hari</td>
                                    <td><span class="badge badge--{{ $bd }}">{{ $lbl }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Riwayat pergerakan --}}
            <section class="panel panel--fit">
                <div class="panel__head panel__head--row">
                    <h3>Riwayat Pergerakan Stok</h3>
                    <span class="hint-inline">15 transaksi terakhir</span>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>Ref.</th>
                                <th>Gudang</th>
                                <th class="table__right">Masuk</th>
                                <th class="table__right">Keluar</th>
                                <th class="table__right">Saldo</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movements as $m)
                                <tr>
                                    <td>{{ $m[0] }}</td>
                                    <td><span class="badge badge--{{ $m[4] > 0 ? 'ok' : 'run' }}">{{ $m[1] }}</span></td>
                                    <td class="table__code">{{ $m[2] }}</td>
                                    <td>{{ $m[3] }}</td>
                                    <td class="table__right">{{ $m[4] > 0 ? number_format($m[4], 2, ',', '.') . ' kg' : '-' }}</td>
                                    <td class="table__right">{{ $m[5] > 0 ? number_format($m[5], 2, ',', '.') . ' kg' : '-' }}</td>
                                    <td class="table__right"><strong>{{ number_format($m[6], 2, ',', '.') }} kg</strong></td>
                                    <td>{{ $m[7] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        {{-- Kolom kanan --}}
        <aside class="form-side">
            <section class="panel panel--fit">
                <div class="panel__head"><h3>Ringkasan</h3></div>
                <dl class="summary">
                    <div><dt>Stok Total</dt><dd>{{ number_format($totalStok, 2, ',', '.') }} kg</dd></div>
                    <div><dt>Jumlah Gudang</dt><dd>{{ count($perWarehouse) }}</dd></div>
                    <div><dt>Jumlah Batch Aktif</dt><dd>{{ count($batches) }}</dd></div>
                    <div><dt>Batch Expiry Dekat</dt><dd>1</dd></div>
                    <div><dt>Status</dt><dd><span class="badge badge--ok">Aman</span></dd></div>
                </dl>
            </section>

            <section class="panel panel--fit">
                <div class="panel__head"><h3>Pemakaian Terakhir</h3></div>
                <ol class="flow">
                    <li class="flow__item is-current"><span class="flow__dot"><i data-lucide="flask-conical" aria-hidden="true"></i></span>
                        <div><strong>TRL-2026-0046</strong><small>09 Okt 2026 &middot; 120 kg dipakai</small></div>
                    </li>
                    <li class="flow__item"><span class="flow__dot"><i data-lucide="flask-conical" aria-hidden="true"></i></span>
                        <div><strong>TRL-2026-0044</strong><small>01 Okt 2026 &middot; 80 kg dipakai</small></div>
                    </li>
                    <li class="flow__item"><span class="flow__dot"><i data-lucide="package-plus" aria-hidden="true"></i></span>
                        <div><strong>Penerimaan PO-2026-0142</strong><small>05 Okt 2026 &middot; 200 kg masuk</small></div>
                    </li>
                </ol>
            </section>
        </aside>
    </div>

</div>

@endsection