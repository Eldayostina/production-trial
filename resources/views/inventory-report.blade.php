@extends('layouts.app')

@section('page_title', 'Inventory Report')
@section('breadcrumb_parent', 'Reports')

@section('content')

@php
    // DATA DUMMY - nanti diganti data dari database (Controller)

    // Rekap per kategori: [kategori, jumlah SKU, nilai stok (Rp), % dari total]
    $perCategory = [
        ['Bahan Baku',      45, 1850000000, 65.1],
        ['Bahan Kering',    32, 420000000,  14.8],
        ['Bahan Penolong',  18, 210000000,  7.4],
        ['Kemasan',         48, 360000000,  12.7],
    ];

    // Nilai stok per gudang: [gudang, nilai (Rp), jumlah SKU]
    $perWarehouse = [
        ['Cold Storage',           1850000000, 45],
        ['Gudang Bahan Kering',    420000000,  32],
        ['Gudang Bahan Penolong',  210000000,  18],
        ['Gudang Kemasan',         360000000,  48],
    ];

    // Ringkasan pergerakan: [gudang, saldo awal, masuk, keluar, adjust, saldo akhir]
    $movements = [
        ['Cold Storage',           1180.5, 1450.2, 1310.8, -2.4, 1317.5],
        ['Gudang Bahan Kering',    820.0,  520.5,  480.3,  0.0,  860.2],
        ['Gudang Bahan Penolong',  185.4,  90.2,   105.6, -0.6,  169.4],
        ['Gudang Kemasan',         95500,  45000,  38500,  0,    102000],
    ];

    // Item di bawah minimum: [kode, nama, gudang, satuan, stok, min, kurang]
    $belowMin = [
        ['RM-1010', 'Hati Ayam',                       'Cold Storage',         'kg',  45.0,  60,   15.0],
        ['RM-1020', 'Daging Ikan Makarel',             'Cold Storage',         'kg',  18.0,  80,   62.0],
        ['RM-2010', 'Guar Gum',                        'Gudang Bahan Kering',  'kg',  6.0,   10,   4.0],
        ['RM-2011', 'Karagenan',                       'Gudang Bahan Kering',  'kg',  4.0,   8,    4.0],
        ['RM-2020', 'Taurine',                         'Gudang Bahan Kering',  'kg',  2.0,   5,    3.0],
        ['PK-3201', 'Kaleng 401 Lacquer Putih',        'Gudang Kemasan',       'pcs', 8000,  10000,2000],
        ['PK-3202', 'Tutup Kaleng Easy Open',          'Gudang Kemasan',       'pcs', 0,     10000,10000],
    ];

    // Item expiry dekat: [kode, nama, batch, gudang, expiry, sisa qty, sisa hari]
    $nearExpiry = [
        ['RM-1002', 'Daging Tuna Chunk',        'LOT-241002', 'Cold Storage', '18 Okt 2026', '900 kg',  8],
        ['RM-1020', 'Daging Ikan Makarel',      'LOT-241020', 'Cold Storage', '15 Okt 2026', '18 kg',   5],
        ['RM-1001', 'Daging Tuna Cooked Flake', 'LOT-241001', 'Cold Storage', '20 Okt 2026', '120 kg',  10],
    ];

    // KPI agregat
    $totalValue = 0;
    foreach ($perCategory as $c) { $totalValue += $c[2]; }
    $totalSku = 143;
    $belowMinCount = count($belowMin);
    $nearExpiryCount = count($nearExpiry);
@endphp

<section class="page-head">
    <div>
        <h2>Inventory Report</h2>
        <p>Rekap stok, nilai, dan pergerakan material periode Oktober 2026</p>
    </div>
    <div class="page-head__actions">
        <button type="button" class="btn btn--light"><i data-lucide="calendar" aria-hidden="true"></i><span>Oktober 2026</span></button>
        <button type="button" class="btn btn--light" id="reportExport"><i data-lucide="download" aria-hidden="true"></i><span>Export Excel</span></button>
        <button type="button" class="btn btn--primary" id="reportPrint"><i data-lucide="printer" aria-hidden="true"></i><span>Cetak PDF</span></button>
    </div>
</section>

{{-- KPI --}}
<section class="kpi-grid" aria-label="Indikator inventory">
    <article class="kpi">
        <span class="kpi__icon kpi__icon--indigo"><i data-lucide="banknote" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total Nilai Stok</p>
            <p class="kpi__value">Rp {{ number_format($totalValue / 1000000000, 2, ',', '.') }} M</p>
            <p class="kpi__trend kpi__trend--up"><i data-lucide="trending-up" aria-hidden="true"></i>+5,2% dari bulan lalu</p>
        </div>
    </article>

    <article class="kpi">
        <span class="kpi__icon kpi__icon--blue"><i data-lucide="package" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Total SKU Aktif</p>
            <p class="kpi__value">{{ $totalSku }}</p>
            <p class="kpi__trend">Di 4 gudang</p>
        </div>
    </article>

    <article class="kpi">
        <span class="kpi__icon kpi__icon--amber"><i data-lucide="alert-triangle" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Di Bawah Minimum</p>
            <p class="kpi__value">{{ $belowMinCount }}</p>
            <p class="kpi__trend kpi__trend--warn"><i data-lucide="clock" aria-hidden="true"></i>Perlu reorder</p>
        </div>
    </article>

    <article class="kpi">
        <span class="kpi__icon kpi__icon--green"><i data-lucide="calendar-clock" aria-hidden="true"></i></span>
        <div class="kpi__body">
            <p class="kpi__label">Expiry ≤ 30 Hari</p>
            <p class="kpi__value">{{ $nearExpiryCount }}</p>
            <p class="kpi__trend">Batch perlu diprioritaskan (FIFO)</p>
        </div>
    </article>
</section>

{{-- Grafik --}}
<section class="grid grid--chart">
    <article class="panel panel--fit">
        <div class="panel__head panel__head--row">
            <h3>Nilai Stok per Gudang</h3>
            <span class="hint-inline">Dalam juta rupiah</span>
        </div>
        <div class="panel__body">
            <div class="bars" role="img" aria-label="Grafik batang nilai stok per gudang">
                <div class="bars__col"><span class="bars__val">1.850</span><div class="bars__bar" style="--h:96%;--pass:96;--fail:0"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Cold Storage</span></div>
                <div class="bars__col"><span class="bars__val">420</span><div class="bars__bar" style="--h:24%;--pass:24;--fail:0"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Bahan Kering</span></div>
                <div class="bars__col"><span class="bars__val">210</span><div class="bars__bar" style="--h:12%;--pass:12;--fail:0"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Bahan Penolong</span></div>
                <div class="bars__col"><span class="bars__val">360</span><div class="bars__bar" style="--h:20%;--pass:20;--fail:0"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Kemasan</span></div>
            </div>
        </div>
    </article>

    <article class="panel panel--fit">
        <div class="panel__head"><h3>Komposisi Nilai Stok</h3></div>
        <div class="panel__body donut-wrap">
            <div class="donut" role="img" aria-label="Komposisi nilai stok per kategori">
                <div class="donut__center"><strong>2,84</strong><span>Miliar Rp</span></div>
            </div>
            <ul class="status-list">
                <li><i class="dot dot--done"></i><span>Bahan Baku</span><strong>65,1%</strong></li>
                <li><i class="dot dot--run"></i><span>Bahan Kering</span><strong>14,8%</strong></li>
                <li><i class="dot dot--wait"></i><span>Kemasan</span><strong>12,7%</strong></li>
                <li><i class="dot dot--fail"></i><span>Bahan Penolong</span><strong>7,4%</strong></li>
            </ul>
        </div>
    </article>
</section>

{{-- Rekap per kategori --}}
<section class="panel panel--fit" style="margin-bottom:20px">
    <div class="panel__head panel__head--row">
        <h3>Rekap Nilai Stok per Kategori</h3>
        <span class="hint-inline">Oktober 2026</span>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th class="table__right">Jumlah SKU</th>
                    <th class="table__right">Nilai Stok</th>
                    <th class="table__right">% dari Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($perCategory as $c)
                    <tr>
                        <td><span class="cell-title">{{ $c[0] }}</span></td>
                        <td class="table__right">{{ $c[1] }}</td>
                        <td class="table__right">Rp {{ number_format($c[2] / 1000000, 0, ',', '.') }} jt</td>
                        <td class="table__right">{{ number_format($c[3], 1, ',', '.') }}%</td>
                    </tr>
                @endforeach
                <tr class="table__row--total">
                    <td>Total</td>
                    <td class="table__right">{{ $totalSku }}</td>
                    <td class="table__right">Rp {{ number_format($totalValue / 1000000, 0, ',', '.') }} jt</td>
                    <td class="table__right">100,0%</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

{{-- Pergerakan stok --}}
<section class="panel panel--fit" style="margin-bottom:20px">
    <div class="panel__head panel__head--row">
        <h3>Ringkasan Pergerakan Stok</h3>
        <span class="hint-inline">Saldo awal + masuk − keluar + adjust = saldo akhir</span>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Gudang</th>
                    <th class="table__right">Saldo Awal</th>
                    <th class="table__right">Masuk</th>
                    <th class="table__right">Keluar</th>
                    <th class="table__right">Adjust</th>
                    <th class="table__right">Saldo Akhir</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movements as $m)
                    <tr>
                        <td>{{ $m[0] }}</td>
                        <td class="table__right">{{ number_format($m[1], 1, ',', '.') }}</td>
                        <td class="table__right is-in">+{{ number_format($m[2], 1, ',', '.') }}</td>
                        <td class="table__right is-over">−{{ number_format($m[3], 1, ',', '.') }}</td>
                        <td class="table__right">{{ number_format($m[4], 1, ',', '.') }}</td>
                        <td class="table__right"><strong>{{ number_format($m[5], 1, ',', '.') }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

{{-- Item di bawah minimum & expiry dekat --}}
<section class="grid grid--table">
    <article class="panel panel--fit">
        <div class="panel__head panel__head--row">
            <h3>Item Di Bawah Minimum</h3>
            <span class="badge badge--wait">{{ $belowMinCount }} item</span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Material</th>
                        <th>Gudang</th>
                        <th class="table__right">Stok</th>
                        <th class="table__right">Min.</th>
                        <th class="table__right">Kurang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($belowMin as $b)
                        <tr>
                            <td class="table__code">{{ $b[0] }}</td>
                            <td>{{ $b[1] }}</td>
                            <td>{{ $b[2] }}</td>
                            <td class="table__right">{{ number_format($b[4], 2, ',', '.') }} {{ $b[3] }}</td>
                            <td class="table__right">{{ number_format($b[5], 0, ',', '.') }} {{ $b[3] }}</td>
                            <td class="table__right is-over"><strong>{{ number_format($b[6], 2, ',', '.') }} {{ $b[3] }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>

    <article class="panel panel--fit">
        <div class="panel__head panel__head--row">
            <h3>Item Expiry ≤ 30 Hari</h3>
            <span class="badge badge--wait">{{ $nearExpiryCount }} batch</span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Material</th>
                        <th>Batch</th>
                        <th>Expiry</th>
                        <th class="table__right">Sisa Qty</th>
                        <th class="table__right">Sisa Hari</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($nearExpiry as $n)
                        <tr>
                            <td class="table__code">{{ $n[0] }}</td>
                            <td>{{ $n[1] }}</td>
                            <td class="table__code">{{ $n[2] }}</td>
                            <td>{{ $n[4] }}</td>
                            <td class="table__right">{{ $n[5] }}</td>
                            <td class="table__right {{ $n[6] <= 7 ? 'is-over' : 'is-under' }}"><strong>{{ $n[6] }} hari</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>
</section>

{{-- Ringkasan eksekutif --}}
<section class="panel panel--fit" style="margin-top:20px">
    <div class="panel__head"><h3>Ringkasan Eksekutif</h3></div>
    <ul class="todo">
        <li class="todo__item">
            <span class="todo__icon todo__icon--warn"><i data-lucide="alert-triangle" aria-hidden="true"></i></span>
            <div>
                <strong>7 item di bawah stok minimum</strong>
                <small>Cold Storage: 2 item, Bahan Kering: 3 item, Kemasan: 2 item. Perlu reorder.</small>
            </div>
        </li>
        <li class="todo__item">
            <span class="todo__icon todo__icon--warn"><i data-lucide="clock" aria-hidden="true"></i></span>
            <div>
                <strong>3 batch expiry ≤ 30 hari</strong>
                <small>Prioritaskan pemakaian (FIFO) untuk batch LOT-241020 (5 hari).</small>
            </div>
        </li>
        <li class="todo__item">
            <span class="todo__icon todo__icon--info"><i data-lucide="trending-up" aria-hidden="true"></i></span>
            <div>
                <strong>Nilai stok naik 5,2%</strong>
                <small>Kenaikan signifikan di Cold Storage karena penerimaan tuna bulan ini.</small>
            </div>
        </li>
        <li class="todo__item">
            <span class="todo__icon todo__icon--info"><i data-lucide="package" aria-hidden="true"></i></span>
            <div>
                <strong>Kemasan memiliki nilai stok terbesar kedua</strong>
                <small>48 SKU, Rp 360 jt. Perlu perhatian pada reorder point kaleng.</small>
            </div>
        </li>
    </ul>
</section>

@endsection