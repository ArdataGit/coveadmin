<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create pembayaran table
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->bigIncrements('pembayaran_id');
            $table->unsignedBigInteger('transaksi_id');
            $table->date('tanggal');
            $table->enum('jenis_bayar', ['biaya_kos', 'tagihan', 'denda'])->nullable();
            $table->enum('tipe_bayar', ['dp', 'full'])->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->integer('nominal')->default(0);
            $table->timestamps();

            $table->foreign('transaksi_id')
                  ->references('id')
                  ->on('transaksi')
                  ->onDelete('cascade');

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
        });

        // Migrate data from transaksi to pembayaran
        DB::statement("
            INSERT INTO pembayaran (
                transaksi_id, 
                tanggal, 
                jenis_bayar, 
                tipe_bayar, 
                keterangan, 
                nominal, 
                created_at, 
                updated_at
            )
            SELECT 
                id, 
                tanggal, 
                jenis_bayar, 
                tipe_bayar, 
                keterangan, 
                nominal, 
                created_at, 
                updated_at
            FROM transaksi
            WHERE jenis_bayar IS NOT NULL 
               OR tipe_bayar IS NOT NULL 
               OR keterangan IS NOT NULL 
               OR nominal != 0
        ");

        // Modify transaksi table to remove columns
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['jenis_bayar', 'tipe_bayar', 'keterangan', 'nominal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add columns back to transaksi table
        Schema::table('transaksi', function (Blueprint $table) {
            $table->enum('jenis_bayar', ['biaya_kos', 'tagihan', 'denda'])->nullable()->after('methode_pembayaran');
            $table->enum('tipe_bayar', ['dp', 'full'])->nullable()->after('jenis_bayar');
            $table->string('keterangan', 255)->nullable()->after('tipe_bayar');
            $table->integer('nominal')->default(0)->after('keterangan');
        });

        // Migrate data back from pembayaran to transaksi
        DB::statement("
            UPDATE transaksi t
            JOIN (
                SELECT 
                    transaksi_id,
                    jenis_bayar,
                    tipe_bayar,
                    keterangan,
                    nominal
                FROM pembayaran
                WHERE transaksi_id IN (SELECT id FROM transaksi)
            ) p ON t.id = p.transaksi_id
            SET 
                t.jenis_bayar = p.jenis_bayar,
                t.tipe_bayar = p.tipe_bayar,
                t.keterangan = p.keterangan,
                t.nominal = p.nominal
        ");

        // Drop pembayaran table
        Schema::dropIfExists('pembayaran');
    }
};
