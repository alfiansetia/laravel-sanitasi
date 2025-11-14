<?php

namespace App\Imports;

use App\Models\Iplt;
use App\Models\Kecamatan;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class IpltImport implements ToModel, WithValidation, SkipsEmptyRows, WithStartRow
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
        $kecamatanExcel = strtolower(trim($row[2])); // nama kecamatan
        $kelurahanExcel = strtolower(trim($row[3])); // nama kelurahan

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

        // buat data Iplt
        $iplt = Iplt::create([
            'nama'              => trim($row[1]),
            'kecamatan_id'      => $kecamatan->id,
            'kelurahan_id'      => $kelurahan->id,
            'lat'               => trim($row[4]),
            'long'              => trim($row[5]),
            'tahun_konstruksi'  => trim($row[6]),
            'terpasang'         => $row[7] ?? 0,
            'terpakai'          => $row[8] ?? 0,
            'tidak_terpakai'    => $row[9] ?? 0,
            'truk'              => $row[10] ?? 0,
            'kapasitas_truk'    => $row[11] ?? 0,
            'kondisi_truk'      => trim($row[12]),
            'rit'               => $row[13] ?? 0,
            'pemanfaat_kk'      => $row[14] ?? 0,
            'pemanfaat_jiwa'    => $row[15] ?? 0,
        ]);
        return $iplt;
    }


    public function rules(): array
    {
        return [
            '1'     => 'required|string|max:200',
            '2'     => 'required|string',
            '3'     => 'required|string',
            '4'     => 'nullable|string',
            '5'     => 'nullable|string',
            '6'     => 'required|date_format:Y',
            '7'     => 'nullable|integer|gte:0',
            '8'     => 'nullable|integer|gte:0',
            '9'     => 'nullable|integer|gte:0',
            '10'    => 'nullable|integer|gte:0',
            '11'    => 'nullable|integer|gte:0',
            '12'    => ['required', Rule::in(config('enums.opsi_baik'))],
            '13'    => 'nullable|integer|gte:0',
            '14'    => 'nullable|integer|gte:0',
            '15'    => 'nullable|integer|gte:0',
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '1'     => 'Nama IPLT',
            '2'     => 'Kecamatan',
            '3'     => 'Kelurahan/Desa',
            '4'     => 'Titik Koordinat Latitude',
            '5'     => 'Titik Koordinat Longitude',
            '6'     => 'Tahun Konstruksi',
            '7'     => 'Kapasitas Terpasang',
            '8'     => 'Kapasitas Terpakai',
            '9'     => 'Kapasitas Tidak Terpakai',
            '10'    => 'Truk Tinja (Unit)',
            '11'    => 'Kapasitas Truk (M3)',
            '12'    => 'Kondisi Truk',
            '13'    => 'Jumlah Ritasi (Rit/Hari)',
            '14'    => 'Jumlah Pemanfaat KK',
            '15'    => 'Jumlah Pemanfaat Jiwa',
        ];
    }
}
