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
                $messages[] = 'Baris ' . ($failure->row() - 6) . ': ' . implode(', ', $failure->errors());
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
