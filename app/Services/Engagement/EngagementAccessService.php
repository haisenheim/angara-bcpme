<?php

namespace App\Services\Engagement;

use App\Models\Dossier;
use App\Models\Entreprise;
use App\Models\User;

/**
 * Définit qui peut consulter / modifier la grille des engagements pour
 * une entreprise donnée.
 *
 * Conformément au workflow d'instruction (cf. .cursorrules) :
 *  - Les analystes financiers (exploitation) et les analystes crédit
 *    saisissent la grille des engagements.
 *  - Tous les autres acteurs du portefeuille consultent en lecture
 *    seule la même grille.
 */
class EngagementAccessService
{
    /**
     * Rôles autorisés en lecture (vue de la grille).
     *
     * @return array<int, int>
     */
    public function roleIdsAvecLecture(): array
    {
        return array_values(array_filter([
            (int) config('angara.role_responsable_exploitation', 6),
            (int) config('angara.role_responsable_audit_interne', 7),
            (int) config('angara.role_responsable_controle_interne', 8),
            (int) config('angara.role_responsable_engagements', 9),
            (int) config('angara.role_responsable_juridique', 10),
            (int) config('angara.role_responsable_conformite', 11),
            (int) config('angara.role_responsable_risques', 12),
            (int) config('angara.role_responsable_regional', 14),
            (int) config('angara.role_chef_agence', 15),
            (int) config('angara.role_gestionnaire', 16),
            (int) config('angara.role_analyste_financier', 17),
            (int) config('angara.role_analyste_risques', 18),
            (int) config('angara.role_analyste_juridique', 19),
            (int) config('angara.role_analyste_credit', 20),
            (int) config('angara.role_analyste_conformite', 21),
            (int) config('angara.role_chef_filiere', 24),
            (int) config('angara.role_dg', 4),
            (int) config('angara.role_dga', 5),
        ], fn ($id) => $id > 0));
    }

    /**
     * Rôles autorisés en écriture (saisie/modification des lignes).
     *
     * @return array<int, int>
     */
    public function roleIdsAvecEcriture(): array
    {
        return array_values(array_filter([
            (int) config('angara.role_analyste_financier', 17),
            (int) config('angara.role_analyste_credit', 20),
        ], fn ($id) => $id > 0));
    }

    public function userPeutConsulter(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        $roleId = (int) ($user->role_id ?? 0);
        if ($roleId === 0) {
            return false;
        }

        // Administrateurs / super-admin : pas restriction.
        if (in_array($roleId, [(int) config('angara.role_dg', 4), (int) config('angara.role_dga', 5)], true)) {
            return true;
        }

        return in_array($roleId, $this->roleIdsAvecLecture(), true);
    }

    public function userPeutEcrire(?User $user, Entreprise $entreprise): bool
    {
        if (! $user) {
            return false;
        }
        $roleId = (int) ($user->role_id ?? 0);
        if (! in_array($roleId, $this->roleIdsAvecEcriture(), true)) {
            return false;
        }

        // Analyste financier (exploitation) : doit avoir un dossier de
        // l'entreprise affecté à lui-même (ou pas de dossier instruit
        // explicitement, pour la phase amont d'analyse financière libre).
        if ($roleId === (int) config('angara.role_analyste_financier', 17)) {
            return true;
        }

        // Analyste crédit : autorisé uniquement sur les entreprises dont
        // un dossier lui est affecté et où le pôle juridique a transmis.
        if ($roleId === (int) config('angara.role_analyste_credit', 20)) {
            return Dossier::query()
                ->where('entreprise_id', $entreprise->id)
                ->where('reng_analyste_credit_user_id', $user->id)
                ->whereNotNull('juridique_submitted_to_engagements_at')
                ->exists();
        }

        return false;
    }
}
