<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpaldRequest;
use App\Imports\SpaldImport;
use App\Models\Spald;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class SpaldController extends Controller
{
    public function index(Request $request)
    {
        $query = Spald::query()
            ->with([
                'kecamatan',
                'kelurahan',
            ])
            ->filter($request->only(Spald::$filterProp));
        return DataTables::eloquent($query)->toJson();
    }

    public function show(Spald $spald)
    {
        return $this->sendResponse($spald->load([
            'kecamatan',
            'kelurahan',
        ]));
    }

    public function store(SpaldRequest $request)
    {
        $spald = Spald::create($request->mappedData());
        return $this->sendResponse($spald, 'Created!');
    }

    public function update(SpaldRequest $request, Spald $spald)
    {
        $spald->update($request->mappedData());
        return $this->sendResponse($spald, 'Updated!');
    }

    public function destroy(Spald $spald)
    {
        $spald->delete();
        return $this->sendResponse($spald, 'Deleted!');
    }

    public function destroy_batch(Request $request)
    {
        $this->validate($request, [
            'ids'       => 'required|array',
            'ids.*'     => 'integer|exists:spalds,id',
        ]);
        $deleted = Spald::whereIn('id', $request->ids)->delete();

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
            Excel::import(new SpaldImport, $request->file('file'));
            DB::commit();
            return $this->sendResponse(null, 'Data berhasil diimport!');
        } catch (ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $messages = [];
            foreach ($failures as $failure) {
                $messages[] = 'Baris ' . ($failure->row() - 11) . ': ' . implode(', ', $failure->errors());
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
