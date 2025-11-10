<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Tpa;
use Illuminate\Http\Request;

class TpaController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::all();
        return view('tpa.index', [
            'kecamatans'    => $kecamatans,
            'title'         => 'TPA'
        ]);
    }
}
