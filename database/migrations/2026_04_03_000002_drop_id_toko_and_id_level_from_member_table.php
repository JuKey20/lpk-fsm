<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $dropColumns = [];
        foreach (['id_toko', 'id_level', 'level_info'] as $col) {
            if (Schema::hasColumn('member', $col)) {
                $dropColumns[] = $col;
            }
        }

        if (empty($dropColumns)) {
            return;
        }

        foreach (['id_toko', 'id_level'] as $col) {
            if (!Schema::hasColumn('member', $col)) {
                continue;
            }
            try {
                Schema::table('member', function (Blueprint $table) use ($col) {
                    $table->dropForeign([$col]);
                });
            } catch (\Throwable $e) {
                try {
                    DB::statement("ALTER TABLE `member` DROP FOREIGN KEY `member_{$col}_foreign`");
                } catch (\Throwable $e2) {
                }
            }
        }

        Schema::table('member', function (Blueprint $table) use ($dropColumns) {
            $table->dropColumn($dropColumns);
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('member', 'id_toko')) {
            Schema::table('member', function (Blueprint $table) {
                $table->string('id_toko')->nullable()->after('id');
            });
        }
        if (!Schema::hasColumn('member', 'level_info')) {
            Schema::table('member', function (Blueprint $table) {
                $table->string('level_info')->nullable()->after('id_toko');
            });
        }
    }
};
