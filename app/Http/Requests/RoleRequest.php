<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_role' => [
                'required', 'string', 'max:100',
                Rule::unique('role', 'nama_role')->ignore($this->route('id'), 'idrole'),
            ],
        ];
    }
}
