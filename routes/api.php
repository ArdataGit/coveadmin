<?php

use App\Http\Controllers\transaksiProdukController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\fasilitasController;
use App\Http\Controllers\kosController;
use App\Http\Controllers\lantaiController;
use App\Http\Controllers\lokasiController;
use App\Http\Controllers\produkController;
use App\Http\Controllers\ticketController;
use App\Http\Controllers\tipeKosController;
use App\Http\Controllers\transaksiController;
use App\Http\Controllers\userController;

// ==================== AUTH ====================
Route::post('/login', [userController::class, 'login']);
// register
Route::post('/register', [userController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [userController::class, 'logout']);
Route::middleware('auth:sanctum')->post('/account/update', [userController::class, 'updateAccount']);
Route::middleware('auth:sanctum')->get('/account', [userController::class, 'profile']);

// Google OAuth routes
Route::get('/auth/google', [UserController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [UserController::class, 'handleGoogleCallback']);

// API Google login (for mobile/SPA)
Route::post('/auth/google/login', [UserController::class, 'loginWithGoogle']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ==================== TRANSAKSI PRODUK ====================
Route::get('/transaksi-produk/user/{userId}', [transaksiProdukController::class, 'getByUserProduk'])->name('transaksi-produk.getByUser');
Route::post('/transaksi-produk', [transaksiProdukController::class, 'store'])->name('transaksi-produk.store');
Route::post('/transaksi-produkweb', [transaksiProdukController::class, 'storeweb'])->name('transaksi-produkweb.store');


// ==================== TRANSAKSI ====================
Route::post('/transaksi', [transaksiController::class, 'store']);
Route::post('/transaksiweb', [transaksiController::class, 'storeweb']);
Route::get('/transaksi/user/{userId}', [transaksiController::class, 'getByUser'])->name('transaksi.getByUser');
Route::get('/transaksi/{id}/invoice', [transaksiController::class, 'invoice'])->name('transaksi.invoice');

// ==================== MASTER DATA ====================
Route::get('/getFasilitas', [fasilitasController::class, 'getAll']);
Route::get('/getLantai', [lantaiController::class, 'getAll']);
Route::get('/getLokasi', [lokasiController::class, 'getAll']);
Route::get('/getTipekos', [tipeKosController::class, 'getAll']);
Route::get('/getKos', [kosController::class, 'getAllData']);
Route::get('/getProduk', [produkController::class, 'getAllData']);
Route::get('/Produk/{id}', [produkController::class, 'getById']);

Route::get('/getKos/{id}', [kosController::class, 'getKosById']);
Route::get('/getKamar/{kos_id}/kamar/{kamar_id}', [kosController::class, 'getKamarDetail']);

// ==================== TICKET ====================
Route::get('/tickets/user', [ticketController::class, 'getTicketsByUserJson'])->name('tickets.user');
Route::post('/tickets', [ticketController::class, 'store'])->name('tickets.store');
