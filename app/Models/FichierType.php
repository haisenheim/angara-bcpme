<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichierType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $connection = 'central_app_mysql';

    protected $table = 'fichiers_types';

    protected $guarded = [];
}
