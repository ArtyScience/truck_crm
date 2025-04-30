<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadUpdateRequest extends FormRequest
{
    private string $phone_regex = "/^(?:\+1\s\(\d{3}\)\s\d{3}\s\d{4}|\d{10}|\(\d{3}\)-\d{3}-\d{4}|\d{3}-\d{3}-\d{4})$/";
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
            'name' => 'required|string|min:5|max:30',
            'company' => 'required|string|min:4|max:30',
            'email' => ['required', 'email', 'max:50', 'regex:/^[a-zA-Z0-9_@.]*$/'],
            'phone' => ['required', 'string', "regex:$this->phone_regex"],
            'notes' => 'nullable|string',
            'additional_phone' => ["nullable", "regex:$this->phone_regex"],
            'additional_email' => ['nullable', 'email', 'regex:/^[a-zA-Z0-9_@.]*$/'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.min' => 'Min 5 characters',
            'company.required' => 'Company is required',
            'phone.required' => 'Phone is required',
            'phone.regex' => 'Incorrect phone format',
            'email.required' => 'Email is required',
            'email.regex' => 'Only letters, numbers are allowed',
            'additional_phone.regex' => 'Incorrect phone format',
            'additional_email.regex' => 'Only letters, numbers are allowed',
        ];
    }
}
