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
        Schema::table('users', function (Blueprint $table) {
            // Rename 'name' to 'nama'
            $table->renameColumn('name', 'nama');

            // Add 'nik' as integer with unique constraint
            $table->integer('nik')->unique()->after('nama');

            // Change 'password' from string to text
            $table->text('password')->change();

            // Add 'alamat' as text
            $table->text('alamat')->after('password');

            // Add 'status' as enum
            $table->enum('status', ['active', 'inactive'])->after('alamat');

            // Drop 'email_verified_at' and 'remember_token'
            $table->dropColumn(['email_verified_at', 'remember_token']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverse renaming: change 'nama' back to 'name'
            $table->renameColumn('nama', 'name');

            // Drop added columns
            $table->dropColumn(['nik', 'alamat', 'status']);

            // Revert 'password' to string
            $table->string('password')->change();

            // Restore 'email_verified_at' and 'remember_token'
            $table->timestamp('email_verified_at')->nullable()->after('email');
            $table->rememberToken()->after('password');
        });
    }
};
