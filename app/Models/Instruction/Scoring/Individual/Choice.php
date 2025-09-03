<?php

namespace App\Models\Instruction\Scoring\Individual;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'scoring_app_mysql';
    protected $table = 'criteres_valeurs'; // Specify the table name if different
    public $timestamps = false;
    public function critere()
    {
        return $this->belongsTo(Critere::class, 'critere_id');
    }

}
