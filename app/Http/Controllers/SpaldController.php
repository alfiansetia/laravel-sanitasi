<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Spald;
use Illuminate\Http\Request;

class SpaldController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::all();
        return view('spald.index', [
            'kecamatans'    => $kecamatans,
            'title'         => 'SPALD',
            'title_desc'    => 'Sistem Pengelolaan Air Limbah Domestik',
            'sample_import' =>  asset('master/sample_spald.xlsx'),
        ]);
    }
}
