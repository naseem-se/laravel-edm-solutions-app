<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isFacility = $this->input('role') === 'facility_mode';

        return [
            'role' => ['required', 'string', 'in:worker_mode,facility_mode'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],  // 'confirmed' handles password_confirmation check
            'phone_number' => ['required', 'string', 'max:255', 'unique:users'],
            'firebase_uid' => ['required', 'string', 'max:255'],

            // Facility-only required fields
            'facility_name' => [$isFacility ? 'required' : 'nullable', 'string', 'max:255'],
            'facility_address' => [$isFacility ? 'required' : 'nullable', 'string', 'max:500'],
            'primary_contact' => [$isFacility ? 'required' : 'nullable', 'string', 'max:255'],
            'billing_contact_name' => [$isFacility ? 'required' : 'nullable', 'string', 'max:255'],
            'billing_contact_email' => [$isFacility ? 'required' : 'nullable', 'email', 'max:255'],
            'scheduling_contact_name' => [$isFacility ? 'required' : 'nullable', 'string', 'max:255'],
            'scheduling_contact_email' => [$isFacility ? 'required' : 'nullable', 'email', 'max:255'],
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
            ], 422)
        );
    }
}
