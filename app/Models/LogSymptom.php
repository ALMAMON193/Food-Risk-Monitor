<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $userId)
 */
class LogSymptom extends Model
{
    use HasFactory;

    protected $table = 'log_symptoms';

    protected $fillable = [
        'user_id',
        'food_name',
        'bloating',
        'gas',
        'pain',
        'stool_issues',
        'notes',
    ];

    protected $casts = [
        'bloating' => 'integer',
        'gas' => 'integer',
        'pain' => 'integer',
        'stool_issues' => 'integer',
    ];

    // Relationship with User (optional)
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
