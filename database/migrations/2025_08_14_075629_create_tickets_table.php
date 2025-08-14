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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id(); // Kolom ID unik untuk tiket
            $table->unsignedBigInteger('user_id'); // ID pengguna yang membuat pengaduan
            $table->string('title'); // Judul pengaduan
            $table->text('description'); // Deskripsi pengaduan
            $table->string('category')->nullable(); // Kategori pengaduan (opsional)
            $table->string('image')->nullable(); // Kolom untuk menyimpan path gambar
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open'); // Status tiket
            $table->text('admin_response')->nullable(); // Tanggapan admin (opsional)
            $table->timestamps(); // Kolom created_at dan updated_at

            // Foreign key untuk menghubungkan dengan tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};