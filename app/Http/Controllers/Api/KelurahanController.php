<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\KelurahanRequest;
use App\Models\Kecamatan;
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

    public function store(KelurahanRequest $request)
    {

        $kelurahan = Kelurahan::create($request->mappedData());
        return $this->sendResponse($kelurahan, 'Created!');
    }

    public function update(KelurahanRequest $request, Kelurahan $kelurahan)
    {
        $kelurahan->update($request->mappedData());
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
            $data = Excel::toCollection([], $file)[0]->skip(1);
            $results = collect();

            foreach ($data as $index => $item) {
                if ($item->filter()->isEmpty()) {
                    continue;
                }
                $kode = $item[0] ?? null;
                $kel  = $item[1] ?? null;
                $kec  = $item[2] ?? null;
                if (empty($kode) || empty($kel) || empty($kec)) {
                    throw new Exception("Data tidak lengkap di baris " . ($index + 2));
                }
                $kecamatan = Kecamatan::firstOrCreate([
                    'nama' => $kec,
                ]);
                $kelurahan = Kelurahan::firstOrCreate([
                    'kode' => $kode,
                ], [
                    'kecamatan_id'  => $kecamatan->id,
                    'nama'          => $kel,
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
