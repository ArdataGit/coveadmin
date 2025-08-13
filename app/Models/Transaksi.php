<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'user_id',
        'quantity', // Tambahkan kolom quantity
        'no_order',
        'tanggal',
        'start_order_date',
        'end_order_date',
        'kos_id',
        'kamar_id',
        'paket_id',
        'harga',
        'nominal',
        'keterangan',
        'tipe_bayar',       // dp / full
        'jenis_bayar',      // biaya_kos / tagihan / denda
        'methode_pembayaran',
        'status',           // paid / unpaid / cancel
    ];

    // Relasi contoh
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function kos()
    {
        return $this->belongsTo(Kos::class);
    }
    public function kamar()
    {
        return $this->belongsTo(KosDetail::class);
    }
    public function paket()
    {
        return $this->belongsTo(PaketHarga::class);
    }
    /**
     * Relationship to Pembayaran
     */
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'transaksi_id', 'id');
    }
}
