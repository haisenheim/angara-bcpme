<?php

namespace App\Support\Navigation;

use App\Models\User;
use App\Support\UserWorkspaceContextResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Menu latéral unique de la refonte : remplace les 18 layouts Blade.
 *
 * Chaque entrée déclare des routes candidates ; seules celles qui existent pour le préfixe
 * du profil connecté sont affichées. Ajouter une fonctionnalité = ajouter une entrée ici.
 */
final class WorkspaceNavigation
{
    /**
     * @return array<int, array{label: string, items: array<int, array<string, mixed>>}>
     */
    public static function for(User $user, Request $request): array
    {
        $p = UserWorkspaceContextResolver::routePrefix($user);
        if ($p === null) {
            return [];
        }

        $sections = [
            'Pilotage' => [
                self::item('Tableau de bord', 'dashboard', ["$p.dashboard"]),
                self::item('Tableaux de pilotage', 'monitoring', ['tdb.index'], ['tdb.*']),
            ],
            'Portefeuille' => [
                self::item('Prospects', 'person_search', [
                    "$p.entreprises.prospects", "$p.prospects.index", "$p.tous-prospects.index",
                ]),
                self::item(in_array($p, ['dg', 'dga'], true) ? 'Clients' : 'Entreprises', 'storefront', [
                    "$p.entreprises.index", "$p.clients.index",
                ], ["$p.entreprises.show", "$p.clients.show"]),
                self::item('Dossiers d\'instruction', 'folder_shared', [
                    "$p.dossiers.index", "$p.portefeuille.dossiers.index",
                ], ["$p.dossiers.show", "$p.v2.dossiers.*", "$p.portefeuille.dossiers.*"]),
            ],
            'Workflow' => [
                self::item('Prospects à valider', 'how_to_reg', ["$p.workflow.prospects.index"], ["$p.workflow.prospects.*"]),
                self::item('Structurations à valider', 'account_tree', ["$p.workflow.instructions.index"], ["$p.workflow.instructions.*"]),
                self::item('Dossiers à valider', 'fact_check', ["$p.workflow.instruction-dossiers.index"], ["$p.workflow.instruction-dossiers.*"]),
                self::item('Structurations', 'account_tree', ["$p.qualifications.index"], ["$p.qualifications.*"]),
                self::item('Instructions en attente', 'pending_actions', ["$p.instructions.pending"]),
                self::item('Instructions en cours', 'autorenew', ["$p.instructions.in-progress"]),
                self::item('Avis sur prospects', 'rate_review', $p === 'juridique' || $p === 'conformite' ? ["$p.prospects.index"] : [], ["$p.prospects.show"]),
                self::item('Avis rendus', 'task_alt', ["$p.prospects.treated"]),
            ],
            'Programmes' => [
                self::item('Programmes d\'appui', 'volunteer_activism', ["$p.programmes.index"], ["$p.programmes.*"]),
            ],
            'Administration' => [
                self::item('Utilisateurs', 'group', ["$p.users.index"], ["$p.users.*", "$p.user.*"]),
                self::item('Agences', 'apartment', ["$p.agences.index"], ["$p.agences.*"]),
                self::item('Territoire', 'map', ["$p.territoire"]),
                self::item('Secteurs', 'category', ["$p.secteurs.index"], ["$p.secteurs.*"]),
                self::item('Organismes', 'corporate_fare', ["$p.organismes.index"], ["$p.organismes.*"]),
                self::item('Banques', 'account_balance', ["$p.banques.index"], ["$p.banques.*"]),
                self::item('Pièces exigibles', 'checklist', ["$p.pieces-exigibles.index"], ["$p.pieces-exigibles.*"]),
                self::item('Types de fichiers', 'description', ["$p.fichiers-types.index"], ["$p.fichiers-types.*"]),
                self::item('Modèles de documents', 'draft', ["$p.document-templates.index"], ["$p.document-templates.*"]),
                self::item('Délégation de pouvoir', 'gavel', ["$p.delegation-pouvoirs.index"], ["$p.delegation-pouvoirs.*"]),
            ],
            'Ressources' => [
                self::item('Bibliothèque de documents', 'library_books', $p === 'admin' ? [] : ['document-templates.index']),
            ],
        ];

        $out = [];
        foreach ($sections as $label => $items) {
            $items = array_values(array_filter(array_map(
                fn (?array $item) => $item ? self::resolve($item, $request) : null,
                $items
            )));
            if ($items !== []) {
                $out[] = ['label' => $label, 'items' => $items];
            }
        }

        return $out;
    }

    /**
     * @param  array<int, string>  $candidates  routes possibles (la première existante est retenue)
     * @param  array<int, string>  $activePatterns  routes supplémentaires qui rendent l'entrée active
     */
    private static function item(string $label, string $icon, array $candidates, array $activePatterns = []): array
    {
        return compact('label', 'icon', 'candidates', 'activePatterns');
    }

    private static function resolve(array $item, Request $request): ?array
    {
        foreach ($item['candidates'] as $name) {
            if (! Route::has($name)) {
                continue;
            }

            return [
                'label' => $item['label'],
                'icon' => $item['icon'],
                'href' => route($name),
                'active' => $request->routeIs($name, ...$item['activePatterns']),
            ];
        }

        return null;
    }
}
