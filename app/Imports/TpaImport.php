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
    protected $kelurahans;

    public function __construct()
    {
        $this->kecamatans = Kecamatan::query()->select('id', 'nama')->get();
        $this->kelurahans = Kelurahan::query()->select('id', 'nama', 'kecamatan_id')->get();
    }

    public function startRow(): int
    {
        return 3;
    }

    public function model(array $row)
    {
        return new Tpa([
            'nama'              => $row[1],
            'kecamatan_id'      => $row[2],
            'kelurahan_id'      => $row[3],
            'lat'               => $row[5],
            'long'              => $row[6],
            'sumber'            => $row[4],
            'tahun_konstruksi'  => $row[7],
            'tahun_beroperasi'  => $row[8],
            'rencana'           => $row[9],
            'luas_sarana'       => $row[11],
            'luas_sel'          => $row[12],
            'pengelola'         => $row[13],
            'pengelola_desc'    => $row[14],
            'kondisi'           => $row[15],
        ]);
    }

    public function rules(): array
    {
        return [
            '1'     => 'required|max:200',
            '2'     => 'required|exists:kecamatans,id',
            '3'     => 'required|exists:kelurahans,id',
            '4'     => 'required|numeric|between:-90,90',
            '5'     => 'required|numeric|between:-180,180',
            '6'     => ['required', Rule::in(config('enums.sumber_dana'))],
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
