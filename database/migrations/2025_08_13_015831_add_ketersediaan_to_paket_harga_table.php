<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKetersediaanToPaketHargaTable extends Migration
{
    public function up()
    {
        Schema::table('paket_harga', function (Blueprint $table) {
            $table->json('ketersediaan')->nullable()->after('pertahun_harga')->comment('Data ketersediaan dalam format JSON');
        });
    }

    public function down()
    {
        Schema::table('paket_harga', function (Blueprint $table) {
            $table->dropColumn('ketersediaan');
        });
    }
}
