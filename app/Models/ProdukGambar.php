<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukGambar extends Model
{
    use HasFactory;

    protected $table = 'produk_gambar';
    protected $primaryKey = 'id_gambar';

    protected $fillable = [
        'id_produk',
        'url_gambar',
    ];

    // Relasi ke Produk (Many to One)
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
