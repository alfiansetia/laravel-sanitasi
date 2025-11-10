<?php

namespace App\Http\Controllers;

use App\Models\Tpa;
use Illuminate\Http\Request;

class TpaController extends Controller
{
    public function index()
    {
        return view('tpa.index', [
            'title' => 'TPA'
        ]);
    }
}
