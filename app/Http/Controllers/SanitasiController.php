<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Sanitasi;
use Illuminate\Http\Request;

class SanitasiController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::all();
        return view('sanitasi.index', [
            'kecamatans'    => $kecamatans,
            'title'         => 'PEMBANGUNAN',
            'sample_import' =>  asset('master/sample_sanitasi.xlsx'),
        ]);
    }
}
