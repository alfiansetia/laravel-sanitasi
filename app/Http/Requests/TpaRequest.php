<?php

namespace App\Http\Requests;

use App\Enums\OpsiBaik;
use App\Enums\Pengelola;
use App\Enums\SumberDana;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TpaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama'                  => 'required|max:200',
            'kecamatan_id'          => 'required|exists:kecamatans,id',
            'kelurahan_id'          => 'required|exists:kelurahans,id',
            'latitude'              => 'required|numeric|between:-90,90',
            'longitude'             => 'required|numeric|between:-180,180',
            'sumber'                => ['required', Rule::in(config('enums.sumber_dana'))],
            'tahun_konstruksi'      => 'required|date_format:Y',
            'tahun_beroperasi'      => 'required|date_format:Y',
            'rencana'               => 'nullable|integer|gte:0',
            'kecamatan_terlayani'   => 'nullable|array',
            'kecamatan_terlayani.*' => 'exists:kecamatans,id',
            'luas_sarana'           => 'nullable|numeric|gte:0',
            'luas_sel'              => 'nullable|numeric|gte:0',
            'pengelola'             => ['required', Rule::in(config('enums.pengelola'))],
            'pengelola_desc'        => 'nullable|string|max:200',
            'kondisi'               => ['required', Rule::in(config('enums.opsi_baik'))],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama'                  => 'Nama TPA',
            'kecamatan_id'          => 'Lokasi (Kecamatan)',
            'kelurahan_id'          => 'Lokasi (Kelurahan/Desa)',
            'latitude'              => 'Titik Koordinat Latitude',
            'longitude'             => 'Titik Koordinat Longitude',
            'sumber'                => 'Sumber Anggaran',
            'tahun_konstruksi'      => 'Tahun Konstruksi',
            'tahun_beroperasi'      => 'Tahun Beroperasi',
            'rencana'               => 'Rencana Umur Beroperasi (Tahun)',
            'kecamatan_terlayani'   => 'Kecamatan Terlayani',
            'kecamatan_terlayani.*' => 'Kecamatan Terlayani',
            'luas_sarana'           => 'Luas Sarana',
            'luas_sel'              => 'Luas Sel',
            'pengelola'             => 'Jenis Pengelola (Dinas/UPT)',
            'pengelola_desc'        => 'Deskripsi Pengelola',
            'kondisi'               => 'Kondisi TPA',
        ];
    }

    public function mappedData(): array
    {
        $data = array_merge(
            $this->only([
                'nama',
                'kecamatan_id',
                'kelurahan_id',
                'sumber',
                'tahun_konstruksi',
                'tahun_beroperasi',
                'rencana',
                'luas_sarana',
                'luas_sel',
                'pengelola',
                'pengelola_desc',
                'kondisi',
            ]),
            [
                'lat'   => $this->latitude,
                'long'  => $this->longitude,
            ]
        );

        foreach (
            [
                'rencana',
                'luas_sarana',
                'luas_sel'
            ] as $field
        ) {
            $data[$field] = $data[$field] ?? 0;
        }

        return $data;
    }
}
