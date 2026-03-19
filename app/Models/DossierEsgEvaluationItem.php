<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierEsgEvaluationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'dossier_esg_evaluation_id', 'category_code', 'indicator_code', 'indicator_label',
        'indicator_description', 'input_type', 'value_text', 'value_number', 'value_boolean',
        'score', 'weight', 'comment', 'sort_order',
    ];

    protected $casts = [
        'value_number' => 'decimal:2',
        'value_boolean' => 'boolean',
        'score' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    public function dossierEsgEvaluation()
    {
        return $this->belongsTo(DossierEsgEvaluation::class, 'dossier_esg_evaluation_id');
    }
}
