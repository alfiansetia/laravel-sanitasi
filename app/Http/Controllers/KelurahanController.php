<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;

class KelurahanController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::all();
        return view('kelurahan.index', [
            'kecamatans'    => $kecamatans,
            'title'         => 'KELURAHAN',
            'sample_import' =>  asset('master/sample_kelurahan.xlsx'),
        ]);
    }
}
