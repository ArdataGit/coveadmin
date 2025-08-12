<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryKos extends Model
{
    use HasFactory;

    protected $table = 'gallery_kos';

    protected $fillable = [
        'kamar_id',
        'nama_file',
        'url',
    ];

    public function kamar()
    {
        return $this->belongsTo(KosDetail::class, 'kamar_id');
    }
}