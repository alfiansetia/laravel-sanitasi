<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelurahan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class KelurahanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelurahan::query()
            ->with('kecamatan')
            ->filter($request->only(Kelurahan::$filterProp));
        return DataTables::eloquent($query)->toJson();
    }

    public function show(Kelurahan $kelurahan)
    {
        return $this->sendResponse($kelurahan->load('kecamatan'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'kecamatan_id'  => 'required|exists:kecamatans,id',
            'kode'          => 'required|string|max:200|unique:kelurahans,kode',
            'nama'          => 'required|string|max:200',
        ]);
        $kelurahan = Kelurahan::create([
            'kecamatan_id'  => $request->kecamatan_id,
            'kode'          => $request->kode,
            'nama'          => $request->nama,
        ]);
        return $this->sendResponse($kelurahan, 'Created!');
    }

    public function update(Request $request, Kelurahan $kelurahan)
    {
        $this->validate($request, [
            'kecamatan_id'  => 'required|exists:kecamatans,id',
            'kode'          => 'required|string|max:200|unique:kelurahans,kode,' . $kelurahan->id,
            'nama'          => 'required|string|max:200',
        ]);
        $kelurahan->update([
            'kecamatan_id'  => $request->kecamatan_id,
            'kode'          => $request->kode,
            'nama'          => $request->nama,
        ]);
        return $this->sendResponse($kelurahan, 'Updated!');
    }

    public function destroy(Kelurahan $kelurahan)
    {
        $kelurahan->delete();
        return $this->sendResponse($kelurahan, 'Deleted!');
    }

    public function destroy_batch(Request $request)
    {
        $this->validate($request, [
            'ids'       => 'required|array',
            'ids.*'     => 'integer|exists:kelurahans,id',
        ]);
        $deleted = Kelurahan::whereIn('id', $request->ids)->delete();

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
                $kelurahan = Kelurahan::create([
                    'tahun'     => $tahun,
                    'nama'      => $nama,
                    'lokasi'    => $lokasi,
                    'pagu'      => $pagu,
                    'jumlah'    => $jumlah,
                    'sumber'    => $sumber,
                    'lat'       => $lat,
                    'long'      => $long,
                ]);
                $results->add($kelurahan);
            }
            DB::commit();
            return $this->sendResponse($results, 'Success Import Data!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError('Gagal import: ' . $th->getMessage(), 500);
        }
    }
}
