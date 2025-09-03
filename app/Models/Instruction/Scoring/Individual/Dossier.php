<?php

namespace App\Models\Instruction\Scoring\Individual;

use App\Models\Structuration\Cooperative;
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

    public function cooperative(){
        return $this->belongsTo(Cooperative::class, 'cooperative_id');
    }
}
