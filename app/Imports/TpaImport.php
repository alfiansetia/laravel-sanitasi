<?php

namespace App\Imports;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Tpa;
use Exception;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TpaImport implements ToModel, WithValidation, SkipsEmptyRows, WithStartRow
{
    protected $kecamatans;

    public function __construct()
    {
        $this->kecamatans = Kecamatan::query()
            ->with('kelurahans')
            ->select('id', 'nama')
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


        // buat data TPA
        $tpa = Tpa::create([
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

        // ambil daftar kecamatan terlayani dari kolom 10
        // $kecamatanNames = collect(explode(',', ($row[10] ?? '')))
        //     ->map(fn($n) => trim($n))
        //     ->filter()
        //     ->values();

        // if ($kecamatanNames->isNotEmpty()) {
        //     // cari kecamatan yang valid di cache yang sudah diload
        //     $kecamatans = $this->kecamatans->filter(fn($k) => in_array($k->nama, $kecamatanNames));

        //     if ($kecamatans->count() !== $kecamatanNames->count()) {
        //         $invalid = $kecamatanNames->diff($kecamatans->pluck('nama'));
        //         throw new Exception("Kecamatan terlayani tidak valid. Tidak ditemukan: " . $invalid->join(', '));
        //     }

        //     // simpan relasi many-to-many (tanpa duplikat)
        //     $tpa->kecamatan_terlayani()->syncWithoutDetaching($kecamatans->pluck('id')->toArray());
        // }
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
            // $tpa->kecamatan_terlayani()->syncWithoutDetaching($kecamatans->pluck('id')->toArray());
            $tpa->kecamatan_terlayani()->createMany(
                $kecamatans
                    ->map(fn($id) => ['kecamatan_id' => $id->id])
                    ->toArray()
            );
        }

        return $tpa;
    }


    public function rules(): array
    {
        return [
            '1'     => 'required|max:200',
            '2'     => 'required',
            '3'     => 'required',
            '4'     => ['required', Rule::in(config('enums.sumber_dana'))],
            '5'     => 'required|numeric|between:-90,90',
            '6'     => 'required|numeric|between:-180,180',
            '7'     => 'required|date_format:Y',
            '8'     => 'required|date_format:Y',
            '9'     => 'nullable|integer|gte:0',
            '10'    => 'nullable',
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
            '1'     => 'Nama TPA',
            '2'     => 'Lokasi (Kecamatan)',
            '3'     => 'Lokasi (Kelurahan/Desa)',
            '4'     => 'Titik Koordinat Latitude',
            '5'     => 'Titik Koordinat Longitude',
            '6'     => 'Sumber Anggaran',
            '7'     => 'Tahun Konstruksi',
            '8'     => 'Tahun Beroperasi',
            '9'     => 'Rencana Umur Beroperasi (Tahun)',
            '10'    => 'Kecamatan Terlayani',
            '11'    => 'Luas Sarana',
            '12'    => 'Luas Sel',
            '13'    => 'Jenis Pengelola (Dinas/UPT)',
            '14'    => 'Deskripsi Pengelola',
            '15'    => 'Kondisi TPA',
        ];
    }
}
