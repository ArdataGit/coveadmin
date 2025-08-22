<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('no_order');
            $table->date('tanggal');
            $table->date('start_order_date')->nullable();
            $table->date('end_order_date')->nullable();
            $table->unsignedBigInteger('kos_id')->nullable();
            $table->unsignedBigInteger('kamar_id')->nullable();
            $table->unsignedBigInteger('paket_id')->nullable();
            $table->integer('harga')->default(0);
            $table->integer('nominal')->default(0);
            $table->string('keterangan')->nullable();

            // ENUM sesuai permintaan
            $table->enum('tipe_bayar', ['dp', 'full'])->nullable();
            $table->enum('jenis_bayar', ['biaya_kos', 'tagihan', 'denda'])->nullable();
            $table->string('methode_pembayaran')->nullable();
            $table->enum('status', ['paid', 'unpaid', 'cancel'])->default('unpaid');

            $table->timestamps();

            // Relasi opsional
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
