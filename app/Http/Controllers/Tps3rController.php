<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Tps3r;
use Illuminate\Http\Request;

class Tps3rController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::all();
        return view('tps3r.index', [
            'kecamatans'    => $kecamatans,
            'title'         => 'TPS3R',
            'title_desc'    => 'Tempat Pengolahan Sampah 3R',
        ]);
    }
}
