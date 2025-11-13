<?php

namespace App\Http\Requests;

use App\Enums\OpsiBaik;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IpltRequest extends FormRequest
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
        return  [
            'nama'              => 'required|max:200',
            'kecamatan_id'      => 'required|exists:kecamatans,id',
            'kelurahan_id'      => 'required|exists:kelurahans,id',
            'latitude'          => 'required|numeric|between:-90,90',
            'longitude'         => 'required|numeric|between:-180,180',
            'tahun_konstruksi'  => 'required|date_format:Y',
            'terpasang'         => 'nullable|integer|gte:0',
            'terpakai'          => 'nullable|integer|gte:0',
            'tidak_terpakai'    => 'nullable|integer|gte:0',
            'truk'              => 'nullable|integer|gte:0',
            'kapasitas_truk'    => 'nullable|integer|gte:0',
            'kondisi_truk'      => ['required', Rule::in(config('enums.opsi_baik'))],
            'rit'               => 'nullable|integer|gte:0',
            'pemanfaat_kk'      => 'nullable|integer|gte:0',
            'pemanfaat_jiwa'    => 'nullable|integer|gte:0',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama'              => 'Nama IPLT',
            'kecamatan_id'      => 'Kecamatan',
            'kelurahan_id'      => 'Desa/Kelurahan',
            'latitude'          => 'Titik Koordinat Latitude',
            'longitude'         => 'Titik Koordinat Longitude',
            'tahun_konstruksi'  => 'Tahun Konstruksi',
            'terpasang'         => 'Kapasitas Terpasang',
            'terpakai'          => 'Kapasitas Terpakai',
            'tidak_terpakai'    => 'Kapasitas Tidak Terpakai',
            'truk'              => 'Truk Tinja (Unit)',
            'kapasitas_truk'    => 'Kapasitas Truk (M3)',
            'kondisi_truk'      => 'Kondisi Truk',
            'rit'               => 'Jumlah Ritasi (Rit/Hari)',
            'pemanfaat_kk'      => 'Jumlah Pemanfaat KK',
            'pemanfaat_jiwa'    => 'Jumlah Pemanfaat Jiwa',
        ];
    }

    public function mappedData(): array
    {
        $data = array_merge(
            $this->only([
                'nama',
                'kecamatan_id',
                'kelurahan_id',
                'tahun_konstruksi',
                'terpasang',
                'terpakai',
                'tidak_terpakai',
                'truk',
                'kapasitas_truk',
                'kondisi_truk',
                'rit',
                'pemanfaat_kk',
                'pemanfaat_jiwa',
            ]),
            [
                'lat'   => $this->latitude,
                'long'  => $this->longitude,
            ]
        );
        foreach (
            [
                'terpasang',
                'terpakai',
                'tidak_terpakai',
                'truk',
                'kapasitas_truk',
                'rit',
                'pemanfaat_kk',
                'pemanfaat_jiwa',
            ] as $field
        ) {
            $data[$field] = $data[$field] ?? 0;
        }
        return $data;
    }
}
