<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammeIndicateur extends Model
{
    use HasFactory;
    protected $table = 'programme_indicateurs';
    public $timestamps = false;
    protected $guarded = [];

    public function indicateur(){
        return $this->belongsTo('App\Models\Indicateur');
    }
}
