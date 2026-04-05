<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardKeuanganRingkasanTest extends TestCase
{
    use RefreshDatabase;

    public function test_rejects_invalid_period(): void
    {
        $response = $this->getJson(route('dashboard.keuangan', [
            'period' => 'invalid',
        ]));

        $response->assertStatus(422);
        $response->assertJsonPath('error', true);
    }

    public function test_daily_aggregation_for_specific_toko(): void
    {
        $year = 2025;
        $month = 3;

        DB::table('pemasukan')->insert([
            [
                'nama_pemasukan' => 'A',
                'id_jenis_pemasukan' => null,
                'nilai' => 100_000,
                'tanggal' => Carbon::create($year, $month, 1, 10, 0, 0),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'nama_pemasukan' => 'B',
                'id_jenis_pemasukan' => null,
                'nilai' => 250_000,
                'tanggal' => Carbon::create($year, $month, 2, 12, 0, 0),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'nama_pemasukan' => 'Other month',
                'id_jenis_pemasukan' => null,
                'nilai' => 777_000,
                'tanggal' => Carbon::create($year, 4, 1, 9, 0, 0),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ]);

        DB::table('pengeluaran')->insert([
            [
                'nama_pengeluaran' => 'X',
                'id_jenis_pengeluaran' => null,
                'is_asset' => null,
                'nilai' => 50_000,
                'tanggal' => Carbon::create($year, $month, 1, 13, 0, 0),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'nama_pengeluaran' => 'Y',
                'id_jenis_pengeluaran' => null,
                'is_asset' => null,
                'nilai' => 70_000,
                'tanggal' => Carbon::create($year, $month, 2, 14, 0, 0),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ]);

        $response = $this->getJson(route('dashboard.keuangan', [
            'period' => 'daily',
            'year' => $year,
            'month' => $month,
        ]));

        $response->assertOk();
        $response->assertJsonPath('error', false);
        $response->assertJsonPath('data.period', 'daily');
        $response->assertJsonCount(31, 'data.categories');

        $response->assertJsonPath('data.income.series.0', 100_000);
        $response->assertJsonPath('data.income.series.1', 250_000);
        $response->assertJsonPath('data.expense.series.0', 50_000);
        $response->assertJsonPath('data.expense.series.1', 70_000);

        $response->assertJsonPath('data.income.total', 350_000);
        $response->assertJsonPath('data.expense.total', 120_000);
    }

    public function test_monthly_aggregation_for_specific_year(): void
    {
        $year = 2025;

        DB::table('pemasukan')->insert([
            [
                'nama_pemasukan' => 'Jan',
                'id_jenis_pemasukan' => null,
                'nilai' => 10_000,
                'tanggal' => Carbon::create($year, 1, 10, 10, 0, 0),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'nama_pemasukan' => 'Feb',
                'id_jenis_pemasukan' => null,
                'nilai' => 20_000,
                'tanggal' => Carbon::create($year, 2, 10, 10, 0, 0),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ]);

        DB::table('pengeluaran')->insert([
            [
                'nama_pengeluaran' => 'Feb',
                'id_jenis_pengeluaran' => null,
                'is_asset' => null,
                'nilai' => 5_000,
                'tanggal' => Carbon::create($year, 2, 15, 10, 0, 0),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ]);

        $response = $this->getJson(route('dashboard.keuangan', [
            'period' => 'monthly',
            'year' => $year,
        ]));

        $response->assertOk();
        $response->assertJsonPath('data.period', 'monthly');
        $response->assertJsonCount(12, 'data.categories');

        $response->assertJsonPath('data.income.series.0', 10_000);
        $response->assertJsonPath('data.income.series.1', 20_000);
        $response->assertJsonPath('data.expense.series.1', 5_000);
        $response->assertJsonPath('data.income.total', 30_000);
        $response->assertJsonPath('data.expense.total', 5_000);
    }

    public function test_yearly_aggregation_last_years_range(): void
    {
        DB::table('pemasukan')->insert([
            [
                'nama_pemasukan' => '2022',
                'id_jenis_pemasukan' => null,
                'nilai' => 1_000,
                'tanggal' => Carbon::create(2022, 1, 1, 10, 0, 0),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'nama_pemasukan' => '2024',
                'id_jenis_pemasukan' => null,
                'nilai' => 2_000,
                'tanggal' => Carbon::create(2024, 6, 1, 10, 0, 0),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ]);

        DB::table('pengeluaran')->insert([
            [
                'nama_pengeluaran' => '2024',
                'id_jenis_pengeluaran' => null,
                'is_asset' => null,
                'nilai' => 500,
                'tanggal' => Carbon::create(2024, 2, 1, 10, 0, 0),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ]);

        $response = $this->getJson(route('dashboard.keuangan', [
            'period' => 'yearly',
            'start_year' => 2022,
            'end_year' => 2024,
        ]));

        $response->assertOk();
        $response->assertJsonPath('data.period', 'yearly');
        $response->assertJsonPath('data.categories.0', '2022');
        $response->assertJsonPath('data.categories.2', '2024');

        $response->assertJsonPath('data.income.series.0', 1_000);
        $response->assertJsonPath('data.income.series.2', 2_000);
        $response->assertJsonPath('data.expense.series.2', 500);
        $response->assertJsonPath('data.income.total', 3_000);
        $response->assertJsonPath('data.expense.total', 500);
    }
}
