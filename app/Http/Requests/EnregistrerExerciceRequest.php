<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnregistrerExerciceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'target_sets' => ['required', 'integer', 'min:1', 'max:20'],
            'target_reps' => ['required', 'string', 'max:20'],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'rest_seconds' => ['nullable', 'integer', 'min:0', 'max:900'],
            'rir' => ['nullable', 'integer', 'min:0', 'max:10'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
