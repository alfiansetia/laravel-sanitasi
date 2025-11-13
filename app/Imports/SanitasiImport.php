<?php

namespace App\Imports;

use App\Models\Sanitasi;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SanitasiImport implements ToModel, WithValidation, SkipsEmptyRows, WithStartRow
{
    public function startRow(): int
    {
        return 5;
    }

    public function model(array $row)
    {
        // buat data Sanitasi
        $sanitasi = Sanitasi::create([
            'tahun'     => trim($row[1]),
            'nama'      => trim($row[2]),
            'lokasi'    => trim($row[3]),
            'pagu'      => $row[4] ?? 0,
            'jumlah'    => $row[5] ?? 0,
            'sumber'    => $row[6],
            'lat'       => trim($row[7]),
            'long'      => trim($row[8]),
        ]);
        return $sanitasi;
    }


    public function rules(): array
    {
        return [
            '1' => 'required|date_format:Y',
            '2' => 'required|max:5000',
            '3' => 'required|max:200',
            '4' => 'nullable|integer|gte:0',
            '5' => 'nullable|integer|gte:0',
            '6' => ['required', Rule::in(config('enums.sumber_dana'))],
            '7' => 'nullable|string',
            '8' => 'nullable|string',
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '1' => 'Nama Kegiatan',
            '2' => 'Tahun',
            '3' => 'Lokasi',
            '4' => 'Pagu Anggaran',
            '5' => 'Jumlah Anggaran',
            '6' => 'Sumber Dana',
            '7' => 'Latitude',
            '8' => 'Longitude',
        ];
    }
}
