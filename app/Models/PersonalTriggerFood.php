<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalTriggerFood extends Model
{
    protected $table = 'personal_trigger_food';
    protected $fillable = [
        'user_id',
        'food_name',
        'serving_quantity',
        'us_measurement',
        'metric_measurement',
        'fodmap_rating',
        'food_category',
        'bloating_risk_standard',
        'bloating_risk_low',
        'bloating_risk_medium',
        'bloating_risk_high',
    ];

    // Relationship with User
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
