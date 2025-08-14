<?php

use App\Http\Controllers\fasilitasController;
use App\Http\Controllers\KosController;
use App\Http\Controllers\lantaiController;
use App\Http\Controllers\lokasiController;
use App\Http\Controllers\ticketController;
use App\Http\Controllers\tipeKosController;
use App\Http\Controllers\transaksiController;
use App\Models\KosDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {   
    return $request->user();
})->middleware('auth:sanctum');

// Route Transaksi
Route::post('/transaksi', [transaksiController::class, 'store']);
Route::get('/transaksi/user/{userId}', [transaksiController::class, 'getByUser'])->name('transaksi.getByUser');

//master route
Route::get('/getFasilitas', [fasilitasController::class, 'getAll']);
Route::get('/getLantai', [lantaiController::class, 'getAll']);
Route::get('/getLokasi', [lokasiController::class, 'getAll']);
Route::get('/getTipekos', [tipeKosController::class, 'getAll']);
Route::get('/getKos', [KosController::class, 'getAllData']);

//invoice
Route::get('/transaksi/{id}/invoice', [TransaksiController::class, 'invoice'])->name('transaksi.invoice');


//ticket route
Route::get('/tickets/user', [TicketController::class, 'getTicketsByUser'])->name('tickets.user');
Route::post('/tickets', [ticketController::class, 'store'])->name('tickets.store');
