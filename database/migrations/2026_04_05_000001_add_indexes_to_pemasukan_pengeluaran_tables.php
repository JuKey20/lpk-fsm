<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('pemasukan', 'id_toko')) {
            Schema::table('pemasukan', function (Blueprint $table) {
                $table->index(['id_toko', 'tanggal'], 'pemasukan_id_toko_tanggal_index');
            });
        } else {
            Schema::table('pemasukan', function (Blueprint $table) {
                $table->index(['tanggal'], 'pemasukan_tanggal_index');
            });
        }

        if (Schema::hasColumn('pengeluaran', 'id_toko')) {
            Schema::table('pengeluaran', function (Blueprint $table) {
                $table->index(['id_toko', 'tanggal'], 'pengeluaran_id_toko_tanggal_index');
            });
        } else {
            Schema::table('pengeluaran', function (Blueprint $table) {
                $table->index(['tanggal'], 'pengeluaran_tanggal_index');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pemasukan', 'id_toko')) {
            try {
                Schema::table('pemasukan', function (Blueprint $table) {
                    $table->dropIndex('pemasukan_id_toko_tanggal_index');
                });
            } catch (\Throwable $e) {
            }
        } else {
            try {
                Schema::table('pemasukan', function (Blueprint $table) {
                    $table->dropIndex('pemasukan_tanggal_index');
                });
            } catch (\Throwable $e) {
            }
        }

        if (Schema::hasColumn('pengeluaran', 'id_toko')) {
            try {
                Schema::table('pengeluaran', function (Blueprint $table) {
                    $table->dropIndex('pengeluaran_id_toko_tanggal_index');
                });
            } catch (\Throwable $e) {
            }
        } else {
            try {
                Schema::table('pengeluaran', function (Blueprint $table) {
                    $table->dropIndex('pengeluaran_tanggal_index');
                });
            } catch (\Throwable $e) {
            }
        }
    }
};
