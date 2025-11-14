<?php

namespace App\Imports;

use App\Models\Kecamatan;
use App\Models\Spald;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SpaldImport implements ToModel, WithValidation, SkipsEmptyRows, WithStartRow
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
        return 12;
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

        $excelDate = $row[21];

        // Jika kosong, langsung null
        if ($excelDate === null || trim($excelDate) === '') {
            $tanggal_update = null;
        } else {
            if (is_numeric($excelDate)) {
                $tanggal_update = Date::excelToDateTimeObject($excelDate)->format('Y-m-d');
            } else {
                $tanggal_update = date('Y-m-d', strtotime($excelDate));
            }

            // Validasi jika ada isinya
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_update)) {
                throw new Exception("Format tanggal tidak valid pada baris Excel: {$row[21]}");
            }
        }

        // buat data Spald
        $spald = Spald::create([
            'nama'              => trim($row[1]),
            'kecamatan_id'      => $kecamatan->id,
            'kelurahan_id'      => $kelurahan->id,
            'alamat'            => trim($row[4]),
            'lat'               => trim($row[5]),
            'long'              => trim($row[6]),
            'skala'             => trim($row[7]),
            'tahun_konstruksi'  => trim($row[8]),
            'sumber'            => trim($row[9]),
            'status_keberfungsian' => trim($row[10]),
            'kondisi'           => trim($row[11]),
            'status_lahan'      => trim($row[12]),
            'kapasitas'         => $row[13] ?? 0,
            'jenis'             => trim($row[14]),
            'teknologi'         => trim($row[15]),
            'pemanfaat_jiwa'    => $row[16] ?? 0,
            'rumah_terlayani'   => $row[17] ?? 0,
            'unit_tangki'       => $row[18] ?? 0,
            'unit_bilik'        => $row[19] ?? 0,
            'status_penyedotan' => trim($row[20]),
            'tanggal_update'    => $tanggal_update,
        ]);
        return $spald;
    }


    public function rules(): array
    {
        return [
            '1'     => 'required|max:200',
            '2'     => 'required|string',
            '3'     => 'required|string',
            '4'     => 'required|max:200',
            '5'     => 'nullable|string',
            '6'     => 'nullable|string',
            '7'     => ['required', Rule::in(config('enums.skala_pelayanan'))],
            '8'     => 'required|date_format:Y',
            '9'     => ['required', Rule::in(config('enums.sumber_dana'))],
            '10'    => ['required', Rule::in(config('enums.opsi_befungsi'))],
            '11'    => ['required', Rule::in(config('enums.opsi_baik'))],
            '12'    => ['required', Rule::in(config('enums.status_lahan'))],
            '13'    => 'nullable|numeric|gte:0',
            '14'    => ['required', Rule::in(config('enums.jenis_pengelolaan'))],
            '15'    => ['required', Rule::in(config('enums.opsi_teknologi'))],
            '16'    => 'nullable|integer|gte:0',
            '17'    => 'nullable|integer|gte:0',
            '18'    => 'nullable|integer|gte:0',
            '19'    => 'nullable|integer|gte:0',
            '20'    => ['required', Rule::in(config('enums.opsi_ada'))],
            '21'    => 'nullable',
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '1'     => 'Nama Instalasi',
            '2'     => 'Kecamatan',
            '3'     => 'Kelurahan/Desa',
            '4'     => 'Alamat',
            '5'     => 'Titik Koordinat Latitude',
            '6'     => 'Titik Koordinat Longitude',
            '7'     => 'Skala Pelayanan',
            '8'     => 'Tahun Konstruksi',
            '9'     => 'Sumber Dana',
            '10'    => 'Status Keberfungsian',
            '11'    => 'Keterangan Kondisi',
            '12'    => 'Status Lahan',
            '13'    => 'Kapasitas Desain (m3/hari)',
            '14'    => 'Jenis Pengelolaan',
            '15'    => 'Opsi Teknologi',
            '16'    => 'Jumlah Pemanfaat Jiwa',
            '17'    => 'Jumlah Rumah Terlayani',
            '18'    => 'Jumlah Unit Tangki Septik',
            '19'    => 'Jumlah Unit Bilik',
            '20'    => 'Penyedotan Lumpur Tinja',
            '21'    => 'Tanggal Update',
        ];
    }
}
