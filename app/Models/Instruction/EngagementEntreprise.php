<?php

namespace App\Models\Instruction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngagementEntreprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'engagement_id',
        'encours_montant',
        'encours_part',
        'encours_impaye',
        'sollicite_montant',
        'sollicite_part'
    ];

    // Relations
    public function engagement()
    {
        return $this->belongsTo(Engagement::class);
    }
}
