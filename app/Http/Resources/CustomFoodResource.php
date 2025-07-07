<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomFoodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'food_name' => $this->food_name,
            'serving_quantity' => $this->serving_quantity,
            'us_measurement' => $this->us_measurement,
            'metric_measurement' => $this->metric_measurement,
            'fodmap_rating' => $this->fodmap_rating,
            'food_category' => $this->food_category,
            'bloating_risk_standard' => $this->bloating_risk_standard,
            'bloating_risk_low' => $this->bloating_risk_low,
            'bloating_risk_medium' => $this->bloating_risk_medium,
            'bloating_risk_high' => $this->bloating_risk_high,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
