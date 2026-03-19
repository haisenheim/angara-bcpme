<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationIndicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'framework_id', 'category_id', 'name', 'code', 'description',
        'input_type', 'score_type', 'default_weight', 'min_score', 'max_score',
        'required', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'default_weight' => 'decimal:2',
        'min_score' => 'decimal:2',
        'max_score' => 'decimal:2',
        'required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function framework()
    {
        return $this->belongsTo(EvaluationFramework::class, 'framework_id');
    }

    public function category()
    {
        return $this->belongsTo(EvaluationCategory::class, 'category_id');
    }
}
