<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateShiftRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date', 'after_or_equal:today'],
            'pay_per_hour' => ['required', 'numeric', 'min:0'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'title' => ['required', 'string'],
            'license_type' => ['nullable', 'string', 'max:255'],
            'special_instruction' => ['nullable', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'is_emergency' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'date.after_or_equal' => 'Shift date cannot be in the past.',
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
