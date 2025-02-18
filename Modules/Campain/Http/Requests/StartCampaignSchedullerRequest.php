<?php

namespace Modules\Campain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartCampaignSchedullerRequest extends FormRequest
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
            'form.days' => 'required',
            'form.timezone' => 'required',
            'form.start_hour' => 'required',
            'form.end_hour' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'form.days.required' => 'Days are requred',
            'form.timezone.required' => 'Timezone is requred',
            'form.timezone.required' => 'Timezone is requred',
            'form.start_hour.required' => 'Start hour is requred',
            'form.end_hour.required' => 'End hour is requred',
        ];
    }
}
