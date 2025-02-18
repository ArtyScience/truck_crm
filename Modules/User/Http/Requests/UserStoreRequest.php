<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'campaign_email' => 'required|email',
            'role' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',               // Minimum 8 characters
                'confirmed',           // Ensures password and password_confirmation match
                'regex:/[a-z]/',       // At least one lowercase letter
                'regex:/[A-Z]/',       // At least one uppercase letter
                'regex:/[0-9]/',       // At least one digit
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'role.required' => 'Role is required',
            'email.required' => 'Email is required',
            'campaign_email.required' => 'Email is required',
            'campaign_email.email' => 'Enter a valid email',
            'email.email' => 'Email is not valid',
            'email.unique' => 'Email already exists',
            'password.regex' => 'Password is to easy',
            'password.confirmed' => 'Password does not match.',
        ];
    }
}
