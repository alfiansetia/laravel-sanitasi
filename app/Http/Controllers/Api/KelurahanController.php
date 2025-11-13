<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\KelurahanRequest;
use App\Imports\KelurahanImport;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
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
            Excel::import(new KelurahanImport, $request->file('file'));
            DB::commit();
            return $this->sendResponse(null, 'Data berhasil diimport!');
        } catch (ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $messages = [];
            foreach ($failures as $failure) {
                $messages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
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
