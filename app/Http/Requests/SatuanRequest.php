<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SatuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_satuan' => [
                'required', 'string', 'max:45',
                Rule::unique('satuan', 'nama_satuan')->ignore($this->route('id'), 'idsatuan'),
            ],
        ];
    }
}
