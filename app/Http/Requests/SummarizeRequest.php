<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SummarizeRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'url' => ['required', 'url',],
        ];
    }

    public function messages(): array
    {
        return [
            'url.required' => 'The URL field is required.',
            'url.url' => 'The URL must be a valid.',
        ];
    }
}
