<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWeeklyRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\WeeklyRecord::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'member_id' => 'required|exists:members,id',
            'year' => 'required|integer|min:2020|max:2030',
            'week' => 'required|integer|min:1|max:53',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'invoice_number' => 'nullable|string|max:255',

            // Internal Reimmersion
            'kg_micro_internal_reimmersion' => 'nullable|numeric|min:0',
            'price_micro_internal_reimmersion' => 'nullable|numeric|min:0',
            'kg_small_internal_reimmersion' => 'nullable|numeric|min:0',
            'price_small_internal_reimmersion' => 'nullable|numeric|min:0',

            // Resale Reimmersion
            'kg_micro_resale_reimmersion' => 'nullable|numeric|min:0',
            'price_micro_resale_reimmersion' => 'nullable|numeric|min:0',
            'kg_small_resale_reimmersion' => 'nullable|numeric|min:0',
            'price_small_resale_reimmersion' => 'nullable|numeric|min:0',

            // Direct Consumption
            'kg_medium_consumption' => 'nullable|numeric|min:0',
            'price_medium_consumption' => 'nullable|numeric|min:0',
            'kg_large_consumption' => 'nullable|numeric|min:0',
            'price_large_consumption' => 'nullable|numeric|min:0',
            'kg_super_consumption' => 'nullable|numeric|min:0',
            'price_super_consumption' => 'nullable|numeric|min:0',

            // Calculations
            'taxable_amount' => 'nullable|numeric|min:0',
            'advance_paid' => 'nullable|numeric|min:0',
            'withholding_tax' => 'nullable|numeric|min:0',
            'profis' => 'nullable|numeric|min:0',
            'bank_transfer' => 'nullable|numeric|min:0'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'member_id' => 'socio',
            'year' => 'anno',
            'week' => 'settimana',
            'start_date' => 'data inizio',
            'end_date' => 'data fine',
            'invoice_number' => 'numero fattura',
            'taxable_amount' => 'imponibile',
            'advance_paid' => 'acconto pagato',
            'withholding_tax' => 'ritenuta d\'acconto',
            'profis' => 'PROFIS',
            'bank_transfer' => 'bonifico'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'member_id.required' => 'Devi selezionare un socio.',
            'member_id.exists' => 'Il socio selezionato non esiste.',
            'end_date.after_or_equal' => 'La data fine deve essere uguale o successiva alla data inizio.',
            'week.min' => 'La settimana deve essere compresa tra 1 e 53.',
            'week.max' => 'La settimana deve essere compresa tra 1 e 53.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Imposta valori di default per campi numerici null
        $numericFields = [
            'kg_micro_internal_reimmersion', 'price_micro_internal_reimmersion',
            'kg_small_internal_reimmersion', 'price_small_internal_reimmersion',
            'kg_micro_resale_reimmersion', 'price_micro_resale_reimmersion',
            'kg_small_resale_reimmersion', 'price_small_resale_reimmersion',
            'kg_medium_consumption', 'price_medium_consumption',
            'kg_large_consumption', 'price_large_consumption',
            'kg_super_consumption', 'price_super_consumption',
            'taxable_amount', 'advance_paid', 'withholding_tax', 'profis', 'bank_transfer'
        ];

        $defaults = [];
        foreach ($numericFields as $field) {
            if (!$this->has($field) || $this->input($field) === null || $this->input($field) === '') {
                $defaults[$field] = 0;
            }
        }

        $defaults['company_id'] = $this->user()->company_id;

        $this->merge($defaults);
    }
}
