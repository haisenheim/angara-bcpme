<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammeProduit extends Model
{
    use HasFactory;
    protected $table = 'programme_produits';
    public $timestamps = false;
    protected $guarded = [];
}
