<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'pembayaran_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'transaksi_id',
        'tanggal',
        'jenis_bayar',
        'tipe_bayar',
        'keterangan',
        'nominal',
        'kode_pembayaran',
    ];

    protected static function boot()
{
    parent::boot();

    static::created(function ($model) {
        $model->kode_pembayaran = 'PEM/' . str_pad($model->pembayaran_id, 6, '0', STR_PAD_LEFT);
        $model->saveQuietly();
    });
}

}
