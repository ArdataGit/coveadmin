<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kos extends Model
{
    use HasFactory;

    protected $table = 'kos';

    protected $fillable = [
        'nama',
        'alamat_kota',
        'daerah_id',
        'keterangan',
        'link_maps',
    ];

    // If there's a relationship with Daerah model
    public function daerah()
    {
        return $this->belongsTo(Lokasi::class, 'daerah_id');
    }

    
    public function gallery()
    {
        return $this->hasMany(Gallery::class, 'kos_id');
    }
}