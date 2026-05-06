<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntrepriseCritereAvis extends Model
{
    protected $connection = 'central_app_mysql';

    protected $table = 'entreprise_critere_avis';

    protected $guarded = [];

    /** @var array<string, string> */
    protected $casts = [
        'saved_at' => 'datetime',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

