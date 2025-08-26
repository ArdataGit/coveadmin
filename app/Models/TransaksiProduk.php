<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiProduk extends Model
{
    use HasFactory;

    protected $table = 'transaksi_produk';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'no_order',
        'id_user',
        'id_produk',
        'jumlah',
        'harga_satuan',
        'subtotal',
        'tanggal_transaksi',
        'status',
        'created_at',
        'updated_at',
    ];

        // Casting tanggal_transaksi biar otomatis jadi Carbon
    protected $casts = [
        'tanggal_transaksi' => 'datetime',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // Relasi ke Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    
}
