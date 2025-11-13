<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\KecamatanRequest;
use App\Imports\KecamatanImport;
use App\Models\Kecamatan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class KecamatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kecamatan::query()
            ->filter($request->only(Kecamatan::$filterProp));
        return DataTables::eloquent($query)->toJson();
    }

    public function show(Kecamatan $kecamatan)
    {
        return $this->sendResponse($kecamatan->load('kelurahans'));
    }

    public function store(KecamatanRequest $request)
    {
        $kecamatan = Kecamatan::create($request->mappedData());
        return $this->sendResponse($kecamatan, 'Created!');
    }

    public function update(KecamatanRequest $request, Kecamatan $kecamatan)
    {
        $kecamatan->update($request->mappedData());
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
            Excel::import(new KecamatanImport, $request->file('file'));
            DB::commit();
            return $this->response('Data berhasil diimport!');
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
            return $this->response('Gagal import: ' . $th->getMessage(), [], 500);
        }
    }
}
