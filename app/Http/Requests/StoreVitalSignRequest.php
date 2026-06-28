<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVitalSignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create vital sign') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'systolic' => ['required', 'integer', 'min:0', 'max:300'],
            'diastolic' => ['required', 'integer', 'min:0', 'max:200'],
            'pulse' => ['required', 'integer', 'min:0', 'max:250'],
            'temperature' => ['nullable', 'numeric', 'min:30', 'max:45'],
            'recorded_at' => ['nullable', 'date'],
        ];
    }
}
