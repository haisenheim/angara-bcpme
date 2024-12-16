<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntrepriseAppui extends Model
{
    use HasFactory;
    protected $table = 'entreprise_appuis';
    public $timestamps = false;
    protected $guarded = [];
}
