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
            // Ubah kolom 'role' menjadi string dengan panjang 50
            // dan tetap memiliki nilai default 'mahasiswa'
            $table->string('role', 50)->default('mahasiswa')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ini adalah kebalikan dari perintah 'up' jika Anda perlu rollback
            // Untuk amannya kita biarkan saja seperti ini
            $table->string('role')->default('mahasiswa')->change();
        });
    }
};