<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnalyticsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'food_risk_chart' => [
                'headline' => $this['food_risk']['headline'] ?? '0%',
                'change'   => $this['food_risk']['change'] ?? '+0%',
                'labels'   => $this['food_risk']['labels'] ?? [],
                'data'     => $this['food_risk']['data'] ?? [],
                'title'    => 'Your Last Food Intake vs Symptoms History',
            ],
            'symptom_chart' => [
                'headline' => $this['symptom']['headline'] ?? '0%',
                'change'   => $this['symptom']['change'] ?? '+0%',
                'labels'   => $this['symptom']['labels'] ?? [],
                'data'     => $this['symptom']['data'] ?? [],
                'title'    => 'Your Last 7 Days Bloating Severity History',
            ],
        ];
    }
}
