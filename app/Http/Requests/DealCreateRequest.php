<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DealCreateRequest extends FormRequest
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
              "lead_id" => ['required'],
              "income" => ['required'],
              "company_contact" => ['required'],
              "pick_up_location" => ['required'],
              "delivery_location" => ['required'],
              "pick_up_date" => ['required'],
              "delivery_date" => ['required'],
              "equipment_type" => ['required'],
              "shipment_type" => ['required'],
              "deal_type" => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'lead_id.required' => 'Lead is required.',
            'income.required' => 'Income is required.',
            'company_contact.required' => 'Contact is required.',
            'pick_up_location.required' => 'Pick up Location is required.',
            'delivery_location.required' => 'Delivery Location is required.',
            'pick_up_date.required' => 'Date is required.',
            'delivery_date.required' => 'Date is required.',
            'equipment_type.required' => 'Equipment is required.',
            'shipment_type.required' => 'Shipment is required.',
            'deal_type.required' => 'Deal is required.',
        ];
    }
}
