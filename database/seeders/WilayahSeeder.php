<?php

namespace Database\Seeders;

use App\Imports\KecamatanImport;
use App\Imports\KelurahanImport;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kec = public_path('master/sample_kecamatan.xlsx');
        $kel = public_path('master/sample_kelurahan.xlsx');
        DB::beginTransaction();
        try {
            Excel::import(new KecamatanImport, $kec);
            Excel::import(new KelurahanImport, $kel);
            DB::commit();
            $this->command->info('Import Kecamatan & Kelurahan berhasil!');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->command->error($th->getMessage());
        }
    }
}
