<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KecamatanRequest extends FormRequest
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
        $rules = [];
        if ($this->isMethod('post')) {
            $rules['nama'] = 'required|string|max:200|unique:kecamatans,nama';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // UPDATE
            $id = $this->route('kecamatan')->id ?? null;
            $rules['nama'] = 'required|string|max:200|unique:kecamatans,nama,' . $id;;
        }
        return $rules;
    }

    public function attributes(): array
    {
        return [
            'nama'  => 'Nama Kecamatan',
        ];
    }

    public function mappedData(): array
    {
        return $this->only(['nama']);
    }
}
