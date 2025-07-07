<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodRiskHistory extends Model
{
    protected $fillable = [
        'user_id',
        'food_name',
        'serving_quantity',
        'us_measurement',
        'metric_measurement',
        'meal_type',
        'risk_score',
        'risk_label',
    ];
}
