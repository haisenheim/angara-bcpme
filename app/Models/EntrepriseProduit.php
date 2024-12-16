<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntrepriseProduit extends Model
{
    use HasFactory;
    protected $table = 'entreprise_produits';
    public $timestamps = false;
    protected $guarded = [];
}
