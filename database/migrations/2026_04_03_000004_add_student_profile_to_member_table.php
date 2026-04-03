<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            if (!Schema::hasColumn('member', 'nik')) {
                $table->string('nik', 20)->nullable()->after('nama_member');
                $table->unique('nik');
            }
            if (!Schema::hasColumn('member', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable()->after('nik');
            }
            if (!Schema::hasColumn('member', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            }
            if (!Schema::hasColumn('member', 'jenis_kelamin')) {
                $table->string('jenis_kelamin', 20)->nullable()->after('tanggal_lahir');
            }
            if (!Schema::hasColumn('member', 'agama')) {
                $table->string('agama', 50)->nullable()->after('jenis_kelamin');
            }
            if (!Schema::hasColumn('member', 'status_pernikahan')) {
                $table->string('status_pernikahan', 50)->nullable()->after('agama');
            }
            if (!Schema::hasColumn('member', 'kewarganegaraan')) {
                $table->string('kewarganegaraan', 10)->nullable()->after('status_pernikahan');
            }

            if (!Schema::hasColumn('member', 'alamat_ktp')) {
                $table->text('alamat_ktp')->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('member', 'alamat_domisili')) {
                $table->text('alamat_domisili')->nullable()->after('alamat_ktp');
            }

            if (!Schema::hasColumn('member', 'pendidikan_terakhir')) {
                $table->string('pendidikan_terakhir', 20)->nullable()->after('village_code');
            }
            if (!Schema::hasColumn('member', 'jurusan')) {
                $table->string('jurusan')->nullable()->after('pendidikan_terakhir');
            }
            if (!Schema::hasColumn('member', 'nama_sekolah')) {
                $table->string('nama_sekolah')->nullable()->after('jurusan');
            }
            if (!Schema::hasColumn('member', 'tahun_lulus')) {
                $table->string('tahun_lulus', 4)->nullable()->after('nama_sekolah');
            }
        });
    }

    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            if (Schema::hasColumn('member', 'nik')) {
                $table->dropUnique('member_nik_unique');
                $table->dropColumn('nik');
            }
            if (Schema::hasColumn('member', 'tempat_lahir')) {
                $table->dropColumn('tempat_lahir');
            }
            if (Schema::hasColumn('member', 'tanggal_lahir')) {
                $table->dropColumn('tanggal_lahir');
            }
            if (Schema::hasColumn('member', 'jenis_kelamin')) {
                $table->dropColumn('jenis_kelamin');
            }
            if (Schema::hasColumn('member', 'agama')) {
                $table->dropColumn('agama');
            }
            if (Schema::hasColumn('member', 'status_pernikahan')) {
                $table->dropColumn('status_pernikahan');
            }
            if (Schema::hasColumn('member', 'kewarganegaraan')) {
                $table->dropColumn('kewarganegaraan');
            }
            if (Schema::hasColumn('member', 'alamat_ktp')) {
                $table->dropColumn('alamat_ktp');
            }
            if (Schema::hasColumn('member', 'alamat_domisili')) {
                $table->dropColumn('alamat_domisili');
            }
            if (Schema::hasColumn('member', 'pendidikan_terakhir')) {
                $table->dropColumn('pendidikan_terakhir');
            }
            if (Schema::hasColumn('member', 'jurusan')) {
                $table->dropColumn('jurusan');
            }
            if (Schema::hasColumn('member', 'nama_sekolah')) {
                $table->dropColumn('nama_sekolah');
            }
            if (Schema::hasColumn('member', 'tahun_lulus')) {
                $table->dropColumn('tahun_lulus');
            }
        });
    }
};
