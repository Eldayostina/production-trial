<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

/* ============ PRODUCTION ============ */
Route::get('/production-plan', function () {
    return view('production-plan');
})->name('production-plan');

Route::get('/production-plan/create', function () {
    return view('production-plan-create');
})->name('production-plan.create');

Route::get('/production-plan/{no}', function ($no) {
    return view('production-plan-show', ['no' => $no]);
})->name('production-plan.show');

Route::get('/production-trial', function () {
    return view('production-trial');
})->name('production-trial');

Route::get('/production-trial/{no}', function ($no) {
    return view('production-trial-show', ['no' => $no]);
})->name('production-trial.show');

Route::get('/trial-result', function () {
    return view('trial-result');
})->name('trial-result');

Route::get('/trial-result/{no}', function ($no) {
    return view('trial-result-show', ['no' => $no]);
})->name('trial-result.show');

/* ============ INVENTORY ============ */
Route::get('/stock-transfer', function () {
    return view('stock-transfer');
})->name('stock-transfer');

Route::get('/stock-transfer/create', function () {
    return view('stock-transfer-create');
})->name('stock-transfer.create');

Route::get('/stock-transfer/{no}', function ($no) {
    return view('stock-transfer-show', ['no' => $no]);
})->name('stock-transfer.show');

Route::get('/stock-overview', function () {
    return view('stock-overview');
})->name('stock-overview');

Route::get('/stock-overview/{code}', function ($code) {
    return view('stock-overview-show', ['code' => $code]);
})->name('stock-overview.show');

/* ============ QUALITY ============ */
Route::get('/approval', function () {
    return view('approval');
})->name('approval');

Route::get('/approval/{no}', function ($no) {
    return view('approval-show', ['no' => $no]);
})->name('approval.show');

Route::get('/quality-inspection', function () {
    return view('quality-inspection');
})->name('quality-inspection');

Route::get('/quality-inspection/{no}', function ($no) {
    return view('quality-inspection-show', ['no' => $no]);
})->name('quality-inspection.show');

Route::get('/quality-inspection/create', function () {
    return view('quality-inspection-create');
})->name('quality-inspection.create');

/* ============ REPORTS ============ */
Route::get('/production-report', function () {
    return view('production-report');
})->name('production-report');

Route::get('/production-report/plan/{no}', function ($no) {
    return view('production-report-plan', ['no' => $no]);
})->name('production-report.plan');

Route::get('/trial-report', function () {
    return view('trial-report');
})->name('trial-report');

Route::get('/inventory-report', function () {
    return view('inventory-report');
})->name('inventory-report');

/* ============ MASTER DATA - BARANG ============ */
Route::get('/master-barang', function () {
    return view('master-barang');
})->name('master-barang');

Route::get('/master-barang/create', function () {
    return view('master-barang-create');
})->name('master-barang.create');

Route::get('/master-barang/{code}', function ($code) {
    return view('master-barang-show', ['code' => $code]);
})->name('master-barang.show');

/* ============ MASTER DATA - BOM ============ */
Route::get('/bom', function () {
    return view('bom');
})->name('bom');

Route::get('/bom/create', function () {
    return view('bom-create');
})->name('bom.create');

Route::get('/bom/{no}', function ($no) {
    return view('bom-show', ['no' => $no]);
})->name('bom.show');

/* ============ MASTER DATA - GUDANG ============ */
Route::get('/master-gudang', function () {
    return view('gudang');
})->name('gudang');

Route::get('/master-gudang/create', function () {
    return view('gudang-form', ['kode' => null]);
})->name('gudang.create');

Route::get('/master-gudang/{kode}/edit', function ($kode) {
    return view('gudang-form', ['kode' => $kode]);
})->name('gudang.edit');

Route::get('/master/work-center', function () {
    return view('master-work-center');
})->name('master.work-center');

Route::get('/master/work-center/create', function () {
    return view('master-work-center-create');
})->name('master.work-center.create');

Route::get('/master/work-center/{no}', function ($no) {
    return view('master-work-center-show', ['code' => $no]);
})->name('master.work-center.show');

Route::get('/master/uom', function () {
    return view('master-uom');
})->name('master.uom');

Route::get('/master/uom/create', function () {
    return view('master-uom-create');
})->name('master.uom.create');

Route::get('/master/uom/{code}', function ($code) {
    return view('master-uom-show', ['code' => $code]);
})->name('master.uom.show');

// Route Master Supplier
Route::get('/master/supplier', function () {
    return view('master-supplier');
})->name('master.supplier');

Route::get('/master/supplier/create', function () {
    return view('master-supplier-create');
})->name('master.supplier.create');

Route::get('/master/supplier/{code}', function ($code) {
    return view('master-supplier-show', ['code' => $code]);
})->name('master.supplier.show');

/* ============ AUDIT LOG ============ */
Route::get('/audit-log', function () {
    return view('audit-log');
})->name('audit-log');

/* ============ USERS ============ */
Route::get('/users', function () {
    return view('users');
})->name('users');

Route::get('/users/create', function () {
    return view('user-form', ['id' => null]);
})->name('users.create');

Route::get('/users/{id}/edit', function ($id) {
    return view('user-form', ['id' => $id]);
})->name('users.edit');

Route::get('/roles', function () {
    return view('roles');
})->name('roles');