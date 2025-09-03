<?php

namespace App\Models\Instruction\Scoring\Individual;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierChoice extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'scoring_app_mysql';
    protected $table = 'dossiers_choices'; // Specify the table name if different
    public $timestamps = false;
    public function critere()
    {
        return $this->belongsTo(Critere::class, 'critere_id');
    }

    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'dossier_id');
    }

    public function choice()
    {
        return $this->belongsTo(Choice::class, 'choice_id');
    }

}
