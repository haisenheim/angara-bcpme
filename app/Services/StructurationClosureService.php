<?php

namespace App\Services;

use App\Models\Dossier;
use App\Models\User;

/**
 * Étape "structuration client" : soumission chef de filière -> décision du chef d'agence (CA).
 *
 * IMPORTANT :
 * - Cette étape ne dépend PAS de la délégation de pouvoir.
 * - Seul le chef d'agence de l'agence du dossier peut valider / rejeter (quand c'est en attente).
 *
 * NB: Les colonnes DB existantes sont conservées (instruction_agence_*),
 * mais doivent être interprétées comme "décision CA sur structuration".
 */
class StructurationClosureService
{
    public function canChefAgenceDecide(?User $user, Dossier $dossier): bool
    {
        if (! $user || ! $dossier->isInstructionPendingAgenceValidation()) {
            return false;
        }

        $chefAgenceRole = (int) config('angara.role_chef_agence', 15);
        if ((int) $user->role_id !== $chefAgenceRole) {
            return false;
        }

        $userAgence = $user->agence_id !== null ? (int) $user->agence_id : null;
        $dossierAgence = $dossier->agence_id !== null ? (int) $dossier->agence_id : null;

        return $userAgence !== null && $dossierAgence !== null && $userAgence === $dossierAgence;
    }

    /**
     * @return 'valide'|'rejete'|'attente'|'non_soumis'
     */
    public function closureStatutKey(Dossier $dossier): string
    {
        if ($dossier->instruction_agence_validated_at) {
            return 'valide';
        }
        if ($dossier->isInstructionRejectedByAgence()) {
            return 'rejete';
        }
        if ($dossier->chef_filiere_submitted_to_agence_at) {
            return 'attente';
        }

        return 'non_soumis';
    }

    public function closureStatutLabel(Dossier $dossier): string
    {
        return match ($this->closureStatutKey($dossier)) {
            'valide' => 'Structuration validée',
            'rejete' => 'Structuration rejetée',
            'attente' => 'Structuration en attente de validation (chef de filière → chef d’agence)',
            default => 'Structuration non soumise au chef d’agence',
        };
    }
}

