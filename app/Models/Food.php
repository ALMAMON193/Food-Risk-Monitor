<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Food extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_name',
        'serving_quantity',
        'unit',
        'us_measurement',
        'metric_measurement',
        'fodmap_rating',
        'fodmap_type',
        'fructose',
        'lactose',
        'sorbitol',
        'mannitol',
        'fructans',
        'gos',
        'food_category',
        'ibs_notes',
        'dietary_tags',
        'vegan',
        'gluten_free',
        'vegetarian',
        'usda_match',
        'bloating_risk_standard',
        'bloating_risk_low',
        'bloating_risk_medium',
        'bloating_risk_high',
        'reference',
    ];

    protected $casts = [
        // Booleans
        'fructose' => 'boolean',
        'lactose' => 'boolean',
        'sorbitol' => 'boolean',
        'mannitol' => 'boolean',
        'fructans' => 'boolean',
        'gos' => 'boolean',
        'vegan' => 'boolean',
        'gluten_free' => 'boolean',
        'vegetarian' => 'boolean',
        'bloating_risk_standard' => 'decimal:1',
        'bloating_risk_low'      => 'decimal:1',
        'bloating_risk_medium'   => 'decimal:1',
        'bloating_risk_high'     => 'decimal:1',
    ];
}
