<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'framework_id', 'name', 'code', 'description', 'weight',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function framework()
    {
        return $this->belongsTo(EvaluationFramework::class, 'framework_id');
    }

    public function indicators()
    {
        return $this->hasMany(EvaluationIndicator::class, 'category_id');
    }
}
