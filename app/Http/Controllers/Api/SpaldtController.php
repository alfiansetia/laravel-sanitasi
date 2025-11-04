<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Spaldt;
use Illuminate\Http\Request;

class SpaldtController extends Controller
{
    public function index()
    {
        $data = Spaldt::query()->get();
        return $this->sendResponse($data);
    }

    public function show(Spaldt $spaldt)
    {
        return $this->sendResponse($spaldt);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'tahun'     => 'required|date_format:Y',
            'nama'      => 'required|max:5000',
            'lokasi'    => 'required|max:200',
            'pagu'      => 'required|integer|gte:0',
            'jumlah'    => 'required|integer|gte:0',
            'sumber'    => 'required|in:DAK,DAU',
            'lat'       => 'nullable',
            'long'      => 'nullable',
        ]);
        $spaldt = Spaldt::create([
            'tahun'     => $request->tahun,
            'nama'      => $request->nama,
            'lokasi'    => $request->lokasi,
            'pagu'      => $request->pagu,
            'jumlah'    => $request->jumlah,
            'sumber'    => $request->sumber,
            'lat'       => $request->lat,
            'long'      => $request->long,
        ]);
        return $this->sendResponse($spaldt, 'Created!');
    }

    public function update(Request $request, Spaldt $spaldt)
    {
        $this->validate($request, [
            'tahun'     => 'required|date_format:Y',
            'nama'      => 'required|max:5000',
            'lokasi'    => 'required|max:200',
            'pagu'      => 'required|integer|gte:0',
            'jumlah'    => 'required|integer|gte:0',
            'sumber'    => 'required|in:DAK,DAU',
            'lat'       => 'nullable',
            'long'      => 'nullable',
        ]);
        $spaldt->update([
            'tahun'     => $request->tahun,
            'nama'      => $request->nama,
            'lokasi'    => $request->lokasi,
            'pagu'      => $request->pagu,
            'jumlah'    => $request->jumlah,
            'sumber'    => $request->sumber,
            'lat'       => $request->lat,
            'long'      => $request->long,
        ]);
        return $this->sendResponse($spaldt, 'Updated!');
    }

    public function destroy(Spaldt $spaldt)
    {
        $spaldt->delete();
        return $this->sendResponse($spaldt, 'Deleted!');
    }

    public function destroy_batch(Request $request)
    {
        $this->validate($request, [
            'ids'       => 'required|array',
            'ids.*'     => 'integer|exists:spaldts,id',
        ]);
        $deleted = Spaldt::whereIn('id', $request->ids)->delete();

        return $this->sendResponse([
            'deleted_count' => $deleted
        ], 'Data deleted successfully.');
    }
}
