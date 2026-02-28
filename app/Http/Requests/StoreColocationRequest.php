<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreColocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return !auth()->user()->hasActiveColocation();
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de la colocation est obligatoire.',
            'name.max'      => 'Le nom ne peut pas dépasser 255 caractères.',
        ];
    }
}