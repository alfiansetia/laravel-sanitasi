<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IpltRequest;
use App\Imports\IpltImport;
use App\Models\Iplt;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class IpltController extends Controller
{
    public function index(Request $request)
    {
        $query = Iplt::query()
            ->with([
                'kecamatan',
                'kelurahan',
            ])
            ->filter($request->only(Iplt::$filterProp));
        return DataTables::eloquent($query)->toJson();
    }

    public function show(Iplt $iplt)
    {
        return $this->sendResponse($iplt->load([
            'kecamatan',
            'kelurahan',
        ]));
    }

    public function store(IpltRequest $request)
    {
        $iplt = Iplt::create($request->mappedData());
        return $this->sendResponse($iplt, 'Created!');
    }

    public function update(IpltRequest $request, Iplt $iplt)
    {
        $iplt->update($request->mappedData());
        return $this->sendResponse($iplt, 'Updated!');
    }

    public function destroy(Iplt $iplt)
    {
        $iplt->delete();
        return $this->sendResponse($iplt, 'Deleted!');
    }

    public function destroy_batch(Request $request)
    {
        $this->validate($request, [
            'ids'       => 'required|array',
            'ids.*'     => 'integer|exists:iplts,id',
        ]);
        $deleted = Iplt::whereIn('id', $request->ids)->delete();

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
            Excel::import(new IpltImport, $request->file('file'));
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
