<?php

namespace App\Http\Resources;

use App\Helpers\NumberHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class FoodRiskCalculateResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'user_information' => [
                'user_id' => $this->whenNotNull($this->user_id),
                'name'    => $this->whenNotNull($this->name),
            ],
            'calculated_risk_value' => [
                'food_name'          => $this->food_name,
                'risk_score'         => round($this->risk_score, 1),
                'risk_label'         => $this->risk_label,
                'serving_quantity'   => NumberHelper::toFloat($this->serving_quantity),
                'us_measurement'     => $this->us_measurement,
                'metric_measurement' => $this->metric_measurement,
                'meal_type'          => $this->meal_type,
            ],
            'suggest_low_risk_food' => $this->when(isset($this->suggestions), function () {
                return $this->suggestions->map(function ($food) {
                    return [
                        'food_name'  => $food->food_name,
                        'risk_score' => round($food->risk_score, 1),
                        'category'   => $food->food_category,
                    ];
                });
            }),

        ];
    }
}
