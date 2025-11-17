<?php

namespace App\Http\Controllers;

use App\Models\Iplt;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class IpltController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::all();
        return view('iplt.index', [
            'kecamatans'    => $kecamatans,
            'title'         => 'IPLT',
            'title_desc'    => 'Instalasi Pengolahan Limbah Terpadu',
            'sample_import' => asset('master/sample_iplt.xlsx'),

        ]);
    }
}
