<?php

namespace App\Http\Controllers\LaporanKeuangan;

use App\Http\Controllers\Controller;
use App\Models\DetailPengeluaran;
use App\Models\Hutang;
use App\Models\Pengeluaran;
use App\Models\Pemasukan;
use App\Models\Piutang;
use App\Services\ArusKasService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NeracaController extends Controller
{
    private array $menu = [];
    protected $arusKasService;

    public function __construct(ArusKasService $arusKasService)
    {
        $this->menu;
        $this->title = [
            'Neraca',
        ];

        $this->arusKasService = $arusKasService;
    }

    public function index()
    {
        if (!in_array(Auth::user()->id_level, [1])) {
            abort(403, 'Unauthorized');
        }

        $menu = [$this->title[0], $this->label[4]];

        return view('laporankeuangan.neraca.index', compact('menu'));
    }

    public function getNeraca(Request $request)
    {
        try {
            $month = (int)($request->input('month') ?? Carbon::now()->month);
            $year = (int)($request->input('year') ?? Carbon::now()->year);

            $startOfYear = Carbon::create($year, 1, 1)->startOfDay();

            $computeForMonth = function (int $m) use ($startOfYear, $year) {
                try {
                    $endOfMonth = Carbon::create($year, $m, 1)->endOfMonth()->endOfDay();

                    $pemasukanIn = (int) Pemasukan::whereBetween('tanggal', [$startOfYear, $endOfMonth])->sum('nilai');
                    $pengeluaranQuery = Pengeluaran::whereBetween('tanggal', [$startOfYear, $endOfMonth]);
                    if (Schema::hasColumn('pengeluaran', 'is_hutang')) {
                        $pengeluaranQuery->where(function ($q) {
                            $q->whereNull('is_hutang')->orWhere('is_hutang', '!=', 1);
                        });
                    }
                    $pengeluaranOut = (int) $pengeluaranQuery->sum('nilai');
                    $pengeluaranBayarHutangOut = (int) DetailPengeluaran::whereBetween('created_at', [$startOfYear, $endOfMonth])
                        ->sum('nilai');

                    $piutangOut = (int) Piutang::whereBetween('tanggal', [$startOfYear, $endOfMonth])->sum('nilai');
                    $piutangIn = (int) DB::table('detail_piutang')
                        ->join('piutang', 'detail_piutang.id_piutang', '=', 'piutang.id')
                        ->whereBetween('detail_piutang.created_at', [$startOfYear, $endOfMonth])
                        ->sum('detail_piutang.nilai');

                    $hutangIn = (int) Hutang::whereBetween('tanggal', [$startOfYear, $endOfMonth])->sum('nilai');
                    $hutangOut = (int) DB::table('detail_hutang')
                        ->join('hutang', 'detail_hutang.id_hutang', '=', 'hutang.id')
                        ->whereBetween('detail_hutang.created_at', [$startOfYear, $endOfMonth])
                        ->sum('detail_hutang.nilai');

                    $kasBesar = $pemasukanIn
                        + $hutangIn
                        + $piutangIn
                        - $pengeluaranOut
                        - $pengeluaranBayarHutangOut
                        - $hutangOut
                        - $piutangOut;

                    $kasKecil = 0;

                $asetPeralatanBesar = (int) Pengeluaran::whereBetween('tanggal', [$startOfYear, $endOfMonth])->where('is_asset', 'Asset Peralatan Besar')->sum('nilai');
                $asetPeralatanKecil = (int) Pengeluaran::whereBetween('tanggal', [$startOfYear, $endOfMonth])->where('is_asset', 'Asset Peralatan Kecil')->sum('nilai');

                $piutangCreated = (int) Piutang::whereBetween('tanggal', [$startOfYear, $endOfMonth])->sum('nilai');
                $piutangPaid = (int) DB::table('detail_piutang')
                    ->join('piutang', 'detail_piutang.id_piutang', '=', 'piutang.id')
                    ->whereBetween('detail_piutang.created_at', [$startOfYear, $endOfMonth])
                    ->sum('detail_piutang.nilai');
                $piutangSaldo = max(0, $piutangCreated - $piutangPaid);

                $hutangCreated = (int) Hutang::whereBetween('tanggal', [$startOfYear, $endOfMonth])->sum('nilai');
                $hutangPaid = (int) DB::table('detail_hutang')
                    ->join('hutang', 'detail_hutang.id_hutang', '=', 'hutang.id')
                    ->whereBetween('detail_hutang.created_at', [$startOfYear, $endOfMonth])
                    ->sum('detail_hutang.nilai');
                $hutangSaldo = max(0, $hutangCreated - $hutangPaid);

                $modal = (int) Pemasukan::whereBetween('tanggal', [$startOfYear, $endOfMonth])
                    ->whereIn('id_jenis_pemasukan', [1, 2])
                    ->sum('nilai');

                $asetLancar = $kasBesar + $piutangSaldo;
                $asetTetap = $asetPeralatanBesar + $asetPeralatanKecil;
                $totalAktiva = round($asetLancar + $asetTetap);
                $totalHutang = $hutangSaldo;
                $totalEkuitas = round($totalAktiva - $totalHutang);
                $saldoBerjalan = $totalEkuitas - $modal;

                    return [
                    'kas_besar' => $kasBesar,
                    'kas_kecil' => $kasKecil,
                    'piutang' => $piutangSaldo,
                    'hutang' => $hutangSaldo,
                    'aset_besar' => $asetPeralatanBesar,
                    'aset_kecil' => $asetPeralatanKecil,
                    'modal' => $modal,
                    'saldo_berjalan' => $saldoBerjalan,
                    'total_aktiva' => $totalAktiva,
                    'total_hutang' => $totalHutang,
                    'total_ekuitas' => $totalEkuitas,
                ];
                } catch (\Throwable) {
                    return [
                        'kas_besar' => 0,
                        'kas_kecil' => 0,
                        'piutang' => 0,
                        'hutang' => 0,
                        'aset_besar' => 0,
                        'aset_kecil' => 0,
                        'modal' => 0,
                        'saldo_berjalan' => 0,
                        'total_aktiva' => 0,
                        'total_hutang' => 0,
                        'total_ekuitas' => 0,
                    ];
                }
            };

            $current = $computeForMonth($month);
            $totalAktiva = $current['total_aktiva'];
            $totalHutang = $current['total_hutang'];
            $totalEkuitas = $current['total_ekuitas'];
            $totalPasiva = $totalHutang + $totalEkuitas;

            $hutangItems = [
                [
                    "kode" => "III.1",
                    "nama" => "Hutang",
                    "nilai" => $totalHutang,
                ],
            ];

            $ekuitasItems = [
                [
                    "kode" => "IV.1",
                    "nama" => "Modal",
                    "nilai" => $current['modal'],
                ],
            ];

            $prevSaldo = 0;
            for ($i = 1; $i <= $month; $i++) {
                $mData = $computeForMonth($i);
                $currentSaldo = $mData['saldo_berjalan'];
                $delta = $currentSaldo - $prevSaldo;
                $prevSaldo = $currentSaldo;

                $periode = Carbon::create($year, $i, 1)->locale('id')->translatedFormat('F Y');
                $ekuitasItems[] = [
                    "kode" => "IV." . ($i + 1),
                    "nama" => $i === $month
                        ? "Saldo Berjalan Periode {$periode}"
                        : "Saldo Akhir Periode {$periode}",
                    "nilai" => $delta,
                ];
            }
            $totalPasiva = $totalHutang + $totalEkuitas;

            $data = [
                [
                    'kategori' => 'AKTIVA',
                    'total' => $totalAktiva,
                    'subkategori' => [
                        [
                            'judul' => 'I. ASET LANCAR',
                            'total' => round($current['kas_besar'] + $current['piutang']),
                            'item' => [
                                [
                                    "kode" => "I.1",
                                    "nama" => "Kas Besar",
                                    "nilai" => $current['kas_besar'],
                                ],
                                [
                                    "kode" => "I.2",
                                    "nama" => "Piutang",
                                    "nilai" => $current['piutang'],
                                ],
                            ],
                        ],
                        [
                            'judul' => 'II. ASET TETAP',
                            'total' => $current['aset_besar'] + $current['aset_kecil'],
                            'item' => [
                                [
                                    "kode" => "II.1",
                                    "nama" => "Peralatan Besar",
                                    "nilai" => $current['aset_besar'],
                                ],
                                [
                                    "kode" => "II.2",
                                    "nama" => "Peralatan Kecil",
                                    "nilai" => $current['aset_kecil'],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'kategori' => 'PASIVA',
                    'total' => round($totalPasiva),
                    'subkategori' => [
                        [
                            'judul' => 'III. HUTANG',
                            'total' => $totalHutang,
                            'item' => $hutangItems,
                        ],
                        [
                            'judul' => 'IV. EKUITAS',
                            'total' => round($totalEkuitas),
                            'item' => $ekuitasItems,
                        ],
                    ],
                ],
            ];

            return response()->json([
                'data' => $data,
                'status_code' => 200,
                'errors' => false,
                'message' => 'Berhasil'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Tidak Ada',
                'message_back' => $th->getMessage(),
                'status_code' => 500,
            ]);
        }
    }

}
