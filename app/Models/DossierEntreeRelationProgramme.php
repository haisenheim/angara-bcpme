<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DossierEntreeRelationProgramme extends Model
{
    public const TYPE_FINANCIER = 'financier';

    public const TYPE_NON_FINANCIER = 'non_financier';

    public const TYPE_MIXTE = 'mixte';

    public const STATUT_PROPOSE = 'propose';

    public const STATUT_SOUMIS = 'soumis';

    public const STATUT_VALIDE = 'valide';

    public const STATUT_REJETE = 'rejete';

    protected $connection = 'central_app_mysql';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'validated_at' => 'datetime',
        ];
    }

    public function dossierEntreeRelation(): BelongsTo
    {
        return $this->belongsTo(DossierEntreeRelation::class);
    }

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    public function instructionDossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class, 'instruction_dossier_id');
    }
}
