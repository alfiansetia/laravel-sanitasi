<?php

namespace App\Imports;

use App\Models\Spaldt;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SpaldtImport implements ToModel, WithHeadingRow, WithValidation
{

    public function model(array $row)
    {
        return new Spaldt([
            'name'          => $row['name'],
            'unit'          => $row['unit'],
            'component'     => $row['component'],
            'number'        => $row['number'],
            'satuan_map'    => $row['satuan_map'],
            'price_map'     => $row['price_map'] ?? 0,
            'satuan_vendor' => $row['satuan_vendor'],
            'price_vendor'  => $row['price_vendor'] ?? 0,
            'vendor'        => $row['vendor'],
            'brand'         => $row['brand'],
            'remark'        => $row['remark'],
        ]);
    }

    public function rules(): array
    {
        return [
            'number'        => ['required'],
            'price_map'     => ['nullable', 'integer'],
            'price_vendor'  => ['nullable', 'integer'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'number.required'       => 'Number wajib diisi.',
            'price_map.integer'     => 'price_map harus berupa angka.',
            'price_vendor.integer'  => 'price_vendor harus berupa angka.',
        ];
    }
}
