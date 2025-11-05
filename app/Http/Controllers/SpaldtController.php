<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpaldtController extends Controller
{
    public function index()
    {
        return view('spaldts.index', [
            'title' => 'SPALD-T'
        ]);
    }
}
