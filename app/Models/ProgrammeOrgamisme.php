<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammeOrgamisme extends Model
{
    use HasFactory;
    protected $table = 'programme_organismes';
    public $timestamps = false;
    protected $guarded = [];
}
