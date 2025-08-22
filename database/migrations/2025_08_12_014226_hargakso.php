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
        Schema::create('paket_harga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kos_id')->constrained('kos')->onDelete('cascade');
            $table->foreignId('kamar_id')->constrained('kos_detail')->onDelete('cascade');
            $table->integer('perharian_harga')->nullable();
            $table->integer('perbulan_harga')->nullable();
            $table->integer('pertigabulan_harga')->nullable();
            $table->integer('perenambulan_harga')->nullable();
            $table->integer('pertahun_harga')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_harga');
    }
};
