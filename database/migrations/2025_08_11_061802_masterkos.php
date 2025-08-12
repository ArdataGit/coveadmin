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
        Schema::create('kos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('alamat_kota');
            $table->unsignedBigInteger('daerah_id');
            $table->string('keterangan')->nullable();
            $table->text('link_maps')->nullable();
            $table->timestamps();

            // Assuming daerah_id is a foreign key to another table, e.g., master_daerah
            $table->foreign('daerah_id')->references('id')->on('lokasi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kos');
    }
};
