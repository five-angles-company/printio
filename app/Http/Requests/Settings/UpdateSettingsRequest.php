<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
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
            'label_printer' => ['nullable', 'exists:printers,id'],
            'receipt_printer' => ['nullable', 'exists:printers,id'],
            'instructions_printer' => ['nullable', 'exists:printers,id'],
            'pos_session_printer' => ['nullable', 'exists:printers,id'],
            'api_url' => ['nullable', 'url'],
        ];
    }
}
