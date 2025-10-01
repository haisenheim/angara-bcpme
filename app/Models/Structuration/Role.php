<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'structuration_roles';
    protected $connection = 'structuration_app_mysql';

    public function users(){
        return $this->hasMany(User::class);
    }
}
