<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;

class AngaraNotification extends DatabaseNotification
{
    use HasFactory;
    protected $connection = 'central_app_mysql';
    protected $table = 'notifications';
    protected $guarded = [];
}
