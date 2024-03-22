<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            'first_name' => 'required|max:200',
            'last_name' => 'required|max:200',
            'company_id' => 'required|exists:companies,id',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|max:20',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'The first name field is required.',
            'first_name.max' => 'The first name must not exceed 200 characters.',
            'last_name.required' => 'The last name field is required.',
            'last_name.max' => 'The last name must not exceed 200 characters.',
            'company_id.required' => 'The company ID field is required.',
            'company_id.exists' => 'The selected company ID is invalid.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email must not exceed 100 characters.',
            'phone.max' => 'The phone number must not exceed 20 characters.',
        ];
    }
}