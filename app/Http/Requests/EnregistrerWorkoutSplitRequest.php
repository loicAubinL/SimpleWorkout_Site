<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnregistrerWorkoutSplitRequest extends FormRequest
{
    public function authorize(): bool
    {
        // L'autorisation propriétaire est vérifiée dans le contrôleur (policy).
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_public' => $this->boolean('is_public'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
            'is_public' => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return ['description' => __('interface.description')];
    }
}
