<?php

namespace App\Http\Controllers;

use App\Models\Sanitasi;
use Illuminate\Http\Request;

class SanitasiController extends Controller
{
    public function index()
    {
        return view('sanitasi.index', [
            'title' => 'PEMBANGUNAN'
        ]);
    }
}
