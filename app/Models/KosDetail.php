<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KosDetail extends Model
{
    use HasFactory;

    protected $table = 'kos_detail';

    protected $fillable = [
        'nama',
        'kos_id',
        'tipe_kos_id',
        'quantity',
        'lantai_id',
        'fasilitas_ids',
        'deskripsi',
        'jenis_kos',
        'dekat_dengan',
        'tipe_sewa'
    ];

    protected $casts = [
        'fasilitas_ids' => 'array',
    ];

    public function kos()
    {
        return $this->belongsTo(Kos::class, 'kos_id');
    }

    public function tipeKos()
    {
        return $this->belongsTo(TipeKos::class, 'tipe_kos_id');
    }

    public function lantai()
    {
        return $this->belongsTo(Lantai::class, 'lantai_id');
    }

    public function gallery()
    {
        return $this->hasMany(GalleryKos::class, 'kamar_id');
    }

        public function paketHarga()
    {
        return $this->hasOne(PaketHarga::class, 'kamar_id');
    }
}