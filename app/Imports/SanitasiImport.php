<?php

namespace App\Imports;

use App\Models\Kecamatan;
use App\Models\Sanitasi;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SanitasiImport implements ToModel, WithValidation, SkipsEmptyRows, WithStartRow
{
    protected $kecamatans;

    public function __construct()
    {
        $this->kecamatans = Kecamatan::query()
            ->with('kelurahans')
            ->get();
    }

    public function startRow(): int
    {
        return 5;
    }

    public function model(array $row)
    {
        $kecamatanExcel = strtolower(trim($row[3])); // nama kecamatan
        $kelurahanExcel = strtolower(trim($row[4])); // nama kelurahan

        // cari kecamatan yang cocok
        $kecamatan = $this->kecamatans->first(function ($item) use ($kecamatanExcel, $kelurahanExcel) {
            // cek nama kecamatan
            if (strtolower(trim($item->nama)) !== $kecamatanExcel) {
                return false;
            }

            // cek apakah ada kelurahan yang cocok di kecamatan ini
            return $item->kelurahans->contains(function ($kel) use ($kelurahanExcel) {
                return strtolower(trim($kel->nama)) === $kelurahanExcel;
            });
        });

        if (! $kecamatan) {
            throw new Exception("Kelurahan/Desa '{$row[2]}' dengan Kecamatan '{$row[3]}' tidak ditemukan (baris Excel)");
        }

        // ambil kelurahan yang cocok (untuk dapetin id-nya)
        $kelurahan = $kecamatan->kelurahans->first(function ($kel) use ($kelurahanExcel) {
            return strtolower(trim($kel->nama)) === $kelurahanExcel;
        });

        if (! $kelurahan) {
            throw new Exception("Kelurahan '{$row[2]}' tidak cocok dalam Kecamatan '{$row[3]}' (baris Excel)");
        }
        // buat data Sanitasi
        $sanitasi = Sanitasi::create([
            'nama'          => trim($row[1]),
            'tahun'         => trim($row[2]),
            'kecamatan_id'  => $kecamatan->id,
            'kelurahan_id'  => $kelurahan->id,
            'pagu'          => $row[5] ?? 0,
            'jumlah'        => $row[6] ?? 0,
            'sumber'        => $row[7],
            'lat'           => trim($row[8]),
            'long'          => trim($row[9]),
        ]);
        return $sanitasi;
    }


    public function rules(): array
    {
        return [
            '1' => 'required|max:5000',
            '2' => 'required|date_format:Y',
            '3' => 'required',
            '4' => 'required',
            '5' => 'nullable|integer|gte:0',
            '6' => 'nullable|integer|gte:0',
            '7' => ['required', Rule::in(config('enums.sumber_dana'))],
            '8' => 'nullable|string',
            '9' => 'nullable|string',
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '1' => 'Nama Kegiatan',
            '2' => 'Tahun',
            '3' => 'Kecamatan',
            '4' => 'Kelurahan/Desa',
            '5' => 'Pagu Anggaran',
            '6' => 'Jumlah Anggaran',
            '7' => 'Sumber Dana',
            '8' => 'Latitude',
            '9' => 'Longitude',
        ];
    }
}
