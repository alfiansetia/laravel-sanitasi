<?php

namespace App\Imports;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class KelurahanImport implements ToModel, WithValidation, SkipsEmptyRows, WithStartRow
{

    protected $kecamatans;

    public function __construct()
    {
        $this->kecamatans = Kecamatan::select('id', 'nama')->get();
    }

    public function startRow(): int
    {
        return 3;
    }

    public function model(array $row)
    {
        $namaKecamatanExcel = strtolower(trim($row[3]));
        $kecamatan = $this->kecamatans->first(function ($item) use ($namaKecamatanExcel) {
            return strtolower(trim($item->nama)) === $namaKecamatanExcel;
        });
        if (! $kecamatan) {
            throw new Exception("Kecamatan '{$row[3]}' tidak ditemukan (baris Excel)");
        }
        $kode = trim($row[1]);
        $nama = trim($row[2]);

        // Hindari duplikat berdasarkan kombinasi 'kode' dan 'kecamatan_id'
        $kelurahan = Kelurahan::query()
            ->where('kode', $kode)
            ->where('kecamatan_id', $kecamatan->id)
            ->first();

        if (! $kelurahan) {
            // Kalau belum ada, baru buat
            $kelurahan = Kelurahan::create([
                'kode'          => $kode,
                'nama'          => $nama,
                'kecamatan_id'  => $kecamatan->id,
            ]);
        }

        return $kelurahan;
    }

    public function rules(): array
    {
        return [
            '1' => ['required', 'max_digits:100', Rule::unique('kelurahans', 'kode')],
            '2' => ['required', 'string', 'max:100'],
            '3' => ['required', 'string', 'max:100',],
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '1' => 'Kode Kelurahan',
            '2' => 'Nama Kelurahan',
            '2' => 'Nama Kecamatan',
        ];
    }
}
