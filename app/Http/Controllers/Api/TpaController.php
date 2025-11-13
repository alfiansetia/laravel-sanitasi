<?php

namespace App\Http\Controllers\Api;

use App\Enums\OpsiBaik;
use App\Enums\Pengelola;
use App\Enums\SumberDana;
use App\Http\Controllers\Controller;
use App\Http\Requests\TpaRequest;
use App\Imports\TpaImport;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Tpa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use ValueError;
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
            ->filter($request->only(Tpa::$filterProp));
        return DataTables::eloquent($query)->toJson();
    }

    public function show(Tpa $tpa)
    {
        return $this->sendResponse($tpa->load([
            'kecamatan',
            'kelurahan',
            'kecamatan_terlayani.kecamatan'
        ]));
    }

    public function store(TpaRequest $request)
    {
        $tpa = Tpa::create($request->mappedData());
        $tpa->kecamatan_terlayani()->createMany(
            collect($request->kecamatan_terlayani ?? [])
                ->map(fn($id) => ['kecamatan_id' => $id])
                ->toArray()
        );
        return $this->sendResponse($tpa, 'Created!');
    }

    public function update(TpaRequest $request, Tpa $tpa)
    {
        $tpa->update($request->mappedData());
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

    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
    //     ]);
    //     DB::beginTransaction();
    //     try {
    //         $skip = 6;
    //         $r = $skip - 1;
    //         $file = $request->file('file');
    //         $data = Excel::toCollection([], $file)[0]->skip($skip);
    //         $results = collect();
    //         foreach ($data as $index => $item) {
    //             if ($item->filter()->isEmpty()) {
    //                 continue;
    //             }
    //             $nama = $item[1] ?? null;
    //             $kec = $item[2] ?? null;
    //             $desa = $item[3] ?? null;
    //             $lat = $item[4] ?? null;
    //             $long = $item[5] ?? null;
    //             $sumber = $item[6] ?? null;
    //             $th_kons = $item[7] ?? null;
    //             $th_opr = $item[8] ?? null;
    //             $rencana_um = $item[9] ?? null;
    //             $kec_ter = $item[10] ?? null;
    //             $luas_sar = $item[11] ?? null;
    //             $luas_sel = $item[12] ?? null;
    //             $jenis = $item[13] ?? null;
    //             $jenis_desc = $item[14] ?? null;
    //             $kondisi = $item[15] ?? null;

    //             if (
    //                 empty($nama) ||
    //                 empty($kec) ||
    //                 empty($desa) ||
    //                 empty($sumber) ||
    //                 empty($th_kons) ||
    //                 empty($th_opr) ||
    //                 empty($kec_ter) ||
    //                 empty($jenis) ||
    //                 empty($kondisi)
    //             ) {
    //                 throw new Exception("Data tidak lengkap di baris " . ($index - $r));
    //             }
    //             if (!empty($lat) && !empty($long)) {
    //                 if (!valid_latlong($lat, $long)) {
    //                     throw new Exception("Latitude Longitude tidak Valid di baris " . ($index - $r));
    //                 };
    //             }
    //             $sumberEnum  = SumberDana::parse($sumber);
    //             $jenisEnum   = Pengelola::parse($jenis);
    //             $kondisiEnum = OpsiBaik::parse($kondisi);
    //             if (! $sumberEnum) {
    //                 throw new Exception("Data sumber tidak valid di baris " . ($index - $r) . " (nilai: '{$sumber}')");
    //             }
    //             if (! $jenisEnum) {
    //                 throw new Exception("Data Jenis Pengelolaan tidak valid di baris " . ($index - $r) . " (nilai: '{$jenis}')");
    //             }
    //             if (! $kondisiEnum) {
    //                 throw new Exception("Data Kondisi tidak valid di baris " . ($index - $r) . " (nilai: '{$kondisi}')");
    //             }
    //             $kecamatan = Kecamatan::query()
    //                 ->whereRaw('LOWER(nama) = ?', [strtolower($kec)])
    //                 ->first();

    //             if (!$kecamatan) {
    //                 throw new Exception("Data Kecamatan tidak valid di baris " . ($index - $r) . " (nilai: '{$kec}')");
    //             }

    //             $kelurahan = Kelurahan::query()
    //                 ->where('kecamatan_id', $kecamatan->id)
    //                 ->whereRaw('LOWER(nama) = ?', [strtolower($desa)])
    //                 ->first();

    //             if (!$kelurahan) {
    //                 throw new Exception("Data Desa tidak valid di baris " . ($index - $r) . " (nilai: '{$desa}')");
    //             }
    //             $tpa = Tpa::create([
    //                 'nama'                  => $nama,
    //                 'kecamatan_id'          => $kecamatan->id,
    //                 'kelurahan_id'          => $kelurahan->id,
    //                 'lat'                   => $lat,
    //                 'long'                  => $long,
    //                 'sumber'                => $sumberEnum,
    //                 'tahun_konstruksi'      => $th_kons,
    //                 'tahun_beroperasi'      => $th_opr,
    //                 'rencana'               => $rencana_um,
    //                 'luas_sarana'           => $luas_sar,
    //                 'luas_sel'              => $luas_sel,
    //                 'pengelola'             => $jenisEnum,
    //                 'pengelola_desc'        => $jenis_desc,
    //                 'kondisi'               => $kondisiEnum,
    //             ]);
    //             $kecamatanNames = collect(explode(',', $kec_ter ?? ''))
    //                 ->map(fn($n) => trim($n))
    //                 ->filter()
    //                 ->values();

    //             if ($kecamatanNames->isNotEmpty()) {
    //                 // Ambil data kecamatan dari database yang namanya ada di daftar Excel
    //                 $kecamatans = Kecamatan::whereIn('nama', $kecamatanNames)->get();

    //                 // Cek apakah jumlah hasil query sama dengan jumlah nama input
    //                 if ($kecamatans->count() !== $kecamatanNames->count()) {
    //                     // Ada nama yang tidak cocok
    //                     $invalid = $kecamatanNames->diff($kecamatans->pluck('nama'));
    //                     throw new Exception(
    //                         "Kecamatan terlayani tidak valid di baris " . ($index - $r) .
    //                             ". Nama tidak ditemukan: " . $invalid->join(', ')
    //                     );
    //                 }

    //                 // Jika semua valid, ambil ID untuk relasi
    //                 $kecamatanIds = $kecamatans->pluck('id')->toArray();

    //                 // Simpan relasi many-to-many
    //                 $tpa->kecamatan_terlayani()->createMany(
    //                     collect($kecamatanIds ?? [])
    //                         ->map(fn($id) => ['kecamatan_id' => $id])
    //                         ->toArray()
    //                 );
    //             }
    //             $results->add($tpa);
    //         }
    //         DB::commit();
    //         return $this->sendResponse($results, 'Success Import Data!');
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         return $this->sendError('Gagal import: ' . $th->getMessage(), 500);
    //     }
    // }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);
        DB::beginTransaction();
        try {
            Excel::import(new TpaImport, $request->file('file'));
            DB::commit();
            return $this->sendResponse(null, 'Data berhasil diimport!');
        } catch (ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $messages = [];
            foreach ($failures as $failure) {
                $messages[] = 'Baris ' . ($failure->row() - 2) . ': ' . implode(', ', $failure->errors());
            }

            return response()->json([
                'message' => 'Gagal import!, ' . implode(', ', $messages),
                'errors' => $messages,
            ], 422);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError('Gagal import: ' . $th->getMessage(), 500);
        }
        return $this->sendError('Gagal import: ' . $th->getMessage(), 500);
    }
}
