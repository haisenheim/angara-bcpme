<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banque extends Model
{
    use HasFactory;

    protected $connection = 'central_app_mysql';

    protected $table = 'banques';

    protected $guarded = [];

    public $timestamps = false;
}
