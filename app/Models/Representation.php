<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Representation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function agences()
    {
        return $this->hasMany(Agence::class);
    }
}
