<?php

namespace App\Models\Instruction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmeNote extends Model
{
    use HasFactory;

    protected $table = 'sme_notes';

    protected $guarded = [];
}
