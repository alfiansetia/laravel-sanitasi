<?php

namespace App\Http\Requests;

use App\Enums\JenisPengelolaan;
use App\Enums\OpsiAda;
use App\Enums\OpsiBaik;
use App\Enums\OpsiBerfungsi;
use App\Enums\OpsiTeknologi;
use App\Enums\SkalaPelayanan;
use App\Enums\StatusLahan;
use App\Enums\SumberDana;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SpaldRequest extends FormRequest
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
            'alamat'                => 'required|max:200',
            'kecamatan_id'          => 'required|exists:kecamatans,id',
            'kelurahan_id'          => 'required|exists:kelurahans,id',
            'latitude'              => 'required|numeric|between:-90,90',
            'longitude'             => 'required|numeric|between:-180,180',
            'skala'                 => ['required', Rule::in(config('enums.skala_pelayanan'))],
            'tahun_konstruksi'      => 'required|date_format:Y',
            'sumber'                => ['required', Rule::in(config('enums.sumber_dana'))],
            'status_keberfungsian'  => ['required', Rule::in(config('enums.opsi_befungsi'))],
            'kondisi'               => ['required', Rule::in(config('enums.opsi_baik'))],
            'status_lahan'          => ['required', Rule::in(config('enums.status_lahan'))],
            'kapasitas'             => 'nullable|numeric|gte:0',
            'jenis'                 => ['required', Rule::in(config('enums.jenis_pengelolaan'))],
            'teknologi'             => ['required', Rule::in(config('enums.opsi_teknologi'))],
            'pemanfaat_jiwa'        => 'nullable|integer|gte:0',
            'rumah_terlayani'       => 'nullable|integer|gte:0',
            'unit_tangki'           => 'nullable|integer|gte:0',
            'unit_bilik'            => 'nullable|integer|gte:0',
            'status_penyedotan'     => ['required', Rule::in(config('enums.opsi_ada'))],
            'tanggal_update'        => 'nullable|date_format:Y-m-d',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama'                  => 'Nama Instalasi',
            'alamat'                => 'Alamat',
            'kecamatan_id'          => 'Kecamatan',
            'kelurahan_id'          => 'Kelurahan/Desa',
            'latitude'              => 'Titik Koordinat Latitude',
            'longitude'             => 'Titik Koordinat Longitude',
            'skala'                 => 'Skala Pelayanan',
            'tahun_konstruksi'      => 'Tahun Konstruksi',
            'sumber'                => 'Sumber Dana',
            'status_keberfungsian'  => 'Status Keberfungsian',
            'kondisi'               => 'Keterangan Kondisi',
            'status_lahan'          => 'Status Lahan',
            'kapasitas'             => 'Kapasitas Desain (m3/hari)',
            'jenis'                 => 'Jenis Pengelolaan',
            'teknologi'             => 'Opsi Teknologi',
            'pemanfaat_jiwa'        => 'Jumlah Pemanfaat Jiwa',
            'rumah_terlayani'       => 'Jumlah Rumah Terlayani',
            'unit_tangki'           => 'Jumlah Unit Tangki Septik',
            'unit_bilik'            => 'Jumlah Unit Bilik',
            'status_penyedotan'     => 'Penyedotan Lumpur Tinja',
            'tanggal_update'        => 'Tanggal Update',
        ];
    }

    public function mappedData(): array
    {
        $data =  array_merge(
            $this->only([
                'nama',
                'alamat',
                'kecamatan_id',
                'kelurahan_id',
                'skala',
                'tahun_konstruksi',
                'sumber',
                'status_keberfungsian',
                'kondisi',
                'status_lahan',
                'kapasitas',
                'jenis',
                'teknologi',
                'pemanfaat_jiwa',
                'rumah_terlayani',
                'unit_tangki',
                'unit_bilik',
                'status_penyedotan',
                'tanggal_update',
            ]),
            [
                'lat'               => $this->latitude,
                'long'              => $this->longitude,
            ]
        );
        foreach (
            [
                'kapasitas',
                'pemanfaat_jiwa',
                'rumah_terlayani',
                'unit_tangki',
                'unit_bilik'
            ] as $field
        ) {
            $data[$field] = $data[$field] ?? 0;
        }
        return $data;
    }
}
