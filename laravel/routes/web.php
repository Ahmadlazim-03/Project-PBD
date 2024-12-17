<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\KartuStokController;
use App\Http\Controllers\MarginPenjualanController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\D_PenerimaanController;
use App\Http\Controllers\D_PengadaanController;
use App\Http\Controllers\D_ReturController;
use App\Http\Controllers\D_PenjualanController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


Route::get('/', [DashboardController::class,'index']);

Route::prefix('master')->group(function () {
    Route::resource('barang', BarangController::class);
    Route::resource('vendor', VendorController::class);
    Route::resource('user', UserController::class);
    Route::resource('satuan', SatuanController::class);
    Route::resource('role', RoleController::class);
});
Route::prefix('transaksi')->group(function () {
    Route::resource('penerimaan', PenerimaanController::class);
    Route::resource('pengadaan', PengadaanController::class);
    Route::resource('penjualan', PenjualanController::class);
    Route::resource('retur', ReturController::class);

    Route::resource('detail-penerimaan', D_PenerimaanController::class);
    Route::resource('detail-pengadaan', D_PengadaanController::class);
    Route::resource('detail-penjualan', D_PenjualanController::class);
    Route::resource('detail-retur', D_ReturController::class);
});
Route::prefix('manajemenstok')->group(function () {
    Route::resource('kartustok', KartuStokController::class);
});
Route::prefix('laporan')->group(function () {
    Route::resource('margin', MarginPenjualanController::class);
});

Route::get('/get-barang-by-pengadaan/{idpengadaan}', [BarangController::class, 'getBarangByPengadaan']);
Route::get('/get-max-jumlah/{idbarang}', [BarangController::class, 'getMaxJumlahByIdBarang']);
Route::get('/get-barang-penerimaan', [BarangController::class, 'getBarangPenerimaan']);
Route::get('/get-max-jumlah/{idbarang}', [BarangController::class, 'getMaxJumlahByIdBarang']);

Route::get('/get-barang-by-penerimaan/{idpenerimaan}', [BarangController::class, 'getBarangByPenerimaan']);
Route::get('/get-barang-count', [BarangController::class, 'getBarangCount']);


