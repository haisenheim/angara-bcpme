<?php

namespace App\Services\Inertia\Admin;

use App\Support\Inertia\Badge;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Briques de description des pages d'administration génériques (Admin/Crud, Admin/Fiche, Admin/Form).
 *
 * Une « action » est un objet décrit côté serveur, interprété par le composant Vue `ActionButtons` :
 *  - lien (GET) : ['label', 'icon', 'href'] ;
 *  - requête directe : ['label', 'request' => ['url', 'method', 'confirm']] (suppression, etc.) ;
 *  - formulaire : contrat ActionDialog (['title', 'fields', 'submits']).
 * Les règles métier (validation, périmètre) restent dans les contrôleurs existants.
 */
final class CrudKit
{
    /**
     * Champ de formulaire (contrat ActionDialog / FormFields).
     *
     * @param  array<string, mixed>  $extra  required, value, options, accept, hint, help, showIf, width
     */
    public static function field(string $name, string $label, string $type = 'text', array $extra = []): array
    {
        return array_merge(['name' => $name, 'label' => $label, 'type' => $type, 'required' => false, 'value' => ''], $extra);
    }

    /**
     * Options de liste déroulante à partir d'une collection (valeur => libellé).
     *
     * @param  iterable<int|string, string>  $pairs
     */
    public static function options(iterable $pairs, array $prepend = []): array
    {
        $opts = [];
        foreach ($prepend as $value => $label) {
            $opts[] = ['value' => (string) $value, 'label' => (string) $label];
        }
        foreach ($pairs as $value => $label) {
            $opts[] = ['value' => (string) $value, 'label' => (string) $label];
        }

        return $opts;
    }

    /** Action formulaire (ActionDialog) envoyée vers une route existante. */
    public static function dialog(string $key, string $label, string $title, array $fields, string $url, string $method = 'post', array $extra = []): array
    {
        return array_merge([
            'key' => $key,
            'label' => $label,
            'icon' => $extra['icon'] ?? ($method === 'post' ? 'add' : 'edit'),
            'variant' => $extra['variant'] ?? ($method === 'post' ? 'primary' : 'secondary'),
            'title' => $title,
            'description' => null,
            'wide' => false,
            'fields' => $fields,
            'submits' => [[
                'label' => $extra['submitLabel'] ?? 'Enregistrer',
                'url' => $url,
                'method' => $method,
                'variant' => 'primary',
            ]],
        ], array_diff_key($extra, ['submitLabel' => true]));
    }

    public static function link(string $key, string $label, string $href, string $icon = 'visibility', string $variant = 'secondary'): array
    {
        return ['key' => $key, 'label' => $label, 'icon' => $icon, 'variant' => $variant, 'href' => $href];
    }

    /** Requête directe (sans formulaire), avec confirmation facultative. */
    public static function request(string $key, string $label, string $url, string $method, ?string $confirm = null, string $icon = 'delete', string $variant = 'ghost-danger'): array
    {
        return ['key' => $key, 'label' => $label, 'icon' => $icon, 'variant' => $variant,
            'request' => ['url' => $url, 'method' => $method, 'confirm' => $confirm]];
    }

    public static function destroy(string $url, string $confirm, string $label = 'Supprimer'): array
    {
        return self::request('destroy', $label, $url, 'delete', $confirm);
    }

    /**
     * Activation / verrouillage via les routes GET existantes.
     */
    public static function toggle(bool $active, string $enableUrl, string $disableUrl, string $disableLabel = 'Verrouiller', string $enableLabel = 'Activer'): array
    {
        return $active
            ? self::link('disable', $disableLabel, $disableUrl, 'lock', 'ghost-danger')
            : self::link('enable', $enableLabel, $enableUrl, 'lock_open', 'success');
    }

    /** Badge d'état à partir de l'attribut `status` hérité (['name', 'color']). */
    public static function statusBadge(?array $status): array
    {
        return ['badge' => ucfirst((string) ($status['name'] ?? '—')), 'variant' => Badge::variant($status['color'] ?? null)];
    }

    public static function activeBadge(bool $active, string $on = 'Actif', string $off = 'Inactif'): array
    {
        return ['badge' => $active ? $on : $off, 'variant' => $active ? 'success' : 'neutral'];
    }

    public static function stat(string $label, int|float|string $value, string $tone = 'neutral'): array
    {
        return ['label' => $label, 'value' => is_numeric($value) ? number_format((float) $value, 0, ',', ' ') : $value, 'tone' => $tone];
    }

    /**
     * Les listes historiques non paginées (->get()) sont présentées sur une seule page.
     */
    public static function singlePage(Collection $items): LengthAwarePaginator
    {
        return new LengthAwarePaginator($items->values(), $items->count(), max(1, $items->count()), 1, [
            'path' => request()->url(),
        ]);
    }

    public static function fileSize(?int $bytes): ?string
    {
        if (! $bytes) {
            return null;
        }
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1, ',', ' ').' Mo';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1, ',', ' ').' Ko';
        }

        return $bytes.' o';
    }
}
