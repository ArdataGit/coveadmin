<?php

use App\Http\Controllers\fasilitasController;
use App\Http\Controllers\KosController;
use App\Http\Controllers\lantaiController;
use App\Http\Controllers\lokasiController;
use App\Http\Controllers\tipeKosController;
use App\Http\Controllers\transaksiController;
use App\Models\KosDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {   
    return $request->user();
})->middleware('auth:sanctum');

// Route Transaksi
Route::prefix('transaksi')->group(function () {
    Route::post('/', [transaksiController::class, 'store']);          // tambah transaksi baru (pembelian)
});
Route::get('/getFasilitas', [fasilitasController::class, 'getAll']);
Route::get('/getLantai', [lantaiController::class, 'getAll']);
Route::get('/getLokasi', [lokasiController::class, 'getAll']);
Route::get('/getTipekos', [tipeKosController::class, 'getAll']);
Route::get('/getKos', [KosController::class, 'getAllData']);

//invoice
Route::get('/transaksi/{id}/invoice', [TransaksiController::class, 'invoice'])->name('transaksi.invoice');
