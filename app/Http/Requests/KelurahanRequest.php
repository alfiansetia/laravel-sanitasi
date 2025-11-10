<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KelurahanRequest extends FormRequest
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
        $rules = [
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kode'         => 'required|string|max:200',
            'nama'         => 'required|string|max:200',
        ];

        // Cek apakah ini request update atau store
        if ($this->isMethod('post')) {
            // STORE
            $rules['kode'] .= '|unique:kelurahans,kode';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // UPDATE
            $id = $this->route('kelurahan')->id ?? null;
            $rules['kode'] .= '|unique:kelurahans,kode,' . $id;
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'kecamatan_id' => 'Kecamatan',
            'kode'         => 'Kode Kelurahan',
            'nama'         => 'Nama Kelurahan',
        ];
    }

    public function mappedData(): array
    {
        return $this->only(['kecamatan_id', 'kode', 'nama']);
    }
}
