<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'company_id' => ['required', 'exists:companies,id'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,name']
        ];

        // Se stiamo aggiornando
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->route('user')->id];
            $rules['password'] = ['sometimes', 'confirmed', Password::defaults()];
        }

        return $rules;
    }
}
