<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('users', 'id_toko')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropForeign(['id_toko']);
                });
            } catch (\Throwable $e) {
                try {
                    DB::statement('ALTER TABLE `users` DROP FOREIGN KEY `users_id_toko_foreign`');
                } catch (\Throwable $e2) {
                }
            }
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('id_toko');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('users', 'id_toko')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('id_toko')->nullable()->after('id');
            });
        }
    }
};
