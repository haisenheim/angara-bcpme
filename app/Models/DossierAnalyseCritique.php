<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Consolidation des avis (juridique, conformité, instruction, etc.) pour un client / prospect.
 */
class DossierAnalyseCritique extends Model
{
    use HasFactory;

    protected $connection = 'central_app_mysql';

    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'token';
    }

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function avis(): HasMany
    {
        return $this->hasMany(AnalyseCritiqueAvis::class)->orderBy('sort_order');
    }
}
