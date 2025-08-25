<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\KosController;
use App\Http\Controllers\LantaiController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TipeKosController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;

// ==================== AUTH ====================
Route::post('/login', [UserController::class, 'login']);
// register
Route::post('/register', [UserController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);
Route::middleware('auth:sanctum')->post('/account/update', [UserController::class, 'updateAccount']);
Route::middleware('auth:sanctum')->get('/account', [UserController::class, 'profile']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ==================== TRANSAKSI ====================
Route::post('/transaksi', [transaksiController::class, 'store']);
Route::get('/transaksi/user/{userId}', [transaksiController::class, 'getByUser'])->name('transaksi.getByUser');
Route::get('/transaksi/{id}/invoice', [transaksiController::class, 'invoice'])->name('transaksi.invoice');

// ==================== MASTER DATA ====================
Route::get('/getFasilitas', [fasilitasController::class, 'getAll']);
Route::get('/getLantai', [lantaiController::class, 'getAll']);
Route::get('/getLokasi', [lokasiController::class, 'getAll']);
Route::get('/getTipekos', [tipeKosController::class, 'getAll']);
Route::get('/getKos', [kosController::class, 'getAllData']);

// ==================== TICKET ====================
Route::get('/tickets/user', [ticketController::class, 'getTicketsByUser'])->name('tickets.user');
Route::post('/tickets', [ticketController::class, 'store'])->name('tickets.store');
