<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Tpst;
use Illuminate\Http\Request;

class TpstController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::all();
        return view('tpst.index', [
            'kecamatans'    => $kecamatans,
            'title'         => 'TPST'
        ]);
    }
}
