<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFacebookFormMappingsRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mappings' => ['required', 'array'],

            'mappings.*.source_field' => [
                'required',
                'string',
            ],

            'mappings.*.target_type' => [
                'required',
                'in:lead,custom_field',
            ],

            'mappings.*.target_value' => [
                'required',
                'string',
            ],
        ];
    }
}
