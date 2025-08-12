<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipeKos extends Model
{
    use HasFactory;
    protected $table = 'tipe_kos';
    protected $fillable = ['nama'];

    public function kosDetails()
    {
        return $this->hasMany(KosDetail::class, 'tipe_kos_id');
    }
}