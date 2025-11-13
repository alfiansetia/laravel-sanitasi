<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TpstRequest;
use App\Imports\TpstImport;
use App\Models\Tpst;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class TpstController extends Controller
{
    public function index(Request $request)
    {
        $query = Tpst::query()
            ->with([
                'kecamatan',
                'kelurahan',
                'kecamatan_terlayani.kecamatan'
            ])
            ->filter($request->only(Tpst::$filterProp));
        return DataTables::eloquent($query)->toJson();
    }

    public function show(Tpst $tpst)
    {
        return $this->sendResponse($tpst->load([
            'kecamatan',
            'kelurahan',
            'kecamatan_terlayani.kecamatan'
        ]));
    }

    public function store(TpstRequest $request)
    {
        $tpst = Tpst::create($request->mappedData());
        $tpst->kecamatan_terlayani()->createMany(
            collect($request->kecamatan_terlayani ?? [])
                ->map(fn($id) => ['kecamatan_id' => $id])
                ->toArray()
        );
        return $this->sendResponse($tpst, 'Created!');
    }

    public function update(TpstRequest $request, Tpst $tpst)
    {
        $tpst->update($request->mappedData());
        $tpst->kecamatan_terlayani()->delete();
        $tpst->kecamatan_terlayani()->createMany(
            collect($request->kecamatan_terlayani ?? [])
                ->map(fn($id) => ['kecamatan_id' => $id])
                ->toArray()
        );
        return $this->sendResponse($tpst, 'Updated!');
    }

    public function destroy(Tpst $tpst)
    {
        $tpst->delete();
        return $this->sendResponse($tpst, 'Deleted!');
    }

    public function destroy_batch(Request $request)
    {
        $this->validate($request, [
            'ids'       => 'required|array',
            'ids.*'     => 'integer|exists:tpsts,id',
        ]);
        $deleted = Tpst::whereIn('id', $request->ids)->delete();

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
            Excel::import(new TpstImport, $request->file('file'));
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
