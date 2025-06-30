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
        Schema::table('absensis', function (Blueprint $table) {
            // 1. Hapus foreign key constraint yang lama
            $table->dropForeign(['mahasiswa_id']);

            // 2. Hapus kolom mahasiswa_id
            $table->dropColumn('mahasiswa_id');

            // 3. Tambahkan kolom user_id yang baru dan constraint-nya
            $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            // 1. Hapus foreign key constraint yang baru
            $table->dropForeign(['user_id']);

            // 2. Hapus kolom user_id
            $table->dropColumn('user_id');

            // 3. Kembalikan kolom mahasiswa_id (jika perlu rollback)
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
        });
    }
};