<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateShiftRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'facility_mode';
    }

    public function rules(): array
    {
        return [
            'date' => ['sometimes', 'date', 'after_or_equal:today'],
            'pay_per_hour' => ['sometimes', 'numeric', 'min:0'],
            'start_time' => ['sometimes', 'date_format:H:i'],
            'end_time' => ['sometimes', 'date_format:H:i', 'after:start_time'],
            'title' => ['sometimes', 'string'],
            'license_type' => ['sometimes', 'string', 'max:255'],
            'special_instruction' => ['nullable', 'string'],
            'location' => ['sometimes', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'end_time.after' => 'End time must be after start time.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ])
        );
    }
}
