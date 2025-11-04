<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Spaldt;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SpaldtController extends Controller
{
    public function index()
    {
        $query = Spaldt::query();
        return DataTables::eloquent($query)->toJson();
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
                $spaldt = Spaldt::create([
                    'tahun'     => $tahun,
                    'nama'      => $nama,
                    'lokasi'    => $lokasi,
                    'pagu'      => $pagu,
                    'jumlah'    => $jumlah,
                    'sumber'    => $sumber,
                    'lat'       => $lat,
                    'long'      => $long,
                ]);
                $results->add($spaldt);
            }
            DB::commit();
            return $this->sendResponse($results . 'Success Import Data!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message'   => 'Gagal import: ' . $th->getMessage(),
                'data'      => $data,
            ], 500);
            return $this->sendError('Gagal import: ' . $th->getMessage(), 500);
        }
    }
}
