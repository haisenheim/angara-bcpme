<?php

namespace App\Support;

use App\Models\User;

/**
 * Préfixe des routes nommées selon le profil connecté,
 * pour les écrans « transverses » (simulateur, grille engagements, etc.) dont l’URL
 * ne passe pas par le préfixe métier (/gestionnaire/…, /analyste/…).
 */
final class UserWorkspaceContextResolver
{
    /**
     * Préfixe des noms de routes (ex. "gestionnaire" pour route("gestionnaire.dashboard")).
     */
    public static function routePrefix(?User $user): ?string
    {
        if ($user === null) {
            return null;
        }

        $rid = (int) $user->role_id;
        $cfg = static fn (string $key, int $default): int => (int) (config('angara.'.$key) ?? $default);

        return match (true) {
            $rid === 1 => 'admin',
            $rid === 2 => 'pca',
            $rid === 3 => 'administrateur',
            $rid === 4 => 'dg',
            $rid === 5 => 'dga',
            $rid === $cfg('role_responsable_exploitation', 6) => 'respexp',
            $rid === $cfg('role_responsable_audit_interne', 7) => 'respaud',
            $rid === $cfg('role_responsable_controle_interne', 8) => 'respci',
            $rid === $cfg('role_responsable_engagements', 9) => 'reng',
            $rid === $cfg('role_responsable_juridique', 10) => 'juridique',
            $rid === $cfg('role_responsable_conformite', 11) => 'conformite',
            $rid === $cfg('role_responsable_risques', 12) => 'rerx',
            $rid === $cfg('role_responsable_regional', 14) => 'regional',
            $rid === $cfg('role_chef_agence', 15) => 'ca',
            $rid === $cfg('role_gestionnaire', 16) => 'gestionnaire',
            $rid === $cfg('role_analyste_financier', 17) => 'analyste',
            $rid === $cfg('role_analyste_risques', 18) => 'analyste-risques',
            $rid === $cfg('role_analyste_juridique', 19) => 'analyste-juridique',
            $rid === $cfg('role_analyste_credit', 20) => 'analyste-credit',
            $rid === $cfg('role_analyste_conformite', 21) => 'analyste-conformite',
            $rid === $cfg('role_auditeur', 22) => 'auditeur',
            $rid === $cfg('role_controleur', 23) => 'controleur',
            $rid === $cfg('role_chef_filiere', 24) => 'chef-filiere',
            default => null,
        };
    }
}
