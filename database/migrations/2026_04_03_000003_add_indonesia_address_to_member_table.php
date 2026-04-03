<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            if (!Schema::hasColumn('member', 'province_code')) {
                $table->char('province_code', 2)->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('member', 'city_code')) {
                $table->char('city_code', 4)->nullable()->after('province_code');
            }
            if (!Schema::hasColumn('member', 'district_code')) {
                $table->char('district_code', 7)->nullable()->after('city_code');
            }
            if (!Schema::hasColumn('member', 'village_code')) {
                $table->char('village_code', 10)->nullable()->after('district_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            if (Schema::hasColumn('member', 'province_code')) {
                $table->dropColumn('province_code');
            }
            if (Schema::hasColumn('member', 'city_code')) {
                $table->dropColumn('city_code');
            }
            if (Schema::hasColumn('member', 'district_code')) {
                $table->dropColumn('district_code');
            }
            if (Schema::hasColumn('member', 'village_code')) {
                $table->dropColumn('village_code');
            }
        });
    }
};
