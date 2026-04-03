<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Member::create([
            "id" => 1,
            "nama_member" => "Jukis",
            "no_hp" => "089581957723",
            "alamat" => "Jagasatru",
        ]);

        Member::create([
            "id" => 2,
            "nama_member" => "Okta",
            "no_hp" => "089581957723",
            "alamat" => "Jagasatru",
        ]);
    }
}
