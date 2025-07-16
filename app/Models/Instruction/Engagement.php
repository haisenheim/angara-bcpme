<?php

namespace App\Models\Instruction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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

    public function getItemsAttribute()
    {
        return new Collection();//$this->hasMany(EngagementEntreprise::class);
    }

    public function getElts($entreprise_id)
    {
        if($this->is_leaf){
            return $this->hasMany(EngagementEntreprise::class)->where('entreprise_id', $entreprise_id)->get();
        }else{
            return null;
        }
    }


    // Accessor pour encours_montant
    public function getEncoursMontantAttribute()
    {
        if($this->is_leaf){
            $_items = $this->items;
            return $_items?->reduce(function($carry,$item){
                return $carry + $item->encours_montant;
            },0);
        }else{
            return $this->children->reduce(function ($carry, $child) {
                return $carry + $child->encours_montant;
            }, 0);
        }
    }

    // Accessor pour encours_part
    public function getEncoursPartAttribute()
    {
        return 0;
    }

    // Accessor pour encours_impaye
    public function getEncoursImpayeAttribute()
    {
        if($this->is_leaf){
            $_items = $this->items;
            return $_items?->reduce(function($carry,$item){
                return $carry + $item->encours_impaye;
            },0);
        }else{
            return $this->children->reduce(function ($carry, $child) {
                return $carry + $child->encours_impaye;
            }, 0);
        }
    }

    // Accessor pour encours_dt_validite
    public function getEncoursDtValiditeAttribute()
    {
        return now();
    }

    // Accessor pour sollicite_montant
    public function getSolliciteMontantAttribute()
    {
        if($this->is_leaf){
            $_items = $this->items;
            return $_items?->reduce(function($carry,$item){
                return $carry + $item->sollicite_montant;
            },0);
        }else{
            return $this->children->reduce(function ($carry, $child) {
                return $carry + $child->sollicite_montant;
            }, 0);
        }
    }

    // Accessor pour sollicite_part
    public function getSollicitePartAttribute()
    {
        return 0;
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
