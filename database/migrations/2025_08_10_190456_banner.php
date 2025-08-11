<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class banner extends Migration
{
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable(); // Path to the banner image
            $table->string('title')->nullable(); // Banner title
            $table->text('description')->nullable(); // Banner description
            $table->boolean('is_active')->default(true); // Banner status (active/inactive)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('banners');
    }
}