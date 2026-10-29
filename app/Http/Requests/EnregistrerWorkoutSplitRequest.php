<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class EnregistrerWorkoutSplitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge(['is_active' => $this->boolean('is_active')]);
    }
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'program' => [
                'nullable',
                'file',
                'extensions:json',
                'mimetypes:application/json,text/plain',
                'max:512',
                function (string $attribute, mixed $value, \Closure $fail) {
                    json_decode(file_get_contents($value->getRealPath()));
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $fail('Le fichier ne contient pas du JSON valide.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du workout est obligatoire.',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'program.extensions' => 'Le programme doit être un fichier .json.',
            'program.mimetypes' => 'Le programme doit être un fichier JSON.',
            'program.max' => 'Le programme ne doit pas dépasser 512 Ko.',
        ];
    }
}
