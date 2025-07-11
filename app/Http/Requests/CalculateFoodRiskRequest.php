<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalculateFoodRiskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            'food_name' => 'required|string',
            'serving_quantity'  => ['required', 'string', 'regex:/^\d+(\.\d+)?(\/\d+(\.\d+)?)?$/'],
            'us_measurement' => 'required',      // Only 'oz' allowed
            'metric_measurement' => 'required', // Only 'g' or 'ml' allowed
            'meal_type' => 'nullable|string',
        ];
    }
    public function messages(): array
    {
        return [
            'serving_quantity.regex' => 'serving_quantity must be a number like 1, 2.5 or a fraction like 1/2.',
        ];
    }
}
