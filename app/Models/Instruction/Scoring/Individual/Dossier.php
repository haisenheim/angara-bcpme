<?php

namespace App\Models\Instruction\Scoring\Individual;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'scoring_app_mysql';

    public function category()
    {
        return $this->belongsTo(CritereCategory::class, 'category_id');
    }

    public function choices()
    {
        return $this->hasMany(Choice::class, 'dossier_id');
    }
}
