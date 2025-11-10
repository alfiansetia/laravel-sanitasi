<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecs = ['Binjai', 'Aduhai1', 'Adudu2', "Sampitak"];
        foreach ($kecs as $key => $value) {
            $kec = Kecamatan::create([
                'nama' => $value
            ]);
            for ($i = 0; $i < 10; $i++) {
                Kelurahan::create([
                    'kecamatan_id'  => $kec->id,
                    'nama'          => "Kel $value $i",
                    'kode'          => $i . random_int(1000, 2000)
                ]);
            }
        }
    }
}
