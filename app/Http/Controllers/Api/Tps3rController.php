<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tps3rRequest;
use App\Imports\Tps3rImport;
use App\Models\Tps3r;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
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
            Excel::import(new Tps3rImport, $request->file('file'));
            DB::commit();
            return $this->sendResponse(null, 'Data berhasil diimport!');
        } catch (ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $messages = [];
            foreach ($failures as $failure) {
                $messages[] = 'Baris ' . ($failure->row() - 4) . ': ' . implode(', ', $failure->errors());
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
