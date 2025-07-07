<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FoodCategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'food_category' => $this->food_category,
        ];
    }
}
