<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationFramework extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'description', 'is_active', 'applies_to',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function categories()
    {
        return $this->hasMany(EvaluationCategory::class, 'framework_id');
    }

    public function indicators()
    {
        return $this->hasMany(EvaluationIndicator::class, 'framework_id');
    }
}
