<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agence extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function representation()
    {
        return $this->belongsTo(Representation::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function getStatusAttribute()
    {
        if ($this->active) {
            return [
                'name' => 'actif',
                'color' => 'success',
            ];
        }

        return [
            'name' => 'verrouillé',
            'color' => 'danger',
        ];
    }
}
