<?php

namespace App\Models\Instruction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Engagement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'is_title',
        'is_leaf',
        'niveau',
        'montant',
    ];

    protected $dates = ['created_at', 'updated_at'];

    // Relations
    public function parent()
    {
        return $this->belongsTo(Engagement::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Engagement::class, 'parent_id');
    }

    public function items()
    {
        return $this->hasMany(EngagementEntreprise::class);
    }

    // Méthode pour calculer la somme
    private function sum($engagement, $field)
    {
        if ($engagement->is_leaf) {
            return $engagement->items->sum($field);
        } else {
            return $engagement->children->sum(function ($child) use ($field) {
                return $this->sum($child, $field);
            });
        }
    }

    // Accessor pour encours_montant
    public function getEncoursMontantAttribute()
    {
        return $this->sum($this, 'encours_montant');
    }

    // Accessor pour encours_part
    public function getEncoursPartAttribute()
    {
        return $this->sum($this, 'encours_part');
    }

    // Accessor pour encours_impaye
    public function getEncoursImpayeAttribute()
    {
        return $this->sum($this, 'encours_impaye');
    }

    // Accessor pour encours_dt_validite
    public function getEncoursDtValiditeAttribute()
    {
        return now();
    }

    // Accessor pour sollicite_montant
    public function getSolliciteMontantAttribute()
    {
        return $this->sum($this, 'sollicite_montant');
    }

    // Accessor pour sollicite_part
    public function getSollicitePartAttribute()
    {
        return $this->sum($this, 'sollicite_part');
    }

    // Accessor pour variation
    public function getVariationAttribute()
    {
        return $this->sollicite_montant - $this->encours_montant;
    }

    // Accessor pour sollicite_dt_validite
    public function getSolliciteDtValiditeAttribute()
    {
        return now();
    }

    // Accessor pour total_montant
    public function getTotalMontantAttribute()
    {
        return $this->encours_montant + $this->sollicite_montant;
    }

    // Accessor pour total_part
    public function getTotalPartAttribute()
    {
        return 100; // Ou une autre logique de calcul si nécessaire
    }

    // Accessor pour dt_validite
    public function getDtValiditeAttribute()
    {
        return now();
    }
}
