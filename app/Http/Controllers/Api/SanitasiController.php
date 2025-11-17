<?php

namespace App\Http\Controllers\Api;

use App\Enums\SumberDana;
use App\Http\Controllers\Controller;
use App\Http\Requests\SanitasiRequest;
use App\Imports\SanitasiImport;
use App\Models\Sanitasi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class SanitasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Sanitasi::query()
            ->with([
                'kecamatan',
                'kelurahan',
            ])
            ->filter($request->only(Sanitasi::$filterProp));
        return DataTables::eloquent($query)->toJson();
    }

    public function show(Sanitasi $sanitasi)
    {
        return $this->sendResponse($sanitasi->load([
            'kecamatan',
            'kelurahan',
        ]));
    }

    public function store(SanitasiRequest $request)
    {
        $sanitasi = Sanitasi::create($request->mappedData());
        return $this->sendResponse($sanitasi, 'Created!');
    }

    public function update(SanitasiRequest $request, Sanitasi $sanitasi)
    {
        $sanitasi->update($request->mappedData());
        return $this->sendResponse($sanitasi, 'Updated!');
    }

    public function destroy(Sanitasi $sanitasi)
    {
        $sanitasi->delete();
        return $this->sendResponse($sanitasi, 'Deleted!');
    }

    public function destroy_batch(Request $request)
    {
        $this->validate($request, [
            'ids'       => 'required|array',
            'ids.*'     => 'integer|exists:sanitasis,id',
        ]);
        $deleted = Sanitasi::whereIn('id', $request->ids)->delete();

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
            Excel::import(new SanitasiImport, $request->file('file'));
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
