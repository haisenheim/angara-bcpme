<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PieceExigibleDefinition extends Model
{
    protected $connection = 'central_app_mysql';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function entreprisePieces(): HasMany
    {
        return $this->hasMany(EntreprisePieceExigible::class, 'piece_exigible_definition_id');
    }
}
