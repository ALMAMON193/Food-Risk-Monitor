<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $userId)
 */
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
