<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'gallery';

    protected $fillable = [
        'kos_id',
        'nama_file',
        'url',
    ];

    public function kos()
    {
        return $this->belongsTo(Kos::class, 'kos_id');
    }
}