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
    Schema::create('absensis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('mahasiswa_id')->constrained()->onDelete('cascade');
        $table->foreignId('mata_kuliah_id')->constrained()->onDelete('cascade');
        $table->date('tanggal_absensi');
        $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alpa']);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
