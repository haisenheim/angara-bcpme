<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DelegationPouvoir extends Model
{
    protected $connection = 'central_app_mysql';

    protected $table = 'delegation_pouvoirs';

    protected $guarded = [];

    protected $casts = [
        'seuil_engagements_max' => 'decimal:2',
    ];

    public function profil(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'profil_id');
    }
}
