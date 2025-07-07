<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FoodRiskResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'food_name' => $this->food_name,
            'percentage' => round($this->bloating_risk_standard) . '%',
            'message' => 'Bloating rate ' . strtolower($this->fodmap_rating),
        ];
    }
}
