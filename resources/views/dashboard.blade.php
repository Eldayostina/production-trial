@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('content')

    {{-- KONTEN --}}
                <main class="content">

            {{-- Judul halaman & aksi --}}
            <section class="page-head">
                <div>
                    <h2>Selamat datang, Admin Produksi</h2>
                    <p>Ringkasan aktivitas Production Trial &middot; Oktober 2026</p>
                </div>
                <div class="page-head__actions">
                    <button type="button" class="btn btn--light"><i data-lucide="calendar" aria-hidden="true"></i><span>Oktober 2026</span></button>
                </div>
            </section>

            {{-- KPI --}}
            <section class="kpi-grid" aria-label="Indikator utama">
                <article class="kpi">
                    <span class="kpi__icon kpi__icon--blue"><i data-lucide="clipboard-list" aria-hidden="true"></i></span>
                    <div class="kpi__body">
                        <p class="kpi__label">Production Plan Aktif</p>
                        <p class="kpi__value">18</p>
                        <p class="kpi__trend kpi__trend--up"><i data-lucide="trending-up" aria-hidden="true"></i>+3 dari bulan lalu</p>
                    </div>
                </article>

                <article class="kpi">
                    <span class="kpi__icon kpi__icon--indigo"><i data-lucide="flask-conical" aria-hidden="true"></i></span>
                    <div class="kpi__body">
                        <p class="kpi__label">Trial Berjalan</p>
                        <p class="kpi__value">12</p>
                        <p class="kpi__trend">Dari 48 trial bulan ini</p>
                    </div>
                </article>

                <article class="kpi">
                    <span class="kpi__icon kpi__icon--amber"><i data-lucide="arrow-left-right" aria-hidden="true"></i></span>
                    <div class="kpi__body">
                        <p class="kpi__label">Transfer Stock Pending</p>
                        <p class="kpi__value">7</p>
                        <p class="kpi__trend kpi__trend--warn"><i data-lucide="clock" aria-hidden="true"></i>2 lewat batas waktu</p>
                    </div>
                </article>

                <article class="kpi">
                    <span class="kpi__icon kpi__icon--green"><i data-lucide="check-circle-2" aria-hidden="true"></i></span>
                    <div class="kpi__body">
                        <p class="kpi__label">Trial Pass Rate</p>
                        <p class="kpi__value">87,5%</p>
                        <p class="kpi__trend kpi__trend--up"><i data-lucide="trending-up" aria-hidden="true"></i>+2,1% dari bulan lalu</p>
                    </div>
                </article>
            </section>

            {{-- Grafik --}}
            <section class="grid grid--chart">
                <article class="panel panel--fit">
                    <div class="panel__head panel__head--row">
                        <h3>Tren Trial per Bulan</h3>
                        <div class="legend">
                            <span class="legend__item"><i class="dot dot--pass"></i>Lulus</span>
                            <span class="legend__item"><i class="dot dot--fail"></i>Gagal</span>
                        </div>
                    </div>
                    <div class="panel__body">
                        <div class="bars" role="img" aria-label="Grafik batang jumlah trial Mei sampai Oktober 2026">
                            <div class="bars__col"><span class="bars__val">28</span><div class="bars__bar" style="--h:56%;--pass:50;--fail:6"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Mei</span></div>
                            <div class="bars__col"><span class="bars__val">34</span><div class="bars__bar" style="--h:68%;--pass:60;--fail:8"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Jun</span></div>
                            <div class="bars__col"><span class="bars__val">31</span><div class="bars__bar" style="--h:62%;--pass:55;--fail:7"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Jul</span></div>
                            <div class="bars__col"><span class="bars__val">40</span><div class="bars__bar" style="--h:80%;--pass:72;--fail:8"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Agu</span></div>
                            <div class="bars__col"><span class="bars__val">44</span><div class="bars__bar" style="--h:88%;--pass:79;--fail:9"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Sep</span></div>
                            <div class="bars__col"><span class="bars__val">48</span><div class="bars__bar" style="--h:96%;--pass:86;--fail:10"><i class="bars__fail"></i><i class="bars__pass"></i></div><span class="bars__label">Okt</span></div>
                        </div>
                    </div>
                </article>

                <article class="panel panel--fit">
                    <div class="panel__head"><h3>Status Trial</h3></div>
                    <div class="panel__body donut-wrap">
                        <div class="donut" role="img" aria-label="Distribusi status trial">
                            <div class="donut__center"><strong>48</strong><span>Total Trial</span></div>
                        </div>
                        <ul class="status-list">
                            <li><i class="dot dot--done"></i><span>Selesai</span><strong>27</strong></li>
                            <li><i class="dot dot--run"></i><span>Berjalan</span><strong>12</strong></li>
                            <li><i class="dot dot--wait"></i><span>Menunggu</span><strong>6</strong></li>
                            <li><i class="dot dot--fail"></i><span>Gagal</span><strong>3</strong></li>
                        </ul>
                    </div>
                </article>
            </section>

            {{-- Tabel & tindakan --}}
            <section class="grid grid--table">
                <article class="panel panel--fit">
                    <div class="panel__head panel__head--row">
                        <h3>Trial Terbaru</h3>
                        <a href="#" class="link">Lihat semua</a>
                    </div>
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No. Trial</th>
                                    <th>Produk</th>
                                    <th>Line</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td class="table__code">TRL-2026-0048</td><td>Bracket Assy A12</td><td>Line 1</td><td>10 Okt 2026</td><td><span class="badge badge--run">Berjalan</span></td></tr>
                                <tr><td class="table__code">TRL-2026-0047</td><td>Housing Cover B07</td><td>Line 3</td><td>09 Okt 2026</td><td><span class="badge badge--wait">Menunggu</span></td></tr>
                                <tr><td class="table__code">TRL-2026-0046</td><td>Shaft Gear C21</td><td>Line 2</td><td>08 Okt 2026</td><td><span class="badge badge--done">Selesai</span></td></tr>
                                <tr><td class="table__code">TRL-2026-0045</td><td>Plate Base D03</td><td>Line 1</td><td>07 Okt 2026</td><td><span class="badge badge--fail">Gagal</span></td></tr>
                                <tr><td class="table__code">TRL-2026-0044</td><td>Bushing E15</td><td>Line 4</td><td>06 Okt 2026</td><td><span class="badge badge--done">Selesai</span></td></tr>
                            </tbody>
                        </table>
                    </div>
                </article>

                <article class="panel panel--fit">
                    <div class="panel__head panel__head--row">
                        <h3>Perlu Tindakan</h3>
                        <span class="badge badge--wait">3</span>
                    </div>
                    <ul class="todo">
                        <li class="todo__item">
                            <span class="todo__icon todo__icon--warn"><i data-lucide="alert-triangle" aria-hidden="true"></i></span>
                            <div><strong>Transfer stock terlambat</strong><small>TRL-2026-0047 &middot; Gudang Bahan Baku</small></div>
                        </li>
                        <li class="todo__item">
                            <span class="todo__icon todo__icon--info"><i data-lucide="clipboard-check" aria-hidden="true"></i></span>
                            <div><strong>Hasil trial menunggu review</strong><small>TRL-2026-0046 &middot; Quality Control</small></div>
                        </li>
                        <li class="todo__item">
                            <span class="todo__icon todo__icon--info"><i data-lucide="list-tree" aria-hidden="true"></i></span>
                            <div><strong>Revisi BOM menunggu persetujuan</strong><small>Bracket Assy A12 &middot; Rev. 03</small></div>
                        </li>
                    </ul>
                </article>
            </section>

        </main>
@endsection