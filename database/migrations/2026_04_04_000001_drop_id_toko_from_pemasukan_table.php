<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pemasukan', function (Blueprint $table) {
            if (Schema::hasColumn('pemasukan', 'id_toko')) {
                $table->dropColumn('id_toko');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pemasukan', function (Blueprint $table) {
            if (!Schema::hasColumn('pemasukan', 'id_toko')) {
                $table->string('id_toko')->nullable()->after('id');
            }
        });
    }
};
