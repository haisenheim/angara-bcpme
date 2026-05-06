<?php

namespace App\Services;

use App\Models\DelegationPouvoir;
use App\Models\Dossier;
use App\Models\User;

class InstructionDelegationService
{
    /**
     * Profils (users.role_id = profils.id) autorisés à valider / rejeter la clôture du dossier
     * d'instruction, selon le total des engagements sollicités et le paramétrage de délégation.
     *
     * Règle métier (prompt l. 226) : « En dernier ressort c'est le DG ou le DGA qui dispose de
     * la possibilité de clôturer le dossier. » → le DG et le DGA conservent TOUJOURS le pouvoir
     * de clôture, en parallèle du profil délégué applicable au seuil.
     *
     * Sans règle / sans total : DG et DGA uniquement.
     */
    public function authorizedProfilIdsForDossier(Dossier $dossier): array
    {
        $direction = $this->defaultDirectionProfilIds();

        if ($dossier->engagements_sollicites_total === null) {
            return $direction;
        }

        $total = (float) $dossier->engagements_sollicites_total;

        $rules = DelegationPouvoir::query()
            ->orderBy('seuil_engagements_max')
            ->get();

        if ($rules->isEmpty()) {
            return $direction;
        }

        foreach ($rules as $rule) {
            if ($total <= (float) $rule->seuil_engagements_max) {
                return array_values(array_unique(array_merge([(int) $rule->profil_id], $direction)));
            }
        }

        return $direction;
    }

    /**
     * @return list<int>
     */
    public function defaultDirectionProfilIds(): array
    {
        return array_values(array_unique([
            (int) config('angara.role_dg', 4),
            (int) config('angara.role_dga', 5),
        ]));
    }

    public function userCanCloseInstruction(?User $user, Dossier $dossier): bool
    {
        if (! $user || $dossier->isInstructionClosed()) {
            return false;
        }

        $allowed = $this->authorizedProfilIdsForDossier($dossier);

        return in_array((int) $user->role_id, $allowed, true);
    }

    public function userCanRejectInstruction(?User $user, Dossier $dossier): bool
    {
        return $this->userCanCloseInstruction($user, $dossier);
    }

    public function isDirectionRole(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        $ids = $this->defaultDirectionProfilIds();

        return in_array((int) $user->role_id, $ids, true);
    }

    /**
     * Libellé métier pour l’interface (seuil + profil ciblé), utilisé pour la clôture du dossier d’instruction.
     * Le DG/DGA conserve toujours le pouvoir de clôture (mention systématique en complément du profil délégué).
     */
    public function describeRuleForInstructionClosure(Dossier $dossier): string
    {
        if ($dossier->engagements_sollicites_total === null) {
            return 'Total des engagements sollicités non renseigné sur ce dossier : seuls le DG et le DGA peuvent valider ou rejeter la clôture, jusqu’à mise à jour métier.';
        }

        $total = (float) $dossier->engagements_sollicites_total;
        $rules = DelegationPouvoir::query()->orderBy('seuil_engagements_max')->get();
        if ($rules->isEmpty()) {
            return 'Aucun paramétrage : seuls le DG et le DGA peuvent valider ou rejeter la clôture.';
        }
        foreach ($rules as $rule) {
            if ($total <= (float) $rule->seuil_engagements_max) {
                $name = $rule->profil?->name ?? ('Profil #'.$rule->profil_id);

                return 'Seuil applicable : total engagements sollicités ≤ '.number_format((float) $rule->seuil_engagements_max, 0, ',', ' ')
                    .' XAF — profil habilité : '.$name.' (le DG et le DGA conservent toujours le pouvoir de clôture en dernier ressort).';
            }
        }

        return 'Total au-delà des seuils paramétrés : validation/rejet de la clôture réservé au DG et au DGA.';
    }

    /**
     * @return 'valide'|'rejete'|'en_cours'
     */
    public function instructionClosureStatutKey(Dossier $dossier): string
    {
        if ($dossier->instruction_closure_validated_at) {
            return 'valide';
        }
        if ($dossier->isInstructionClosureRejected()) {
            return 'rejete';
        }

        return 'en_cours';
    }

    public function instructionClosureStatutLabel(Dossier $dossier): string
    {
        return match ($this->instructionClosureStatutKey($dossier)) {
            'valide' => 'Dossier d’instruction clos',
            'rejete' => 'Clôture instruction rejetée',
            default => 'Instruction en cours',
        };
    }
}
