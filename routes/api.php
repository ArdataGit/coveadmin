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
Route::post('/transaksi', [TransaksiController::class, 'store']);
Route::get('/transaksi/user/{userId}', [TransaksiController::class, 'getByUser'])->name('transaksi.getByUser');
Route::get('/transaksi/{id}/invoice', [TransaksiController::class, 'invoice'])->name('transaksi.invoice');

// ==================== MASTER DATA ====================
Route::get('/getFasilitas', [FasilitasController::class, 'getAll']);
Route::get('/getLantai', [LantaiController::class, 'getAll']);
Route::get('/getLokasi', [LokasiController::class, 'getAll']);
Route::get('/getTipekos', [TipeKosController::class, 'getAll']);
Route::get('/getKos', [KosController::class, 'getAllData']);

// ==================== TICKET ====================
Route::get('/tickets/user', [TicketController::class, 'getTicketsByUser'])->name('tickets.user');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
