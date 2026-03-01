<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
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
            'title'       => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'paid_by'     => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Le titre est obligatoire.',
            'amount.required'      => 'Le montant est obligatoire.',
            'amount.numeric'       => 'Le montant doit être un nombre.',
            'amount.min'           => 'Le montant doit être supérieur à 0.',
            'paid_by.required'     => 'Veuillez choisir qui a payé.',
        ];
    }
}
