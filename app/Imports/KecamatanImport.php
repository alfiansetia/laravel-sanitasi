<?php

namespace App\Imports;

use App\Models\Kecamatan;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class KecamatanImport implements ToModel, WithValidation, SkipsEmptyRows, WithStartRow
{
    public function startRow(): int
    {
        return 3;
    }

    public function model(array $row)
    {
        return new Kecamatan([
            'nama'  => $row[1],
        ]);
    }

    public function rules(): array
    {
        return [
            '1' => ['required', 'string', 'max:100', Rule::unique('kecamatans', 'nama')],
        ];
    }

    public function customValidationAttributes()
    {
        return ['1' => 'Nama Kecamatan'];
    }
}
