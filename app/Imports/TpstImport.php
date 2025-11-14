<?php

namespace App\Imports;

use App\Models\Kecamatan;
use App\Models\Tpst;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TpstImport implements ToModel, WithValidation, SkipsEmptyRows, WithStartRow
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
        return 7;
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

        // buat data TPST
        $tpst = Tpst::create([
            'nama'              => trim($row[1]),
            'kecamatan_id'      => $kecamatan->id,
            'kelurahan_id'      => $kelurahan->id,
            'lat'               => trim($row[4]),
            'long'              => trim($row[5]),
            'sumber'            => trim($row[6]),
            'tahun_konstruksi'  => trim($row[7]),
            'tahun_beroperasi'  => trim($row[8]),
            'rencana'           => $row[9] ?? 0,
            'luas_sarana'       => $row[11] ?? 0,
            'luas_sel'          => $row[12] ?? 0,
            'pengelola'         => $row[13],
            'pengelola_desc'    => trim($row[14] ?? ''),
            'kondisi'           => $row[15],
        ]);


        $kecamatanNames = collect(explode(',', ($row[10] ?? '')))
            ->map(fn($n) => trim($n))
            ->filter()
            ->values();

        if ($kecamatanNames->isNotEmpty()) {
            // cari kecamatan yang valid di cache yang sudah diload
            $kecamatans = $this->kecamatans->whereIn('nama', $kecamatanNames);

            if ($kecamatans->count() !== $kecamatanNames->count()) {
                $invalid = $kecamatanNames->diff($kecamatans->pluck('nama'));
                throw new Exception("Kecamatan terlayani tidak valid. Tidak ditemukan: " . $invalid->join(', '));
            }

            // simpan relasi many-to-many (tanpa duplikat)
            $tpst->kecamatan_terlayani()->createMany(
                $kecamatans
                    ->map(fn($id) => ['kecamatan_id' => $id->id])
                    ->toArray()
            );
        }

        return $tpst;
    }


    public function rules(): array
    {
        return [
            '1'     => 'required|max:200',
            '2'     => 'required',
            '3'     => 'required',
            '4'     => 'nullable|string',
            '5'     => 'nullable|string',
            '6'     => ['required', Rule::in(config('enums.sumber_dana'))],
            '7'     => 'required|date_format:Y',
            '8'     => 'required|date_format:Y',
            '9'     => 'nullable|integer|gte:0',
            '10'    => 'nullable|string',
            '11'    => 'nullable|numeric|gte:0',
            '12'    => 'nullable|numeric|gte:0',
            '13'    => ['required', Rule::in(config('enums.pengelola'))],
            '14'    => 'nullable|string|max:200',
            '15'    => ['required', Rule::in(config('enums.opsi_baik'))],
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '1'     => 'Nama TPST',
            '2'     => 'Kecamatan',
            '3'     => 'Kelurahan/Desa',
            '4'     => 'Titik Koordinat Latitude',
            '5'     => 'Titik Koordinat Longitude',
            '6'     => 'Sumber Anggaran',
            '7'     => 'Tahun Konstruksi',
            '8'     => 'Tahun Beroperasi',
            '9'     => 'Rencana Umur Beroperasi (Tahun)',
            '10'    => 'Kecamatan Terlayani',
            '11'    => 'Luas Sarana',
            '12'    => 'Luas Sel',
            '13'    => 'Jenis Pengelola (DINAS/UPT)',
            '14'    => 'Deskripsi Pengelola',
            '15'    => 'Kondisi TPST',
        ];
    }
}
