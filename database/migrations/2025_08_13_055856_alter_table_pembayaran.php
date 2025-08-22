<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            // Tambah kolom kode_pembayaran setelah primary key
            $table->string('kode_pembayaran', 50)->nullable()->after('pembayaran_id')->unique();
        });

        // Isi kode_pembayaran untuk data yang sudah ada
        DB::table('pembayaran')->orderBy('pembayaran_id')->chunk(100, function ($pembayarans) {
            foreach ($pembayarans as $pembayaran) {
                DB::table('pembayaran')
                    ->where('pembayaran_id', $pembayaran->pembayaran_id)
                    ->update([
                        'kode_pembayaran' => 'PEM/' . str_pad($pembayaran->pembayaran_id, 6, '0', STR_PAD_LEFT)
                    ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn('kode_pembayaran');
        });
    }
};
