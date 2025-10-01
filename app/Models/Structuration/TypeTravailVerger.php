<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeTravailVerger extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'structuration_types_travaux_vergers';
    protected $connection = 'structuration_app_mysql';


}
