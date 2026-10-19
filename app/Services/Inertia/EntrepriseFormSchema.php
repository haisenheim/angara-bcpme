<?php

namespace App\Services\Inertia;

use App\Models\Arrondissement;
use App\Models\Departement;
use App\Models\Entreprise;
use App\Models\Forme;
use App\Models\Produit;
use App\Models\Region;
use App\Models\Service;
use Carbon\Carbon;

/**
 * Description des formulaires entreprise / prospect / tiers (page Inertia Entreprises/Form et
 * composant SchemaForm). Les noms de champs sont ceux attendus par les actions POST existantes.
 */
class EntrepriseFormSchema
{
    private ?array $cache = null;

    public const NIVEAUX = ['Supérieur' => 'Supérieur', 'Secondaire' => 'Secondaire', 'Primaire' => 'Primaire', 'Sans niveau' => 'Sans niveau'];

    public const LIENS_PHYSIQUE = ['Familial', 'Professionnel', 'Associatif', 'Amical'];

    public const LIENS_MORALE = ['Client', 'Fournisseur', 'Banque'];

    public const LIENS_MORALE_SUGGESTIONS = ['Client', 'Fournisseur', 'Banque', 'Partenaire', 'Actionnaire', 'Filiale', 'Maison mère'];

    /**
     * Formulaire prospect du gestionnaire (création, complétion d'un brouillon, tiers personne morale « nouveau »).
     * Champs de CompanyController::validateProspectFields.
     */
    public function prospectSteps(?Entreprise $item = null, array $old = []): array
    {
        $v = $this->valueReader($item, $old);
        $loc = $this->localisationFields($item, $old, false);
        $tp = $old['type_personnel'] ?? ($item ? ($item->personnel_mixte ? 'mixte' : ($item->personnel_saisonier ? 'saisonier' : ($item->personnel_permanent ? 'permanent' : ''))) : '');

        return [
            ['key' => 'identite', 'title' => 'Identité', 'description' => 'Dénomination, références administratives et cadrage général.', 'fields' => [
                ['name' => 'name', 'label' => 'Dénomination', 'type' => 'text', 'required' => true, 'width' => 'full', 'value' => $v('name'),
                    'placeholder' => 'Raison sociale ou nom commercial', 'help' => 'Si vide, le libellé « Prospect (à compléter) » sera appliqué.'],
                ['name' => 'rccm', 'label' => 'Numéro RCCM', 'type' => 'text', 'value' => $v('rccm'), 'placeholder' => 'Ex. RC/YAO/2024/B/123'],
                ['name' => 'niu', 'label' => 'NIU', 'type' => 'text', 'value' => $v('niu'), 'placeholder' => 'Ex. M123456789012A'],
                ['name' => 'cnps', 'label' => 'Numéro employeur / assurance', 'type' => 'text', 'value' => $v('cnps')],
                ['name' => 'forme_id', 'label' => 'Forme juridique', 'type' => 'select', 'value' => $v('forme_id'), 'options' => $this->formes()],
                ['name' => 'taille', 'label' => 'Taille', 'type' => 'select', 'value' => $v('taille'), 'options' => $this->pairs(EntrepriseListPresenter::TAILLES)],
                ['name' => 'caractere', 'label' => 'Caractère', 'type' => 'select', 'value' => $v('caractere'), 'options' => $this->pairs(EntrepriseListPresenter::CARACTERES)],
                ['name' => 'systeme', 'label' => 'Système comptable', 'type' => 'radio', 'value' => $v('systeme') ?: 'Normal', 'options' => $this->pairs(['Normal' => 'Normal', 'Minimal' => 'Minimal'])],
                ['name' => 'mm_phone', 'label' => 'Mobile Money', 'type' => 'tel', 'value' => $v('mm_phone')],
            ]],
            ['key' => 'localisation', 'title' => 'Localisation', 'description' => 'Point de présence géographique et canaux de contact.', 'fields' => array_merge($loc, [
                ['name' => 'phone', 'label' => 'Téléphone', 'type' => 'tel', 'value' => $v('phone')],
                ['name' => 'email', 'label' => 'E-mail', 'type' => 'email', 'value' => $v('email')],
                ['name' => 'village_ou_quartier', 'label' => 'Village ou quartier', 'type' => 'text', 'value' => $v('village_ou_quartier')],
                ['name' => 'latitude', 'label' => 'Latitude', 'type' => 'text', 'value' => $v('latitude'), 'placeholder' => 'Ex. 3.8480'],
                ['name' => 'longitude', 'label' => 'Longitude', 'type' => 'text', 'value' => $v('longitude'), 'placeholder' => 'Ex. 11.5021'],
            ])],
            ['key' => 'dirigeant', 'title' => 'Dirigeant', 'description' => 'Contact et profil du responsable.', 'fields' => [
                ['name' => 'manager', 'label' => 'Nom du dirigeant', 'type' => 'text', 'required' => true, 'value' => $v('manager')],
                ['name' => 'manager_contact', 'label' => 'Contact dirigeant', 'type' => 'text', 'value' => $v('manager_contact')],
                ['name' => 'manager_sexe', 'label' => 'Sexe', 'type' => 'radio', 'value' => $v('manager_sexe'), 'options' => $this->pairs(['Homme' => 'Homme', 'Femme' => 'Femme'])],
                ['name' => 'manager_niveau', 'label' => 'Niveau d\'instruction', 'type' => 'select', 'value' => $v('manager_niveau'), 'options' => $this->pairs(self::NIVEAUX)],
                ['name' => 'manager_dtn', 'label' => 'Date de naissance', 'type' => 'date', 'value' => $this->date($v('manager_dtn')), 'max' => now()->subYears(18)->toDateString()],
                ['name' => 'manager_promoteur', 'label' => 'Le dirigeant est-il promoteur ?', 'type' => 'select',
                    'value' => array_key_exists('manager_promoteur', $old) ? (string) $old['manager_promoteur'] : ($item && $item->manager_promoteur !== null ? (string) (int) $item->manager_promoteur : ''),
                    'options' => $this->pairs(['1' => 'Oui', '0' => 'Non'])],
            ]],
            ['key' => 'structure', 'title' => 'Structure', 'description' => 'Capacité, calendrier d\'activité et indicateurs financiers.', 'fields' => [
                ['name' => 'dt_creation', 'label' => 'Date de création formelle', 'type' => 'date', 'value' => $this->date($v('dt_creation')), 'max' => now()->toDateString()],
                ['name' => 'dt_start', 'label' => 'Début des activités', 'type' => 'date', 'value' => $this->date($v('dt_start')), 'max' => now()->toDateString()],
                ['name' => 'capital', 'label' => 'Capital social (FCFA)', 'type' => 'number', 'value' => $v('capital')],
                ['name' => 'chiffre_affaire', 'label' => 'Chiffre d\'affaires (FCFA)', 'type' => 'number', 'value' => $v('chiffre_affaire')],
                ['name' => 'ressources_propres', 'label' => 'Ressources propres (FCFA)', 'type' => 'number', 'value' => $v('ressources_propres')],
                ['name' => 'total_actif', 'label' => 'Total actif (FCFA)', 'type' => 'number', 'value' => $v('total_actif')],
                ['name' => 'nb_personnel', 'label' => 'Nombre total de personnes', 'type' => 'number', 'step' => 1, 'value' => $v('nb_personnel')],
                ['name' => 'nb_personnel_permanent', 'label' => 'Personnel permanent', 'type' => 'number', 'step' => 1, 'value' => $v('nb_personnel_permanent')],
                ['name' => 'nb_personnel_saisonier', 'label' => 'Personnel saisonnier', 'type' => 'number', 'step' => 1, 'value' => $v('nb_personnel_saisonier')],
                ['name' => 'type_personnel', 'label' => 'Type de personnel dominant', 'type' => 'radio', 'width' => 'half', 'value' => $tp,
                    'options' => $this->pairs(['permanent' => 'Permanent', 'saisonier' => 'Saisonnier', 'mixte' => 'Mixte'])],
            ]],
            ['key' => 'activites', 'title' => 'Activités', 'description' => 'Produits ou services actuellement portés.', 'fields' => [
                ['name' => 'produit_id', 'label' => 'Produit principal', 'type' => 'choice', 'width' => 'half', 'value' => $v('produit_id'), 'options' => $this->produits()],
                ['name' => 'produit_year_start', 'label' => 'Ancienneté sur le produit principal (années)', 'type' => 'number', 'step' => 1, 'min' => 0, 'value' => $v('produit_year_start')],
                ['name' => 'autres', 'label' => 'Produits secondaires', 'type' => 'multichoice', 'width' => 'full',
                    'value' => $this->ids($old['autres'] ?? ($item ? $item->produits->pluck('id')->all() : [])), 'options' => $this->produits()],
            ]],
            ['key' => 'besoins', 'title' => 'Besoins', 'description' => 'Appuis souhaités pour accompagner le développement.', 'fields' => [
                ['name' => 'appuisf', 'label' => 'Appuis financiers souhaités', 'type' => 'multichoice', 'width' => 'half',
                    'value' => $this->ids($old['appuisf'] ?? ($item ? $item->appuis->where('financier', 1)->pluck('id')->all() : [])), 'options' => $this->services(1)],
                ['name' => 'appuisnf', 'label' => 'Appuis non financiers souhaités', 'type' => 'multichoice', 'width' => 'half',
                    'value' => $this->ids($old['appuisnf'] ?? ($item ? $item->appuis->where('financier', 0)->pluck('id')->all() : [])), 'options' => $this->services(0)],
            ]],
        ];
    }

    /**
     * Fiche entreprise complète (création admin / analyste, édition d'un client par le gestionnaire).
     * Les listes de produits et d'appuis sont envoyées en chaînes séparées par des virgules (format historique).
     */
    public function entrepriseSteps(?Entreprise $item = null, bool $withAppuis = true): array
    {
        $v = $this->valueReader($item, []);
        $tp = $item ? ($item->personnel_mixte ? 'mixte' : ($item->personnel_saisonier ? 'saisonier' : 'permanent')) : 'permanent';

        $steps = [
            ['key' => 'identification', 'title' => 'Identification', 'fields' => [
                ['name' => 'rccm', 'label' => 'N° Registre de commerce', 'type' => 'text', 'required' => ! $item, 'value' => $v('rccm')],
                ['name' => 'niu', 'label' => 'N° d\'identifiant unique (NIU)', 'type' => 'text', 'required' => true, 'value' => $v('niu')],
                ['name' => 'cnps', 'label' => 'N° employeur ou assurance volontaire', 'type' => 'text', 'required' => true, 'value' => $v('cnps')],
                ['name' => 'name', 'label' => 'Dénomination', 'type' => 'text', 'required' => true, 'width' => 'half', 'value' => $v('name')],
                ['name' => 'mm_phone', 'label' => 'Numéro principal Mobile Money', 'type' => 'tel', 'required' => ! $item, 'value' => $v('mm_phone')],
                ['name' => 'taille', 'label' => 'Type d\'entreprise', 'type' => 'select', 'required' => true, 'value' => $v('taille'), 'placeholder' => 'Choisir…', 'options' => $this->pairs(EntrepriseListPresenter::TAILLES)],
                ['name' => 'caractere', 'label' => 'Caractère', 'type' => 'radio', 'value' => $v('caractere') ?: 'Formel', 'options' => $this->pairs(EntrepriseListPresenter::CARACTERES)],
                ['name' => 'forme_id', 'label' => 'Forme juridique', 'type' => 'select', 'required' => true, 'value' => $v('forme_id'), 'placeholder' => 'Choisir…', 'options' => $this->formes()],
                ['name' => 'systeme', 'label' => 'Système comptable', 'type' => 'radio', 'value' => $v('systeme') ?: 'Normal', 'options' => $this->pairs(['Normal' => 'Normal', 'Minimal' => 'Minimal'])],
                ['name' => 'capital', 'label' => 'Capital social (FCFA)', 'type' => 'number', 'required' => true, 'value' => $v('capital')],
                ['name' => 'dt_creation', 'label' => 'Date de création formelle', 'type' => 'date', 'required' => true, 'value' => $this->date($v('dt_creation'))],
                ['name' => 'dt_start', 'label' => 'Date de début des activités', 'type' => 'date', 'required' => true, 'value' => $this->date($v('dt_start'))],
                ['name' => 'ressources_propres', 'label' => 'Ressources propres (FCFA)', 'type' => 'number', 'required' => true, 'value' => $v('ressources_propres')],
                ['name' => 'total_actif', 'label' => 'Total actif (FCFA)', 'type' => 'number', 'required' => true, 'value' => $v('total_actif')],
                ['name' => 'nb_personnel', 'label' => 'Nombre d\'employés', 'type' => 'number', 'step' => 1, 'required' => true, 'value' => $v('nb_personnel')],
                ['name' => 'type_personnel', 'label' => 'Type de personnel', 'type' => 'radio', 'width' => 'half', 'value' => $tp,
                    'options' => $this->pairs(['permanent' => 'Permanent', 'saisonier' => 'Saisonnier', 'mixte' => 'Mixte'])],
            ]],
            ['key' => 'dirigeant', 'title' => 'Dirigeant', 'fields' => [
                ['name' => 'manager', 'label' => 'Nom et prénom du dirigeant', 'type' => 'text', 'required' => true, 'width' => 'half', 'value' => $v('manager')],
                ['name' => 'manager_sexe', 'label' => 'Sexe', 'type' => 'radio', 'value' => $v('manager_sexe') ?: 'Homme', 'options' => $this->pairs(['Homme' => 'Homme', 'Femme' => 'Femme'])],
                ['name' => 'manager_contact', 'label' => 'Contact du dirigeant', 'type' => 'text', 'required' => ! $item, 'value' => $v('manager_contact')],
                ['name' => 'manager_niveau', 'label' => 'Niveau d\'instruction', 'type' => 'select', 'required' => true, 'placeholder' => 'Choisir…', 'value' => $v('manager_niveau'), 'options' => $this->pairs(self::NIVEAUX)],
                ['name' => 'manager_dtn', 'label' => 'Date de naissance', 'type' => 'date', 'required' => true, 'value' => $this->date($v('manager_dtn'))],
                ['name' => 'manager_promoteur', 'label' => 'Le dirigeant est-il le promoteur ?', 'type' => 'radio',
                    'value' => $item && $item->manager_promoteur !== null ? (string) (int) $item->manager_promoteur : '1',
                    'options' => $this->pairs(['1' => 'Oui', '0' => 'Non'])],
            ]],
            ['key' => 'objet', 'title' => 'Objet social', 'fields' => array_values(array_filter([
                ['name' => 'produit_id', 'label' => 'Produit principal', 'type' => 'choice', 'width' => 'half', 'value' => $v('produit_id'), 'options' => $this->produits()],
                ['name' => 'produit_year_start', 'label' => 'Ancienneté dans le produit principal (années)', 'type' => 'number', 'step' => 1, 'value' => $v('produit_year_start')],
                $withAppuis ? ['name' => 'autres', 'label' => 'Produits / services secondaires', 'type' => 'multichoice', 'serialize' => 'csv', 'width' => 'full', 'value' => [], 'options' => $this->produits()] : null,
            ]))],
        ];
        if ($withAppuis) {
            $steps[] = ['key' => 'appuis', 'title' => 'Appuis sollicités', 'fields' => [
                ['name' => 'appuisf', 'label' => 'Appuis financiers', 'type' => 'multichoice', 'serialize' => 'csv', 'width' => 'half', 'value' => [], 'options' => $this->services(1)],
                ['name' => 'appuisnf', 'label' => 'Appuis non financiers', 'type' => 'multichoice', 'serialize' => 'csv', 'width' => 'half', 'value' => [], 'options' => $this->services(0)],
            ]];
        }
        $steps[] = ['key' => 'localisation', 'title' => 'Localisation / contact', 'fields' => array_merge($this->localisationFields($item, [], true), [
            ['name' => 'phone', 'label' => 'Téléphone', 'type' => 'tel', 'value' => $v('phone')],
            ['name' => 'email', 'label' => 'E-mail', 'type' => 'email', 'value' => $v('email')],
        ])];

        return $steps;
    }

    /** Tiers personne physique (saveTiersPhysique). */
    public function tiersPhysiqueSteps(): array
    {
        return [['key' => 'tiers', 'title' => 'Tiers personne physique', 'fields' => [
            ['name' => 'name', 'label' => 'Nom et prénom', 'type' => 'text', 'required' => true, 'width' => 'half'],
            ['name' => 'niu', 'label' => 'N° d\'identifiant unique (NIU)', 'type' => 'text', 'required' => true, 'width' => 'half'],
            ['name' => 'email', 'label' => 'E-mail', 'type' => 'email', 'required' => true, 'width' => 'half'],
            ['name' => 'phone', 'label' => 'Téléphone', 'type' => 'tel', 'required' => true, 'width' => 'half'],
            ['name' => 'address', 'label' => 'Adresse / domicile', 'type' => 'textarea', 'required' => true, 'width' => 'full'],
            ['name' => 'lien', 'label' => 'Lien avec le dirigeant principal', 'type' => 'radio', 'width' => 'full', 'value' => 'Familial',
                'options' => $this->pairs(array_combine(self::LIENS_PHYSIQUE, self::LIENS_PHYSIQUE))],
        ]]];
    }

    /** Tiers personne morale, formulaire simple admin / analyste (saveTiersMorale historique). */
    public function tiersMoraleSimpleSteps(): array
    {
        return [
            ['key' => 'entreprise', 'title' => 'Entreprise tierce', 'fields' => [
                ['name' => 'rccm', 'label' => 'N° Registre de commerce', 'type' => 'text'],
                ['name' => 'niu', 'label' => 'N° d\'identifiant unique (NIU)', 'type' => 'text'],
                ['name' => 'cnps', 'label' => 'N° employeur ou assurance volontaire', 'type' => 'text'],
                ['name' => 'name', 'label' => 'Dénomination', 'type' => 'text', 'required' => true, 'width' => 'half'],
                ['name' => 'produit_id', 'label' => 'Produit principal', 'type' => 'choice', 'width' => 'half', 'options' => $this->produits()],
                ['name' => 'capital', 'label' => 'Capital social (FCFA)', 'type' => 'number', 'required' => true],
                ['name' => 'taille', 'label' => 'Type d\'entreprise', 'type' => 'select', 'required' => true, 'placeholder' => 'Choisir…', 'options' => $this->pairs(EntrepriseListPresenter::TAILLES)],
                ['name' => 'forme_id', 'label' => 'Forme juridique', 'type' => 'select', 'required' => true, 'placeholder' => 'Choisir…', 'options' => $this->formes()],
                ['name' => 'caractere', 'label' => 'Caractère', 'type' => 'radio', 'value' => 'Formel', 'options' => $this->pairs(EntrepriseListPresenter::CARACTERES)],
                ['name' => 'systeme', 'label' => 'Système comptable', 'type' => 'radio', 'value' => 'Normal', 'options' => $this->pairs(['Normal' => 'Normal', 'Minimal' => 'Minimal'])],
                ['name' => 'phone', 'label' => 'Téléphone', 'type' => 'tel', 'required' => true],
                ['name' => 'email', 'label' => 'E-mail', 'type' => 'email', 'required' => true],
                ['name' => 'lien', 'label' => 'Nature de la relation', 'type' => 'radio', 'value' => 'Client', 'width' => 'half',
                    'options' => $this->pairs(array_combine(self::LIENS_MORALE, self::LIENS_MORALE))],
            ]],
            ['key' => 'dirigeant', 'title' => 'Dirigeant', 'fields' => [
                ['name' => 'manager', 'label' => 'Nom et prénom', 'type' => 'text', 'required' => true, 'width' => 'half'],
                ['name' => 'manager_sexe', 'label' => 'Sexe', 'type' => 'radio', 'value' => 'Homme', 'options' => $this->pairs(['Homme' => 'Homme', 'Femme' => 'Femme'])],
                ['name' => 'manager_contact', 'label' => 'Contact', 'type' => 'text', 'required' => true],
            ]],
        ];
    }

    /**
     * Région / département (aides à la saisie, non envoyés) puis commune (arrondissement_id).
     */
    private function localisationFields(?Entreprise $item, array $old, bool $required): array
    {
        $ref = $this->referentiel();
        $arrId = (string) ($old['arrondissement_id'] ?? $item?->arrondissement_id ?? '');
        $arr = $arrId !== '' ? collect($ref['arrondissements'])->firstWhere('value', $arrId) : null;
        $depId = $arr['parent'] ?? '';
        $dep = $depId !== '' ? collect($ref['departements'])->firstWhere('value', (string) $depId) : null;

        return [
            ['name' => '_region', 'label' => 'Région', 'type' => 'select', 'virtual' => true, 'placeholder' => 'Toutes', 'value' => (string) ($dep['parent'] ?? ''), 'options' => $ref['regions']],
            ['name' => '_departement', 'label' => 'Département', 'type' => 'select', 'virtual' => true, 'placeholder' => 'Tous', 'dependsOn' => '_region', 'value' => (string) $depId, 'options' => $ref['departements']],
            ['name' => 'arrondissement_id', 'label' => 'Commune', 'type' => 'select', 'required' => true, 'placeholder' => 'Choisir…', 'dependsOn' => '_departement', 'value' => $arrId, 'options' => $ref['arrondissements']],
        ];
    }

    private function referentiel(): array
    {
        return $this->cache ??= [
            'regions' => Region::query()->orderBy('name')->get(['id', 'name'])->map(fn ($r) => ['value' => (string) $r->id, 'label' => $r->name])->all(),
            'departements' => Departement::query()->orderBy('name')->get(['id', 'name', 'region_id'])->map(fn ($d) => ['value' => (string) $d->id, 'label' => $d->name, 'parent' => (string) $d->region_id])->all(),
            'arrondissements' => Arrondissement::query()->orderBy('name')->get(['id', 'name', 'departement_id'])->map(fn ($a) => ['value' => (string) $a->id, 'label' => $a->name, 'parent' => (string) $a->departement_id])->all(),
        ];
    }

    private function formes(): array
    {
        return Forme::query()->orderBy('name')->get(['id', 'name'])->map(fn ($f) => ['value' => (string) $f->id, 'label' => $f->name])->all();
    }

    private function produits(): array
    {
        return Produit::query()->orderBy('code')->orderBy('name')->get(['id', 'name', 'code'])
            ->map(fn ($p) => ['value' => (string) $p->id, 'label' => trim(($p->code ? $p->code.' ' : '').$p->name)])->all();
    }

    private function services(int $financier): array
    {
        return Service::query()->with('type')->where('financier', $financier)->orderBy('name')->get()
            ->map(fn ($s) => ['value' => (string) $s->id, 'label' => $s->name.($s->type ? ' ('.$s->type->name.')' : '')])->all();
    }

    private function pairs(array $map): array
    {
        return collect($map)->map(fn ($label, $value) => ['value' => (string) $value, 'label' => $label])->values()->all();
    }

    private function ids(array|string|null $ids): array
    {
        if (is_string($ids)) {
            $ids = $ids === '' ? [] : explode(',', $ids);
        }

        return collect($ids ?? [])->filter()->map(fn ($id) => (string) $id)->values()->all();
    }

    private function date($value): string
    {
        if (! $value) {
            return '';
        }
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            return (string) $value;
        }
    }

    private function valueReader(?Entreprise $item, array $old): \Closure
    {
        return function (string $key) use ($item, $old) {
            if (array_key_exists($key, $old)) {
                return $old[$key] ?? '';
            }
            $value = $item?->{$key};

            return $value === null ? '' : (is_bool($value) ? (string) (int) $value : (string) $value);
        };
    }
}
