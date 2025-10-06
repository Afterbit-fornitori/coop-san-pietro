<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $member = $this->route('member');
        return $this->user()->can('update', $member);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $member = $this->route('member');

        return [
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'tax_code' => [
                'required',
                'string',
                'size:16',
                Rule::unique('members')->where(function ($query) {
                    return $query->where('company_id', $this->user()->company_id);
                })->ignore($member->id)
            ],
            'birth_date' => 'required|date|before:today',
            'birth_place' => 'required|string|max:255',
            'rpm_registration' => 'required|string|max:255',
            'rpm_registration_date' => 'required|date|before_or_equal:today',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_active' => 'nullable|boolean'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'last_name' => 'cognome',
            'first_name' => 'nome',
            'tax_code' => 'codice fiscale',
            'birth_date' => 'data di nascita',
            'birth_place' => 'luogo di nascita',
            'rpm_registration' => 'matricola RPM',
            'rpm_registration_date' => 'data iscrizione RPM',
            'phone' => 'telefono',
            'email' => 'email',
            'is_active' => 'attivo'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'tax_code.required' => 'Il codice fiscale è obbligatorio.',
            'tax_code.size' => 'Il codice fiscale deve essere di 16 caratteri.',
            'tax_code.unique' => 'Questo codice fiscale è già registrato per la tua azienda.',
            'birth_date.before' => 'La data di nascita deve essere antecedente a oggi.',
            'rpm_registration_date.before_or_equal' => 'La data di iscrizione RPM non può essere futura.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->has('is_active'),
        ]);
    }
}
