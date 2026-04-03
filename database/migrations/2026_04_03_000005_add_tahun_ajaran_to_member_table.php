<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            if (!Schema::hasColumn('member', 'tahun_ajaran')) {
                $table->unsignedSmallInteger('tahun_ajaran')->nullable()->after('nama_sekolah');
            }
        });
    }

    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            if (Schema::hasColumn('member', 'tahun_ajaran')) {
                $table->dropColumn('tahun_ajaran');
            }
        });
    }
};
