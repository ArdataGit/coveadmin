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
        Schema::create('kos_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->unsignedBigInteger('kos_id');
            $table->unsignedBigInteger('tipe_kos_id');
            $table->integer('quantity');
            $table->unsignedBigInteger('lantai_id');
            $table->json('fasilitas_ids');
            $table->text('deskripsi')->nullable();
            $table->enum('jenis_kos', ['Putra', 'Putri', 'Campur']);
            $table->text('dekat_dengan')->nullable();
            $table->timestamps();

            $table->foreign('kos_id')->references('id')->on('kos')->onDelete('cascade');
            $table->foreign('tipe_kos_id')->references('id')->on('tipe_kos')->onDelete('restrict');
            $table->foreign('lantai_id')->references('id')->on('lantai')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kos_detail');
    }
};
