<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    public function index()
    {
        return view('kecamatan.index', [
            'title'         => 'KECAMATAN',
            'sample_import' =>  asset('master/sample_kecamatan.xlsx'),
        ]);
    }
}
