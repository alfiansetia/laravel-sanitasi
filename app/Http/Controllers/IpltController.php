<?php

namespace App\Http\Controllers;

use App\Models\Iplt;
use Illuminate\Http\Request;

class IpltController extends Controller
{
    public function index()
    {
        return view('spaldts.index', [
            'title' => 'IPLT'
        ]);
    }
}
