<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonalTriggerFoodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'food_name' => 'required|string|max:255',
            'serving_quantity' => 'nullable|string|max:255',
            'us_measurement' => 'nullable|string|max:255',
            'metric_measurement' => 'nullable|string|max:255',
            'fodmap_rating' => 'nullable|string|max:255',
            'food_category' => 'nullable|string|max:255',
            'bloating_risk_standard' => 'nullable|string|max:255',
            'bloating_risk_low' => 'nullable|string|max:255',
            'bloating_risk_medium' => 'nullable|string|max:255',
            'bloating_risk_high' => 'nullable|string|max:255',
        ];
    }
}
