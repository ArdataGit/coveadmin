<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->after('id');
            $table->timestamp('email_verified_at')->nullable()->after('email');
            
            // Make NIK and alamat nullable for Google users
            $table->string('nik', 16)->nullable()->change();
            $table->text('alamat')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'email_verified_at']);
            
            // Revert NIK and alamat to required (if needed)
            $table->string('nik', 16)->nullable(false)->change();
            $table->text('alamat')->nullable(false)->change();
        });
    }
};