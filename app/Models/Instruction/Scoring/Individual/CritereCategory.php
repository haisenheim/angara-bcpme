<?php

namespace App\Models\Instruction\Scoring\Individual;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CritereCategory extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'scoring_app_mysql';
    protected $table = 'criteres_categories'; // Specify the table name if different

    public function criteres()
    {
        return $this->hasMany(Critere::class, 'category_id');
    }


}
