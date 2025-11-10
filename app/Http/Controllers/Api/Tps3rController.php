<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tps3rRequest;
use App\Models\Tps3r;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class Tps3rController extends Controller
{
    public function index(Request $request)
    {
        $query = Tps3r::query()
            ->with([
                'kecamatan',
                'kelurahan',
            ])
            ->filter($request->only(Tps3r::$filterProp));
        return DataTables::eloquent($query)->toJson();
    }

    public function show(Tps3r $tps3r)
    {
        return $this->sendResponse($tps3r->load([
            'kecamatan',
            'kelurahan',
        ]));
    }

    public function store(Tps3rRequest $request)
    {
        $tps3r = Tps3r::create($request->mappedData());
        return $this->sendResponse($tps3r, 'Created!');
    }

    public function update(Tps3rRequest $request, Tps3r $tps3r)
    {
        $tps3r->update($request->mappedData());
        return $this->sendResponse($tps3r, 'Updated!');
    }

    public function destroy(Tps3r $tps3r)
    {
        $tps3r->delete();
        return $this->sendResponse($tps3r, 'Deleted!');
    }

    public function destroy_batch(Request $request)
    {
        $this->validate($request, [
            'ids'       => 'required|array',
            'ids.*'     => 'integer|exists:tps3r,id',
        ]);
        $deleted = Tps3r::whereIn('id', $request->ids)->delete();

        return $this->sendResponse([
            'deleted_count' => $deleted
        ], 'Data deleted successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);
        DB::beginTransaction();
        try {
            $file = $request->file('file');
            $data = Excel::toCollection([], $file)[0]->skip(2);
            $results = collect();

            foreach ($data as $index => $item) {
                $tahun = $item[1] ?? null;
                $nama = $item[2] ?? null;
                $lokasi = $item[3] ?? null;
                $pagu = $item[4] ?? null;
                $jumlah = $item[5] ?? 0;
                $sumber = $item[6] ?? null;
                $lat = $item[7] ?? null;
                $long = $item[8] ?? null;
                $sum =  strtoupper(trim($sumber));

                if (empty($tahun) || empty($nama) || empty($lokasi) || empty($sumber)) {
                    throw new Exception("Data tidak lengkap di baris " . ($index + 2));
                }

                if (! in_array($sum, ['DAK', 'DAU'], true)) {
                    throw new Exception("Data sumber tidak valid di baris " . ($index + 2) . " (nilai: '{$sumber}')");
                }
                $tps3r = Tps3r::create([
                    'tahun'     => $tahun,
                    'nama'      => $nama,
                    'lokasi'    => $lokasi,
                    'pagu'      => $pagu,
                    'jumlah'    => $jumlah,
                    'sumber'    => $sumber,
                    'lat'       => $lat,
                    'long'      => $long,
                ]);
                $results->add($tps3r);
            }
            DB::commit();
            return $this->sendResponse($results, 'Success Import Data!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError('Gagal import: ' . $th->getMessage(), 500);
        }
    }
}
