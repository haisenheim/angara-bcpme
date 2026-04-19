<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyseCritiqueAvis extends Model
{
    protected $table = 'analyse_critique_avis';

    public const SOURCE_JURIDIQUE = 'juridique';

    public const SOURCE_CONFORMITE = 'conformite';

    public const SOURCE_GESTIONNAIRE = 'gestionnaire';

    public const SOURCE_ANALYSTE = 'analyste';

    public const SOURCE_CHEF_AGENCE = 'chef_agence';

    public const SOURCE_CHEF_FILIERE = 'chef_filiere';

    public const SOURCE_INSTRUCTION = 'instruction';

    public const SOURCE_EER = 'eer';

    public const SOURCE_AUTRE = 'autre';

    public const ETAT_BROUILLON = 'brouillon';

    public const ETAT_EMIS = 'emis';

    public const ETAT_INTEGRE = 'integre';

    public const ETAT_ECARTE = 'ecarte';

    public const ETAT_ANNULE = 'annule';

    protected $connection = 'central_app_mysql';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'emis_at' => 'datetime',
        ];
    }

    public function dossierAnalyseCritique(): BelongsTo
    {
        return $this->belongsTo(DossierAnalyseCritique::class);
    }

    public function instructionDossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class, 'instruction_dossier_id');
    }

    public function emisPar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'emis_par_user_id');
    }
}
