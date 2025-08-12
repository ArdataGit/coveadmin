<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\kosController;
use App\Http\Controllers\lantaiController;
use App\Http\Controllers\lokasiController;
use App\Http\Controllers\paketHargaController;
use App\Http\Controllers\settingController;
use App\Http\Controllers\tipeKosController;
use App\Http\Controllers\transaksiController;
use App\Http\Controllers\userController;
use App\Http\Controllers\fasilitasController;
use Illuminate\Support\Facades\Route;

// ====================
// Public & Auth Routes
// ====================

Route::get('/', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
// Proses login
Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
// Proses logout
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// ====================
// Admin Routes (Protected)
// ====================
Route::prefix('dashboard')->middleware('auth:admin')->group(function () {

    // Fasilitas CRUD Routes
    Route::get('/master-fasilitas', [fasilitasController::class, 'index'])->name('fasilitas.index');
    Route::get('/master-fasilitas/data', [fasilitasController::class, 'data'])->name('fasilitas.data');
    Route::post('/master-fasilitas', [fasilitasController::class, 'store'])->name('fasilitas.store');
    Route::put('/master-fasilitas/{id}', [fasilitasController::class, 'update'])->name('fasilitas.update');
    Route::delete('/master-fasilitas/{id}', [fasilitasController::class, 'destroy'])->name('fasilitas.destroy');

    // Lantai Routes
    Route::get('/master-lantai', [lantaiController::class, 'index'])->name('lantai.index');
    Route::get('/master-lantai/data', [lantaiController::class, 'data'])->name('lantai.data');
    Route::post('/master-lantai', [lantaiController::class, 'store'])->name('lantai.store');
    Route::put('/master-lantai/{id}', [lantaiController::class, 'update'])->name('lantai.update');
    Route::delete('/master-lantai/{id}', [lantaiController::class, 'destroy'])->name('lantai.destroy');

    // Lokasi Routes
    Route::get('/master-lokasi', [lokasiController::class, 'index'])->name('lokasi.index');
    Route::get('/master-lokasi/data', [lokasiController::class, 'data'])->name('lokasi.data');
    Route::post('/master-lokasi', [lokasiController::class, 'store'])->name('lokasi.store');
    Route::put('/master-lokasi/{id}', [lokasiController::class, 'update'])->name('lokasi.update');
    Route::delete('/master-lokasi/{id}', [lokasiController::class, 'destroy'])->name('lokasi.destroy');

    // Tipe Kos Routes
    Route::get('/master-tipe-kos', [tipeKosController::class, 'index'])->name('tipe-kos.index');
    Route::get('/master-tipe-kos/data', [tipeKosController::class, 'data'])->name('tipe-kos.data');
    Route::post('/master-tipe-kos', [tipeKosController::class, 'store'])->name('tipe-kos.store');
    Route::put('/master-tipe-kos/{id}', [tipeKosController::class, 'update'])->name('tipe-kos.update');
    Route::delete('/master-tipe-kos/{id}', [tipeKosController::class, 'destroy'])->name('tipe-kos.destroy');

    // User Routes
    Route::get('/master-user', [userController::class, 'index'])->name('user.index');
    Route::get('/master-user/data', [userController::class, 'data'])->name('user.data');
    Route::post('/master-user', [userController::class, 'store'])->name('user.store');
    Route::put('/master-user/{id}', [userController::class, 'update'])->name('user.update');
    Route::delete('/master-user/{id}', [userController::class, 'destroy'])->name('user.destroy');

    //master kos Routes
    Route::get('/master-kos', [kosController::class, 'index'])->name('kos.index');
    Route::get('/master-kos/data', [kosController::class, 'data'])->name('kos.data');
    Route::post('/master-kos', [kosController::class, 'store'])->name('kos.store');
    Route::put('/master-kos/{id}', [kosController::class, 'update'])->name('kos.update');
    Route::delete('/master-kos/{id}', [kosController::class, 'destroy'])->name('kos.destroy');
    
    // Kos Detail routes
    Route::get('/kos-detail/{kos_id}', [KosController::class, 'detail'])->name('kos.detail');
    Route::get('/kos-detail/{kos_id}/data', [KosController::class, 'detailData'])->name('kos.detail.data');
    Route::post('/kos-detail/{kos_id}', [KosController::class, 'detailStore'])->name('kos.detail.store');
    Route::put('/kos-detail/{id}', [KosController::class, 'detailUpdate'])->name('kos.detail.update');
    Route::delete('/kos-detail/{id}', [KosController::class, 'detailDestroy'])->name('kos.detail.destroy');

    // Placeholder for Gallery route
    Route::get('/dashboard/kos/{kos_id}/gallery/{kamar_id}', [KosController::class, 'gallery'])->name('kos.gallery');
    Route::get('/dashboard/kos/{kos_id}/gallery/{kamar_id}/data', [KosController::class, 'galleryData'])->name('kos.gallery.data');
    Route::post('/dashboard/kos/{kos_id}/gallery/{kamar_id}', [KosController::class, 'galleryStore'])->name('kos.gallery.store');
    Route::delete('/dashboard/kos/{kos_id}/gallery/{kamar_id}/{id}', [KosController::class, 'galleryDestroy'])->name('kos.gallery.destroy');

    // Paket Harga Routes
    Route::get('/master-paket-harga', [paketHargaController::class, 'index'])->name('paket-harga.index');
    Route::get('/master-paket-harga/data', [paketHargaController::class, 'data'])->name('paket-harga.data');
    Route::post('/master-paket-harga', [paketHargaController::class, 'store'])->name('paket-harga.store');
    Route::put('/master-paket-harga/{paketHarga}', [paketHargaController::class, 'update'])->name('paket-harga.update');
    Route::delete('/master-paket-harga/{paketHarga}', [paketHargaController::class, 'destroy'])->name('paket-harga.destroy');
    
    // Settings Routes
    Route::get('/settings', [settingController::class, 'index'])->name('settings.index');
    Route::post('/settings/title-sistem', [settingController::class, 'updateTitleSistem'])->name('settings.update.title_sistem');
    Route::post('/settings/nama-perusahaan', [settingController::class, 'updateNamaPerusahaan'])->name('settings.update.nama_perusahaan');
    Route::post('/settings/alamat-perusahaan', [settingController::class, 'updateAlamatPerusahaan'])->name('settings.update.alamat_perusahaan');
    Route::post('/settings/nomor-wa', [settingController::class, 'updateNomorWa'])->name('settings.update.nomor_wa');
    Route::post('/settings/banner', [settingController::class, 'storeBanner'])->name('settings.store.banner');
    Route::post('/settings/banner/update', [settingController::class, 'updateBanner'])->name('settings.update.banner');
    Route::delete('/settings/banner/{id}', [settingController::class, 'deleteBanner'])->name('settings.delete.banner');
    Route::post('/settings/admin', [settingController::class, 'storeAdmin'])->name('settings.store.admin');
    Route::post('/settings/admin/update', [settingController::class, 'updateAdmin'])->name('settings.update.admin');
    Route::delete('/settings/admin/{id}', [settingController::class, 'deleteAdmin'])->name('settings.delete.admin');

    // Transaksi Routes
    Route::get('/transaksi', [transaksiController::class, 'index'])->name('transaksi.index');           // ambil semua transaksi
    Route::get('/transaksi/{id}', [transaksiController::class, 'show']);        // ambil detail transaksi
    Route::put('/transaksi/{id}/status', [transaksiController::class, 'updateStatus']); // update status pembayaran
    Route::post('/transaksi/{id}/pembayaran', [TransaksiController::class, 'pembayaran']);
    Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

});
