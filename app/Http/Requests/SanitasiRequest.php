<?php

namespace App\Http\Requests;

use App\Enums\SumberDana;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SanitasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'nama'          => 'required|max:5000',
            'tahun'         => 'required|date_format:Y',
            'kecamatan_id'  => 'required|exists:kecamatans,id',
            'kelurahan_id'  => 'required|exists:kelurahans,id',
            'pagu'          => 'nullable|integer|gte:0',
            'jumlah'        => 'nullable|integer|gte:0',
            'sumber'        => ['required', Rule::in(config('enums.sumber_dana'))],
            'lat'           => 'required|numeric|between:-90,90',
            'long'          => 'required|numeric|between:-180,180',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama'          => 'Nama Kegiatan',
            'tahun'         => 'Tahun',
            'kecamatan_id'  => 'Lokasi (Kecamatan)',
            'kelurahan_id'  => 'Lokasi (Kelurahan/Desa)',
            'pagu'          => 'Pagu Anggaran',
            'jumlah'        => 'Jumlah Anggaran',
            'sumber'        => 'Sumber Dana',
            'latitude'      => 'Latitude',
            'longitude'     => 'Longitude',
        ];
    }

    public function mappedData(): array
    {
        $data = array_merge(
            $this->only([
                'nama',
                'tahun',
                'kecamatan_id',
                'kelurahan_id',
                'pagu',
                'jumlah',
                'sumber',
            ]),
            [
                'lat'   => $this->latitude,
                'long'  => $this->longitude,
            ]
        );

        foreach (
            [
                'pagu',
                'jumlah',
            ] as $field
        ) {
            $data[$field] = $data[$field] ?? 0;
        }

        return $data;
    }
}
