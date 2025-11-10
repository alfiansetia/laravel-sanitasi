<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SanitasiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'tahun'     => 'required|date_format:Y',
            'nama'      => 'required|max:5000',
            'lokasi'    => 'required|max:200',
            'pagu'      => 'required|integer|gte:0',
            'jumlah'    => 'required|integer|gte:0',
            'sumber'    => 'required|in:DAK,DAU',
            'lat'       => 'nullable',
            'long'      => 'nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama'      => 'Nama Kegiatan',
            'tahun'     => 'Tahun',
            'lokasi'    => 'Lokasi',
            'pagu'      => 'Pagu Anggaran',
            'jumlah'    => 'Jumlah Anggaran',
            'sumber'    => 'Sumber Dana',
            'lat'       => 'Latitude',
            'long'      => 'Longitude',
        ];
    }

    public function mappedData(): array
    {
        return $this->only([
            'tahun',
            'nama',
            'lokasi',
            'pagu',
            'jumlah',
            'sumber',
            'lat',
            'long',
        ]);
    }
}
