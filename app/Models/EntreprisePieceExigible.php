<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntreprisePieceExigible extends Model
{
    protected $connection = 'central_app_mysql';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'provided_at' => 'datetime',
        ];
    }

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function definition(): BelongsTo
    {
        return $this->belongsTo(PieceExigibleDefinition::class, 'piece_exigible_definition_id');
    }

    public function fichier(): BelongsTo
    {
        return $this->belongsTo(Fichier::class, 'fichier_id');
    }

    public function providedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provided_by_user_id');
    }

    public function isProvided(): bool
    {
        return $this->fichier_id !== null && $this->fichier_id !== 0;
    }
}
