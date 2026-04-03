<?php

namespace App\Imports;

use App\Models\Member;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class MemberImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows->skip(1) as $row) {
            try {
                $namaMember = $row[0] ?? null;
                $noHp = $row[1] ?? null;
                $alamat = $row[2] ?? null;

                if (empty($namaMember) || empty($noHp) || empty($alamat)) {
                    $namaMember = $row[2] ?? null;
                    $noHp = $row[3] ?? null;
                    $alamat = $row[4] ?? null;
                }

                if (empty($namaMember) || empty($noHp) || empty($alamat)) {
                    throw new \Exception('Data tidak valid pada baris: ' . json_encode($row));
                }

                Member::create([
                    'nama_member' => $namaMember,
                    'no_hp' => $noHp,
                    'alamat' => $alamat
                ]);

            } catch (\Exception $e) {
                Log::error('Error pada baris: ' . json_encode($row) . ' - ' . $e->getMessage());
                continue;
            }
        }
    }
}
