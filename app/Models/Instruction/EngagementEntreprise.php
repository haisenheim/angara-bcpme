<?php

namespace App\Models\Instruction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngagementEntreprise extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relations
    public function engagement()
    {
        return $this->belongsTo(Engagement::class);
    }

    public function banque()
    {
        return $this->belongsTo('App\Models\Banque');
    }

    public function entreprise()
    {
        return $this->belongsTo('App\Models\Entreprise');
    }

    public function getEncoursDtValiditeAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : null;
    }

    public function getSolliciteDtValiditeAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : null;
    }
}
