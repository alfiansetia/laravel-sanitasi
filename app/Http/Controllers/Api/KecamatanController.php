<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class KecamatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kecamatan::query()
            ->filter($request->only(['nama']));
        return DataTables::eloquent($query)->toJson();
    }

    public function show(Kecamatan $kecamatan)
    {
        return $this->sendResponse($kecamatan->load('kelurahans'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama'      => 'required|string|max:200|unique:kecamatans,nama',
        ]);
        $kecamatan = Kecamatan::create([
            'nama'      => $request->nama,
        ]);
        return $this->sendResponse($kecamatan, 'Created!');
    }

    public function update(Request $request, Kecamatan $kecamatan)
    {
        $this->validate($request, [
            'nama'      => 'required|string|max:200|unique:kecamatans,nama,' . $kecamatan->id,
        ]);
        $kecamatan->update([
            'nama'      => $request->nama,
        ]);
        return $this->sendResponse($kecamatan, 'Updated!');
    }

    public function destroy(Kecamatan $kecamatan)
    {
        $kecamatan->delete();
        return $this->sendResponse($kecamatan, 'Deleted!');
    }

    public function destroy_batch(Request $request)
    {
        $this->validate($request, [
            'ids'       => 'required|array',
            'ids.*'     => 'integer|exists:kecamatans,id',
        ]);
        $deleted = Kecamatan::whereIn('id', $request->ids)->delete();

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
                $kecamatan = Kecamatan::create([
                    'tahun'     => $tahun,
                    'nama'      => $nama,
                    'lokasi'    => $lokasi,
                    'pagu'      => $pagu,
                    'jumlah'    => $jumlah,
                    'sumber'    => $sumber,
                    'lat'       => $lat,
                    'long'      => $long,
                ]);
                $results->add($kecamatan);
            }
            DB::commit();
            return $this->sendResponse($results, 'Success Import Data!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError('Gagal import: ' . $th->getMessage(), 500);
        }
    }
}
