<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = Kecamatan::query()
            ->withCount([
                'sanitasis',
                'tpas',
                'tpsts',
                'tps3rs',
                'iplts',
                'spalds',
            ])->orderBy('nama', 'ASC')->get();
        return $this->sendResponse($data);
    }
}
