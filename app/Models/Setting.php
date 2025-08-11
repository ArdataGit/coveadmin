<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = ['title_sistem', 'nama_perusahaan', 'alamat_perusahaan', 'nomor_wa'];
}