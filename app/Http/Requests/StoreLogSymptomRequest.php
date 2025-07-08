<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLogSymptomRequest extends FormRequest
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
            'food_name' => 'required',
            'bloating' => 'required|numeric|min:0|max:5',
            'gas' => 'required|numeric|min:0|max:5',
            'pain' => 'required|numeric|min:0|max:5',
            'stool_issues' => 'required|numeric|min:0|max:5',
            'notes' => 'nullable|string|max:1000',
        ];
    }

}
