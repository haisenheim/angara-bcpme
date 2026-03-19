<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationScoreThreshold extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'threshold_type', 'label', 'min_value', 'max_value',
        'color', 'description', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'min_value' => 'decimal:2',
        'max_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
