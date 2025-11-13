<?php

namespace App\Imports;

use App\Models\Kecamatan;
use App\Models\Tps3r;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class Tps3rImport implements ToModel, WithValidation, SkipsEmptyRows, WithStartRow
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
        $kecamatanExcel = strtolower(trim($row[1])); // nama kecamatan
        $kelurahanExcel = strtolower(trim($row[2])); // nama kelurahan

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
            throw new Exception("Kelurahan/Desa '{$row[1]}' dengan Kecamatan '{$row[2]}' tidak ditemukan (baris Excel)");
        }

        // ambil kelurahan yang cocok (untuk dapetin id-nya)
        $kelurahan = $kecamatan->kelurahans->first(function ($kel) use ($kelurahanExcel) {
            return strtolower(trim($kel->nama)) === $kelurahanExcel;
        });

        if (! $kelurahan) {
            throw new Exception("Kelurahan '{$row[1]}' tidak cocok dalam Kecamatan '{$row[2]}' (baris Excel)");
        }

        // buat data TPS3R
        $tps3r = Tps3r::create([
            'kecamatan_id'      => $kecamatan->id,
            'kelurahan_id'      => $kelurahan->id,
            'luas'              => $row[3] ?? 0,
            'tahun_konstruksi'  => trim($row[4]),
            'tahun_beroperasi'  => trim($row[5]),
            'jumlah_timbunan'   => $row[6] ?? 0,
            'jumlah_penduduk'   => $row[7] ?? 0,
            'jumlah_kk'         => $row[8] ?? 0,
            'gerobak'           => $row[9] ?? 0,
            'motor'             => $row[10] ?? 0,
            'status'            => $row[11],
        ]);
        return $tps3r;
    }


    public function rules(): array
    {
        return [
            '1'     => 'required',
            '2'     => 'required',
            '3'     => 'nullable|integer|gte:0',
            '4'     => 'required|date_format:Y',
            '5'     => 'required|date_format:Y',
            '6'     => 'nullable|numeric|gte:0',
            '7'     => 'nullable|integer|gte:0',
            '8'     => 'nullable|integer|gte:0',
            '9'     => 'nullable|integer|gte:0',
            '10'    => 'nullable|integer|gte:0',
            '11'    => ['required', Rule::in(config('enums.opsi_befungsi'))],
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '1'     => 'Kecamatan',
            '2'     => 'Kelurahan/Desa',
            '3'     => 'Luas (M2)',
            '3'     => 'Tahun Konstruksi',
            '5'     => 'Tahun Beroperasi',
            '6'     => 'Jumlah Timbunan Sampah (Ton/Hari)',
            '7'     => 'Jumlah Penduduk',
            '8'     => 'Jumlah KK Terlayani',
            '9'     => 'Gerobak',
            '10'    => 'Motor Roda Tiga',
            '11'    => 'Keberfungsian',
        ];
    }
}
