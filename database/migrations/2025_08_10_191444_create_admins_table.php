<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Admin's full name
            $table->string('email')->unique(); // Admin's email (unique)
            $table->string('password'); // Hashed password
            $table->boolean('is_active')->default(true); // Admin status (active/inactive)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admins');
    }
}