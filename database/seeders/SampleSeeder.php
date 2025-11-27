<?php

namespace Database\Seeders;

use App\Imports\IpltImport;
use App\Imports\SanitasiImport;
use App\Imports\SpaldImport;
use App\Imports\TpaImport;
use App\Imports\Tps3rImport;
use App\Imports\TpstImport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sanitasi = public_path('master/sample_sanitasi.xlsx');
        $tpa = public_path('master/sample_tpa.xlsx');
        $tpst = public_path('master/sample_tpst.xlsx');
        $tps3r = public_path('master/sample_tps3r.xlsx');
        $iplt = public_path('master/sample_iplt.xlsx');
        $spald = public_path('master/sample_spald.xlsx');
        DB::beginTransaction();
        try {
            Excel::import(new SanitasiImport, $sanitasi);
            Excel::import(new TpaImport, $tpa);
            Excel::import(new TpstImport, $tpst);
            Excel::import(new Tps3rImport, $tps3r);
            Excel::import(new IpltImport, $iplt);
            Excel::import(new SpaldImport, $spald);
            DB::commit();
            $this->command->info('Import Sample berhasil!');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->command->error($th->getMessage());
        }
    }
}
