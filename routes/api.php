<?php

use App\Http\Controllers\transaksiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {   
    return $request->user();
})->middleware('auth:sanctum');

// Route Transaksi
Route::prefix('transaksi')->group(function () {
    Route::post('/', [transaksiController::class, 'store']);          // tambah transaksi baru (pembelian)
});