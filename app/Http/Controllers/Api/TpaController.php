<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tpa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class TpaController extends Controller
{
    public function index(Request $request)
    {
        $query = Tpa::query()
            ->with([
                'kecamatan',
                'kelurahan',
                'kecamatan_terlayani.kecamatan'
            ])
            ->filter($request->only([
                'nama',
                'sumber',
                'kecamatan_id',
                'kelurahan_id',
                'tahun_konstruksi',
                'tahun_beroperasi',
                'pengelola',
                'kondisi',
            ]));
        return DataTables::eloquent($query)->toJson();
    }

    public function show(Tpa $tpa)
    {
        return $this->sendResponse($tpa);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama'                  => 'required|max:200',
            'kecamatan_id'          => 'required|exists:kecamatans,id',
            'kelurahan_id'          => 'required|exists:kelurahans,id',
            'latitude'              => 'required|numeric|between:-90,90',
            'longitude'             => 'required|numeric|between:-180,180',
            'sumber'                => 'required|in:DAK,DAU',
            'tahun_konstruksi'      => 'required|date_format:Y',
            'tahun_beroperasi'      => 'required|date_format:Y',
            'rencana'               => 'required|integer|gte:0',
            'kecamatan_terlayani'   => 'nullable|array',
            'kecamatan_terlayani.*' => 'exists:kecamatans,id',
            'luas_sarana'           => 'required|numeric|gte:0',
            'luas_sel'              => 'required|numeric|gte:0',
            'pengelola'             => 'required|in:Dinas,UPT',
            'pengelola_desc'        => 'nullable|string|max:200',
            'kondisi'               => 'required|in:Baik,Tidak Baik',
        ]);
        $tpa = Tpa::create([
            'nama'              => $request->nama,
            'kecamatan_id'      => $request->kecamatan_id,
            'kelurahan_id'      => $request->kelurahan_id,
            'lat'               => $request->latitude,
            'long'              => $request->longitude,
            'sumber'            => $request->sumber,
            'tahun_konstruksi'  => $request->tahun_konstruksi,
            'tahun_beroperasi'  => $request->tahun_beroperasi,
            'rencana'           => $request->rencana,
            'luas_sarana'       => $request->luas_sarana,
            'luas_sel'          => $request->luas_sel,
            'pengelola'         => $request->pengelola,
            'pengelola_desc'    => $request->pengelola_desc,
            'kondisi'           => $request->kondisi,
        ]);
        $tpa->kecamatan_terlayani()->createMany(
            collect($request->kecamatan_terlayani ?? [])
                ->map(fn($id) => ['kecamatan_id' => $id])
                ->toArray()
        );
        return $this->sendResponse($tpa, 'Created!');
    }

    public function update(Request $request, Tpa $tpa)
    {
        $this->validate($request, [
            'nama'                  => 'required|max:200',
            'kecamatan_id'          => 'required|exists:kecamatans,id',
            'kelurahan_id'          => 'required|exists:kelurahans,id',
            'latitude'              => 'required|numeric|between:-90,90',
            'longitude'             => 'required|numeric|between:-180,180',
            'sumber'                => 'required|in:DAK,DAU',
            'tahun_konstruksi'      => 'required|date_format:Y',
            'tahun_beroperasi'      => 'required|date_format:Y',
            'rencana'               => 'required|integer|gte:0',
            'kecamatan_terlayani'   => 'nullable|array',
            'kecamatan_terlayani.*' => 'exists:kecamatans,id',
            'luas_sarana'           => 'required|numeric|gte:0',
            'luas_sel'              => 'required|numeric|gte:0',
            'pengelola'             => 'required|in:Dinas,UPT',
            'pengelola_desc'        => 'nullable|string|max:200',
            'kondisi'               => 'required|in:Baik,Tidak Baik',
        ]);
        $tpa->update([
            'nama'              => $request->nama,
            'kecamatan_id'      => $request->kecamatan_id,
            'kelurahan_id'      => $request->kelurahan_id,
            'lat'               => $request->latitude,
            'long'              => $request->longitude,
            'sumber'            => $request->sumber,
            'tahun_konstruksi'  => $request->tahun_konstruksi,
            'tahun_beroperasi'  => $request->tahun_beroperasi,
            'rencana'           => $request->rencana,
            'luas_sarana'       => $request->luas_sarana,
            'luas_sel'          => $request->luas_sel,
            'pengelola'         => $request->pengelola,
            'pengelola_desc'    => $request->pengelola_desc,
            'kondisi'           => $request->kondisi,
        ]);
        $tpa->kecamatan_terlayani()->delete();
        $tpa->kecamatan_terlayani()->createMany(
            collect($request->kecamatan_terlayani ?? [])
                ->map(fn($id) => ['kecamatan_id' => $id])
                ->toArray()
        );
        return $this->sendResponse($tpa, 'Updated!');
    }

    public function destroy(Tpa $tpa)
    {
        $tpa->delete();
        return $this->sendResponse($tpa, 'Deleted!');
    }

    public function destroy_batch(Request $request)
    {
        $this->validate($request, [
            'ids'       => 'required|array',
            'ids.*'     => 'integer|exists:tpas,id',
        ]);
        $deleted = Tpa::whereIn('id', $request->ids)->delete();

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
                $tpa = Tpa::create([
                    'tahun'     => $tahun,
                    'nama'      => $nama,
                    'lokasi'    => $lokasi,
                    'pagu'      => $pagu,
                    'jumlah'    => $jumlah,
                    'sumber'    => $sumber,
                    'lat'       => $lat,
                    'long'      => $long,
                ]);
                $results->add($tpa);
            }
            DB::commit();
            return $this->sendResponse($results, 'Success Import Data!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError('Gagal import: ' . $th->getMessage(), 500);
        }
    }
}
