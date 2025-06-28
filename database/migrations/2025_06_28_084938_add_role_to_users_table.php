<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_role_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // database/migrations/xxxx_xx_xx_xxxxxx_add_role_to_users_table.php

public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Tambahkan baris ini
        $table->string('role')->default('mahasiswa')->after('email');
    });
}

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ini untuk membatalkan perubahan jika kita melakukan rollback migrasi
            $table->dropColumn('role');
        });
    }
};