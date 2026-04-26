<?php

namespace App\Console\Commands;

use App\Models\OrganisationEntite;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class BackfillUserOrganisationCommand extends Command
{
    protected $signature = 'angara:backfill-user-organisation
                            {--dry-run : Ne rien enregistrer}
                            {--only-missing : Ne met à jour que les users sans organisation_type}
                            {--user-id= : Cible un user id spécifique}';

    protected $description = 'Renseigne organisation_type / organisation_entite_id des utilisateurs existants.';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');
        $onlyMissing = (bool) $this->option('only-missing');
        $targetUserId = $this->option('user-id');

        $entites = OrganisationEntite::query()->get()->keyBy('key');

        $roleToEntiteKey = $this->roleToEntiteKeyMap();

        $q = User::query()->where('role_id', '>', 1);
        if ($targetUserId !== null && $targetUserId !== '') {
            $q->whereKey((int) $targetUserId);
        }
        if ($onlyMissing) {
            $q->where(function (Builder $b) {
                $b->whereNull('organisation_type')->orWhere('organisation_type', '');
            });
        }

        $total = (clone $q)->count();
        $this->info('Users à analyser: '.$total);

        $updated = 0;
        $skipped = 0;
        $unknown = 0;

        $q->orderBy('id')->chunkById(200, function ($users) use ($dry, $entites, $roleToEntiteKey, &$updated, &$skipped, &$unknown) {
            /** @var User $user */
            foreach ($users as $user) {
                [$type, $agenceId, $entiteId] = $this->computeOrganisationForUser($user, $entites, $roleToEntiteKey);

                if ($type === null) {
                    $unknown++;
                    $this->warn('User #'.$user->id.' (role '.$user->role_id.') : organisation indéterminée');
                    continue;
                }

                $dirty = false;
                if ((string) ($user->organisation_type ?? '') !== (string) $type) {
                    $user->organisation_type = $type;
                    $dirty = true;
                }
                if ((int) ($user->agence_id ?? 0) !== (int) $agenceId) {
                    $user->agence_id = (int) $agenceId;
                    $dirty = true;
                }
                if ((int) ($user->organisation_entite_id ?? 0) !== (int) ($entiteId ?? 0)) {
                    $user->organisation_entite_id = $entiteId;
                    $dirty = true;
                }

                if (! $dirty) {
                    $skipped++;
                    continue;
                }

                $updated++;
                if (! $dry) {
                    $user->save();
                }
            }
        });

        $this->line('');
        $this->info('Mise à jour: '.$updated.($dry ? ' (dry-run)' : ''));
        $this->info('Inchangés: '.$skipped);
        $this->info('Indéterminés: '.$unknown);

        return self::SUCCESS;
    }

    /**
     * @return array<int, string> role_id => entite.key
     */
    private function roleToEntiteKeyMap(): array
    {
        // Gouvernance historique (middlewares)
        $rolePca = 2;
        $roleAdministrateur = 3;

        return [
            // Pôles (responsables)
            (int) config('angara.role_responsable_exploitation', 6) => 'pole_exploitation',
            (int) config('angara.role_responsable_juridique', 10) => 'pole_juridique',
            (int) config('angara.role_responsable_engagements', 9) => 'pole_engagements',
            (int) config('angara.role_responsable_risques', 12) => 'pole_risques',

            // Analystes rattachés à un pôle
            (int) config('angara.role_analyste_financier', 17) => 'pole_exploitation',
            (int) config('angara.role_analyste_juridique', 19) => 'pole_juridique',
            (int) config('angara.role_analyste_credit', 20) => 'pole_engagements',
            (int) config('angara.role_analyste_risques', 18) => 'pole_risques',

            // DG / DGA
            (int) config('angara.role_dg', 4) => 'direction_generale',
            (int) config('angara.role_dga', 5) => 'direction_generale',

            // Audit & contrôle
            (int) config('angara.role_responsable_audit_interne', 7) => 'audit_interne',
            (int) config('angara.role_responsable_controle_interne', 8) => 'controle_interne',
            (int) config('angara.role_auditeur', 22) => 'audit_interne',
            (int) config('angara.role_controleur', 23) => 'controle_interne',

            // Conseil d’administration
            $rolePca => 'conseil_administration',
            $roleAdministrateur => 'conseil_administration',
        ];
    }

    /**
     * @param  \Illuminate\Support\Collection<string, OrganisationEntite>  $entites
     * @param  array<int, string>  $roleToEntiteKey
     * @return array{0:?string,1:int,2:?int} organisation_type, agence_id, organisation_entite_id
     */
    private function computeOrganisationForUser(User $user, $entites, array $roleToEntiteKey): array
    {
        $roleId = (int) ($user->role_id ?? 0);
        $agenceId = (int) ($user->agence_id ?? 0);

        // Rôles agence: si une agence est renseignée, on force "agence"
        $rolesAgence = [
            (int) config('angara.role_gestionnaire', 16),
            (int) config('angara.role_chef_agence', 15),
            (int) config('angara.role_chef_filiere', 24),
            (int) config('angara.role_responsable_regional', 14),
        ];
        if (in_array($roleId, $rolesAgence, true) && $agenceId > 0) {
            return ['agence', $agenceId, null];
        }

        // Mapping entité
        $entiteKey = $roleToEntiteKey[$roleId] ?? null;
        if ($entiteKey !== null) {
            $entiteId = $entites->get($entiteKey)?->id;
            if ($entiteId) {
                return ['entite', 0, (int) $entiteId];
            }
        }

        // Fallback: agence si agence_id, sinon entité "exploitation" (par défaut)
        if ($agenceId > 0) {
            return ['agence', $agenceId, null];
        }

        $defaultEntiteId = $entites->get('pole_exploitation')?->id;
        if ($defaultEntiteId) {
            return ['entite', 0, (int) $defaultEntiteId];
        }

        return [null, $agenceId, null];
    }
}

