<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketHarga extends Model
{
    use HasFactory;

    protected $table = 'paket_harga';

    protected $fillable = [
        'kos_id',
        'kamar_id',
        'perharian_harga',
        'perbulan_harga',
        'pertigabulan_harga',
        'perenambulan_harga',
        'pertahun_harga',
    ];

    public function kos()
    {
        return $this->belongsTo(Kos::class);
    }

    public function kamar()
    {
        return $this->belongsTo(KosDetail::class);
    }
}