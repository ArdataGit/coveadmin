<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('title_sistem', 255)->default('Sistem Manajemen');
            $table->string('nama_perusahaan', 255)->default('Nama Perusahaan');
            $table->text('alamat_perusahaan')->nullable(); // Removed default, made nullable
            $table->string('nomor_wa', 20)->default('6281234567890');
            $table->timestamps();
        });

        // Insert default settings
        \App\Models\Setting::create([
            'title_sistem' => 'Sistem Manajemen',
            'nama_perusahaan' => 'Nama Perusahaan',
            'alamat_perusahaan' => 'Alamat Perusahaan',
            'nomor_wa' => '6281234567890',
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};