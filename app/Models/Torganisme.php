<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Torganisme extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function organismes()
    {
        return $this->hasMany(Organisme::class, 'type_id');
    }
}
