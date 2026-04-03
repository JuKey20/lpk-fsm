<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Imports\MemberImport;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
use Carbon\Carbon;

class MemberController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Data Member',
        ];
    }

    public function getmember(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = Member::query();

        $query->orderBy('id', $meta['orderBy']);

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_member) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(no_hp) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(alamat) LIKE ?", ["%$searchTerm%"]);
                if (Schema::hasColumn('member', 'nik')) {
                    $query->orWhereRaw("LOWER(nik) LIKE ?", ["%$searchTerm%"]);
                }
                if (Schema::hasColumn('member', 'alamat_domisili')) {
                    $query->orWhereRaw("LOWER(alamat_domisili) LIKE ?", ["%$searchTerm%"]);
                }
            });
        }

        $data = $query->paginate($meta['limit']);

        $paginationMeta = [
            'total' => $data->total(),
            'per_page' => $data->perPage(),
            'current_page' => $data->currentPage(),
            'total_pages' => $data->lastPage()
        ];

        $data = [
            'data' => $data->items(),
            'meta' => $paginationMeta
        ];

        if (empty($data['data'])) {
            return response()->json([
                'status_code' => 400,
                'errors' => true,
                'message' => 'Tidak ada data'
            ], 400);
        }

        $items = collect($data['data']);

        $provinceMap = collect();
        $cityMap = collect();
        $districtMap = collect();
        $villageMap = collect();

        if (Schema::hasColumn('member', 'province_code')) {
            $provinceCodes = $items->pluck('province_code')->filter()->unique()->values()->all();
            if (!empty($provinceCodes)) {
                $provinceMap = Province::whereIn('code', $provinceCodes)->pluck('name', 'code');
            }
        }
        if (Schema::hasColumn('member', 'city_code')) {
            $cityCodes = $items->pluck('city_code')->filter()->unique()->values()->all();
            if (!empty($cityCodes)) {
                $cityMap = City::whereIn('code', $cityCodes)->pluck('name', 'code');
            }
        }
        if (Schema::hasColumn('member', 'district_code')) {
            $districtCodes = $items->pluck('district_code')->filter()->unique()->values()->all();
            if (!empty($districtCodes)) {
                $districtMap = District::whereIn('code', $districtCodes)->pluck('name', 'code');
            }
        }
        if (Schema::hasColumn('member', 'village_code')) {
            $villageCodes = $items->pluck('village_code')->filter()->unique()->values()->all();
            if (!empty($villageCodes)) {
                $villageMap = Village::whereIn('code', $villageCodes)->pluck('name', 'code');
            }
        }

        $mappedData = $items->map(function ($item) use ($provinceMap, $cityMap, $districtMap, $villageMap) {
            $tempatLahir = $item->tempat_lahir ?? null;
            $tanggalLahir = $item->tanggal_lahir ?? null;
            $tanggalLahirFormatted = null;
            if (!empty($tanggalLahir)) {
                try {
                    $tanggalLahirFormatted = Carbon::parse($tanggalLahir)->locale('id')->translatedFormat('d F Y');
                } catch (\Throwable $e) {
                    $tanggalLahirFormatted = null;
                }
            }

            $provinceCode = $item->province_code ?? null;
            $cityCode = $item->city_code ?? null;
            $districtCode = $item->district_code ?? null;
            $villageCode = $item->village_code ?? null;

            $provinsi = $provinceCode ? ($provinceMap[$provinceCode] ?? null) : null;
            $kabupaten = $cityCode ? ($cityMap[$cityCode] ?? null) : null;
            $kecamatan = $districtCode ? ($districtMap[$districtCode] ?? null) : null;
            $desa = $villageCode ? ($villageMap[$villageCode] ?? null) : null;

            $kabupatenLabel = $kabupaten ? preg_replace('/^(Kabupaten|Kab\\.|Kota)\\s+/i', '', $kabupaten) : null;
            if ($kabupaten && preg_match('/^Kota\\s+/i', $kabupaten)) {
                $kabupatenLabel = $kabupaten;
            }

            $kabupatenProvinsi = implode(', ', array_filter([$kabupatenLabel, $provinsi])) ?: '-';
            $kecamatanDesa = implode(', ', array_filter([$kecamatan, $desa])) ?: '-';

            return [
                'id' => $item['id'],
                'nama' => $item['nama_member'] ?? '-',
                'nik' => $item->nik ?? '-',
                'tempat_lahir' => $tempatLahir ?? '-',
                'tanggal_lahir' => $tanggalLahirFormatted ?? '-',
                'kabupaten_provinsi' => $kabupatenProvinsi,
                'kecamatan_desa' => $kecamatanDesa,
                'jenis_kelamin' => $item->jenis_kelamin ?? '-',
                'agama' => $item->agama ?? '-',
                'no_hp' => $item->no_hp ?? '-',
                'email' => $item->email ?? '-',
                'tahun_masuk_lpk' => $item->tahun_ajaran ?? '-',
            ];
        });

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Sukses',
            'pagination' => $data['meta']
        ], 200);
    }

    public function index()
    {
        if (!in_array(Auth::user()->id_level, [1, 2, 3, 4])) {
            abort(403, 'Unauthorized');
        }

        $menu = [$this->title[0], $this->label[0]];
        $member = Member::orderBy('id', 'desc')->get();

        return view('master.member.index', compact('menu', 'member'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'nama_member' => 'required',
                'nik' => 'required|digits:16|unique:member,nik',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required',
                'agama' => 'required',
                'status_pernikahan' => 'required',
                'no_hp' => 'required',
                'email' => 'required|email',
                'alamat_domisili' => 'required',
                'province_code' => 'required',
                'city_code' => 'required',
                'district_code' => 'required',
                'village_code' => 'required',
                'pendidikan_terakhir' => 'required',
                'jurusan' => 'required',
                'nama_sekolah' => 'required',
                'tahun_ajaran' => 'required|integer|min:1900|max:2100',
                'tahun_lulus' => 'required|digits:4',
            ],
            [
                'nama_member.required' => 'Nama Member tidak boleh kosong',
                'nik.required' => 'NIK wajib diisi',
                'nik.digits' => 'NIK harus 16 digit',
                'nik.unique' => 'NIK sudah terdaftar',
                'tempat_lahir.required' => 'Tempat lahir wajib diisi',
                'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
                'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
                'jenis_kelamin.required' => 'Jenis kelamin wajib diisi',
                'agama.required' => 'Agama wajib diisi',
                'status_pernikahan.required' => 'Status pernikahan wajib diisi',
                'no_hp.required' => 'No Hp Wajib diisi',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'alamat_domisili.required' => 'Alamat domisili wajib diisi',
                'province_code.required' => 'Provinsi wajib diisi',
                'city_code.required' => 'Kabupaten/Kota wajib diisi',
                'district_code.required' => 'Kecamatan wajib diisi',
                'village_code.required' => 'Desa/Kelurahan wajib diisi',
                'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib diisi',
                'jurusan.required' => 'Jurusan wajib diisi',
                'nama_sekolah.required' => 'Nama sekolah/universitas wajib diisi',
                'tahun_ajaran.required' => 'Tahun masuk LPK wajib diisi',
                'tahun_ajaran.integer' => 'Tahun masuk LPK harus berupa angka',
                'tahun_ajaran.min' => 'Tahun masuk LPK tidak valid',
                'tahun_ajaran.max' => 'Tahun masuk LPK tidak valid',
                'tahun_lulus.required' => 'Tahun lulus wajib diisi',
                'tahun_lulus.digits' => 'Tahun lulus harus 4 digit',
            ]
        );

        ActivityLogger::log('Tambah Member', $request->all());

        try {
            Member::create([
                'nama_member' => $request->nama_member,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat_domisili,
                'nik' => $request->nik,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama' => $request->agama,
                'status_pernikahan' => $request->status_pernikahan,
                'alamat_domisili' => $request->alamat_domisili,
                'email' => $request->email,
                'province_code' => $request->province_code,
                'city_code' => $request->city_code,
                'district_code' => $request->district_code,
                'village_code' => $request->village_code,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'jurusan' => $request->jurusan,
                'nama_sekolah' => $request->nama_sekolah,
                'tahun_ajaran' => $request->tahun_ajaran,
                'tahun_lulus' => $request->tahun_lulus,
            ]);

        } catch (Throwable $th) {
            return redirect()->route('master.member.index')->with('error', $th->getMessage())->withInput();
        }

        return redirect()->route('master.member.index')->with('success', 'Sukses menambahkan Member Baru');
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $request->validate(
                [
                    'nama_member' => 'required',
                    'nik' => 'required|digits:16|unique:member,nik,' . $id . ',id',
                    'tempat_lahir' => 'required',
                    'tanggal_lahir' => 'required|date',
                    'jenis_kelamin' => 'required',
                    'agama' => 'required',
                    'status_pernikahan' => 'required',
                    'tahun_ajaran' => 'required|integer|min:1900|max:2100',
                    'no_hp' => 'required',
                    'email' => 'required|email',
                    'alamat_domisili' => 'required',
                    'province_code' => 'required',
                    'city_code' => 'required',
                    'district_code' => 'required',
                    'village_code' => 'required',
                    'pendidikan_terakhir' => 'required',
                    'jurusan' => 'required',
                    'nama_sekolah' => 'required',
                    'tahun_lulus' => 'required|digits:4',
                ],
                [
                    'nama_member.required' => 'Nama Member tidak boleh kosong',
                    'nik.required' => 'NIK wajib diisi',
                    'nik.digits' => 'NIK harus 16 digit',
                    'nik.unique' => 'NIK sudah terdaftar',
                    'tempat_lahir.required' => 'Tempat lahir wajib diisi',
                    'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
                    'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
                    'jenis_kelamin.required' => 'Jenis kelamin wajib diisi',
                    'agama.required' => 'Agama wajib diisi',
                    'status_pernikahan.required' => 'Status pernikahan wajib diisi',
                    'no_hp.required' => 'No Hp Wajib diisi',
                    'email.required' => 'Email wajib diisi',
                    'email.email' => 'Format email tidak valid',
                    'alamat_domisili.required' => 'Alamat domisili wajib diisi',
                    'province_code.required' => 'Provinsi wajib diisi',
                    'city_code.required' => 'Kabupaten/Kota wajib diisi',
                    'district_code.required' => 'Kecamatan wajib diisi',
                    'village_code.required' => 'Desa/Kelurahan wajib diisi',
                    'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib diisi',
                    'jurusan.required' => 'Jurusan wajib diisi',
                    'nama_sekolah.required' => 'Nama sekolah/universitas wajib diisi',
                    'tahun_ajaran.required' => 'Tahun masuk LPK wajib diisi',
                    'tahun_ajaran.integer' => 'Tahun masuk LPK harus berupa angka',
                    'tahun_ajaran.min' => 'Tahun masuk LPK tidak valid',
                    'tahun_ajaran.max' => 'Tahun masuk LPK tidak valid',
                    'tahun_lulus.required' => 'Tahun lulus wajib diisi',
                    'tahun_lulus.digits' => 'Tahun lulus harus 4 digit',
                ]
            );

            $member = Member::findOrFail($id);

            $updateData = [
                'nama_member' => $request->nama_member,
                'nik' => $request->nik,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama' => $request->agama,
                'status_pernikahan' => $request->status_pernikahan,
                'tahun_ajaran' => $request->tahun_ajaran,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'alamat_domisili' => $request->alamat_domisili,
                'alamat' => $request->alamat_domisili,
                'province_code' => $request->province_code,
                'city_code' => $request->city_code,
                'district_code' => $request->district_code,
                'village_code' => $request->village_code,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'jurusan' => $request->jurusan,
                'nama_sekolah' => $request->nama_sekolah,
                'tahun_lulus' => $request->tahun_lulus,
            ];

            $updated = $member->update($updateData);

            if (!$updated) {
                throw new \Exception('Gagal memperbarui data member');
            }

            ActivityLogger::log('Update Member', ['id' => $id, 'data' => $updateData]);

            DB::commit();
            return redirect()->route('master.member.index')->with('success', 'Sukses memperbarui Member');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('master.member.index')
                ->with('error', 'Gagal memperbarui Member: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function detail($id)
    {
        $member = Member::findOrFail($id);

        $tanggalLahirFormatted = null;
        if (!empty($member->tanggal_lahir)) {
            try {
                $tanggalLahirFormatted = Carbon::parse($member->tanggal_lahir)->locale('id')->translatedFormat('d F Y');
            } catch (\Throwable $e) {
                $tanggalLahirFormatted = null;
            }
        }

        $provinsi = null;
        $kabupaten = null;
        $kecamatan = null;
        $desa = null;

        if (!empty($member->province_code)) {
            $provinsi = Province::where('code', $member->province_code)->value('name');
        }
        if (!empty($member->city_code)) {
            $kabupaten = City::where('code', $member->city_code)->value('name');
        }
        if (!empty($member->district_code)) {
            $kecamatan = District::where('code', $member->district_code)->value('name');
        }
        if (!empty($member->village_code)) {
            $desa = Village::where('code', $member->village_code)->value('name');
        }

        $kabupatenLabel = $kabupaten ? preg_replace('/^(Kabupaten|Kab\\.|Kota)\\s+/i', '', $kabupaten) : null;
        if ($kabupaten && preg_match('/^Kota\\s+/i', $kabupaten)) {
            $kabupatenLabel = $kabupaten;
        }

        $kabupatenProvinsi = implode(', ', array_filter([$kabupatenLabel, $provinsi])) ?: null;
        $kecamatanDesa = implode(', ', array_filter([$kecamatan, $desa])) ?: null;

        return response()->json([
            'status_code' => 200,
            'errors' => false,
            'message' => 'Sukses',
            'data' => [
                'id' => $member->id,
                'nama' => $member->nama_member,
                'nik' => $member->nik,
                'tempat_lahir' => $member->tempat_lahir,
                'tanggal_lahir' => $tanggalLahirFormatted,
                'jenis_kelamin' => $member->jenis_kelamin,
                'agama' => $member->agama,
                'status_pernikahan' => $member->status_pernikahan,
                'tahun_masuk_lpk' => $member->tahun_ajaran,
                'no_hp' => $member->no_hp,
                'email' => $member->email,
                'alamat_domisili' => $member->alamat_domisili ?? $member->alamat,
                'kabupaten_provinsi' => $kabupatenProvinsi,
                'kecamatan_desa' => $kecamatanDesa,
                'pendidikan_terakhir' => $member->pendidikan_terakhir,
                'jurusan' => $member->jurusan,
                'nama_sekolah' => $member->nama_sekolah,
                'tahun_lulus' => $member->tahun_lulus,
            ],
        ], 200);
    }

    public function provinces()
    {
        $data = Province::orderBy('name')->get(['code as id', 'name']);
        return response()->json($data);
    }

    public function regencies($provinceCode)
    {
        $data = City::where('province_code', $provinceCode)->orderBy('name')->get(['code as id', 'name']);
        return response()->json($data);
    }

    public function districts($cityCode)
    {
        $data = District::where('city_code', $cityCode)->orderBy('name')->get(['code as id', 'name']);
        return response()->json($data);
    }

    public function villages($districtCode)
    {
        $data = Village::where('district_code', $districtCode)->orderBy('name')->get(['code as id', 'name']);
        return response()->json($data);
    }

    public function delete(string $id)
    {
        $member = Member::findOrFail($id);

        ActivityLogger::log('Delete Member', ['id' => $id]);

        try {
            DB::beginTransaction();

            $member->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sukses menghapus Data Member'
            ]);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Data Member: ' . $th->getMessage()
            ], 500);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        Excel::import(new MemberImport, $request->file('file'));

        return back()->with('success', 'Data berhasil diimpor!');
    }
}
