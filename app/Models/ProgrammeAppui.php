<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammeAppui extends Model
{
    use HasFactory;
    protected $table = 'programme_appuis';
    public $timestamps = false;
    protected $guarded = [];
}
