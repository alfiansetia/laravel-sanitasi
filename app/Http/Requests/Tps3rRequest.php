<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Tps3rRequest extends FormRequest
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
            'kecamatan_id'          => 'required|exists:kecamatans,id',
            'kelurahan_id'          => 'required|exists:kelurahans,id',
            'luas'                  => 'required|integer|gte:0',
            'tahun_konstruksi'      => 'required|date_format:Y',
            'tahun_beroperasi'      => 'required|date_format:Y',
            'jumlah_timbunan'       => 'required|numeric|gte:0',
            'jumlah_penduduk'       => 'required|integer|gte:0',
            'jumlah_kk'             => 'required|integer|gte:0',
            'gerobak'               => 'required|integer|gte:0',
            'motor'                 => 'required|integer|gte:0',
            'status'                => 'required|in:Berfungsi,Tidak Berfungsi',
        ];
    }

    public function attributes(): array
    {
        return [
            'kecamatan_id'          => 'Kecamatan',
            'kelurahan_id'          => 'Kelurahan/Desa',
            'luas'                  => 'Luas',
            'tahun_konstruksi'      => 'Tahun Konstruksi',
            'tahun_beroperasi'      => 'Tahun Beroperasi',
            'jumlah_timbunan'       => 'Jumlah Timbunan Sampah (Ton/Hari)',
            'jumlah_penduduk'       => 'Jumlah Penduduk',
            'jumlah_kk'             => 'Jumlah KK Terlayani',
            'gerobak'               => 'Gerobak',
            'motor'                 => 'Motor Roda Tiga',
            'status'                => 'Keberfungsian',
        ];
    }

    public function mappedData(): array
    {
        return $this->only([
            'kecamatan_id',
            'kelurahan_id',
            'luas',
            'tahun_konstruksi',
            'tahun_beroperasi',
            'jumlah_timbunan',
            'jumlah_penduduk',
            'jumlah_kk',
            'gerobak',
            'motor',
            'status',
        ]);
    }
}
