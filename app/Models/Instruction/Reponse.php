<?php

namespace App\Models\Instruction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reponse extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function choice()
    {
        return $this->belongsTo(Choice::class);
    }

}
