<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'judul_produk',
        'deskripsi',
        'harga',
        'id_kategori',
    ];

    // Relasi ke Kategori (Many to One)
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    // Relasi ke Gambar Produk (One to Many)
    public function gambar()
    {
        return $this->hasMany(ProdukGambar::class, 'id_produk');
    }
}
