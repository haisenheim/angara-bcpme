<?php

namespace App\Services\Inertia;

use App\Models\Arrondissement;
use App\Models\ElementConstitutif;
use App\Models\Entreprise;
use App\Models\Service;
use Carbon\Carbon;

/**
 * Actions de la fiche entreprise / prospect du gestionnaire (anciennes modales Blade), au format
 * ActionDialog : soumission du prospect, avis par critère, sites, équipe, appuis, pièces constitutives.
 *
 * Les droits sont ceux des contrôleurs cibles : seul le gestionnaire propriétaire (gestionnaire_id
 * ou user_id) peut modifier la fiche ; les autres ne reçoivent aucune action.
 */
class GestionnaireEntrepriseActions
{
    public const NIVEAUX_ETUDE = ['Supérieur', 'Secondaire', 'Primaire', 'Sans niveau'];

    public function isOwner(Entreprise $item): bool
    {
        return $item->isManagedBy(auth()->id());
    }

    /**
     * Complète les props de la fiche (EntrepriseSheetPresenter::present) avec les actions du gestionnaire.
     */
    public function decorate(Entreprise $item, array $sheet): array
    {
        $sheet['workflowActions'] = [];
        $sheet['sectionActions'] = [];
        if (! $this->isOwner($item)) {
            return $sheet;
        }

        $token = $item->token;

        if ($item->prospect && ! $item->prospect_submitted_at) {
            $sheet['workflowActions'][] = [
                'key' => 'submit-prospect', 'label' => 'Soumettre pour avis', 'icon' => 'send', 'variant' => 'success',
                'title' => 'Soumettre le prospect',
                'description' => 'Le prospect sera transmis aux responsables juridique et conformité pour avis. La fiche ne sera plus modifiable par le formulaire de complétion.',
                'fields' => [],
                'submits' => [['label' => 'Soumettre', 'variant' => 'success', 'url' => route('gestionnaire.entreprises.prospects.submit', $token)]],
            ];
        }

        if (! $item->prospect) {
            $sheet['workflowActions'][] = [
                'key' => 'appui', 'label' => 'Ajouter un appui', 'icon' => 'add_card', 'variant' => 'secondary',
                'title' => 'Ajouter un appui',
                'fields' => [[
                    'name' => 'appui_id', 'label' => 'Appui', 'type' => 'select', 'required' => true,
                    'options' => Service::query()->orderBy('name')->get(['id', 'name', 'financier'])
                        ->map(fn ($s) => ['value' => $s->id, 'label' => $s->name.' ('.($s->financier ? 'financier' : 'non financier').')'])->values()->all(),
                ]],
                'submits' => [['label' => 'Enregistrer', 'variant' => 'primary', 'url' => route('gestionnaire.entreprise.appui.save', ['entreprise_id' => $item->id])]],
            ];
            $sheet['workflowActions'][] = [
                'key' => 'element', 'label' => 'Ajouter une pièce constitutive', 'icon' => 'upload_file', 'variant' => 'secondary',
                'title' => 'Ajouter une pièce constitutive',
                'fields' => [
                    ['name' => 'type_id', 'label' => 'Type de pièce', 'type' => 'select', 'required' => true,
                        'options' => ElementConstitutif::query()->where('active', 1)->orderBy('name')->get(['id', 'name'])
                            ->map(fn ($e) => ['value' => $e->id, 'label' => $e->name])->values()->all()],
                    ['name' => 'fichier', 'label' => 'Fichier', 'type' => 'file', 'required' => true],
                ],
                'submits' => [['label' => 'Enregistrer', 'variant' => 'primary', 'url' => route('gestionnaire.entreprise.element.save', ['entreprise_id' => $item->id])]],
            ];
        }

        // Sites
        $communes = Arrondissement::query()->with('departement:id,name')->orderBy('name')->get(['id', 'name', 'departement_id'])
            ->map(fn ($a) => ['value' => $a->id, 'label' => $a->name.($a->departement ? ' ('.$a->departement->name.')' : '')])->values()->all();
        $sites = $item->sites->keyBy('id');
        $sheet['sectionActions']['sites'] = [$this->siteAction($token, $communes)];
        $sheet['sites'] = collect($sheet['sites'])->map(function ($row) use ($sites, $token, $communes) {
            $site = $sites->get($row['id']);
            $row['actions'] = $site ? [
                $this->siteAction($token, $communes, $site),
                $this->deleteAction('site-del-'.$site->id, 'Supprimer le site', 'Supprimer le site « '.$site->libelle.' » ?',
                    route('gestionnaire.entreprises.sites.destroy', [$token, $site->id])),
            ] : [];

            return $row;
        })->values()->all();

        // Équipe
        $siteOptions = $item->sites->map(fn ($s) => ['value' => $s->id, 'label' => $s->libelle])->values()->all();
        $membres = $item->equipeMembres->keyBy('id');
        $sheet['sectionActions']['equipe'] = [$this->membreAction($token, $siteOptions)];
        $sheet['equipe'] = collect($sheet['equipe'])->map(function ($row) use ($membres, $token, $siteOptions) {
            $m = $membres->get($row['id']);
            $row['actions'] = $m ? [
                $this->membreAction($token, $siteOptions, $m),
                $this->deleteAction('membre-del-'.$m->id, 'Supprimer le membre', 'Supprimer « '.trim($m->nom.' '.$m->prenom).' » de l\'équipe ?',
                    route('gestionnaire.entreprises.equipe.destroy', [$token, $m->id])),
            ] : [];

            return $row;
        })->values()->all();

        // Avis du gestionnaire par critère (prospect et client).
        $avisMap = ($item->critereAvis ?? collect())->keyBy('critere_id');
        $sheet['questionnaire'] = collect($sheet['questionnaire'])->map(function ($q) use ($token, $avisMap) {
            if (! empty($q['critereId'])) {
                $current = $avisMap->get($q['critereId']);
                $q['actions'] = [[
                    'key' => 'avis-critere-'.$q['critereId'],
                    'label' => $current?->avis ? 'Modifier mon avis' : 'Saisir mon avis', 'icon' => 'rate_review', 'variant' => 'secondary',
                    'title' => 'Avis du gestionnaire — '.$q['critere'], 'wide' => true,
                    'description' => 'Laisser le champ vide puis enregistrer supprime l\'avis de ce critère.',
                    'fields' => [['name' => 'avis', 'label' => 'Avis', 'type' => 'richtext', 'value' => $current?->avis ?? '']],
                    'submits' => [['label' => 'Enregistrer', 'variant' => 'primary',
                        'url' => route('gestionnaire.entreprises.prospects.avis-criteres.store', [$token, 'critere_id' => $q['critereId']])]],
                ]];
            }

            return $q;
        })->values()->all();

        return $sheet;
    }

    private function siteAction(string $token, array $communes, $site = null): array
    {
        $v = fn (string $k) => $site?->{$k} ?? '';

        return [
            'key' => $site ? 'site-edit-'.$site->id : 'site-add',
            'label' => $site ? 'Modifier' : 'Ajouter un site', 'icon' => $site ? 'edit' : 'add_location_alt', 'variant' => 'secondary',
            'title' => $site ? 'Modifier le site' : 'Ajouter un site', 'wide' => true,
            'fields' => [
                ['name' => 'libelle', 'label' => 'Nom / libellé', 'type' => 'text', 'required' => true, 'value' => $v('libelle')],
                ['name' => 'arrondissement_id', 'label' => 'Commune', 'type' => 'select', 'required' => true, 'options' => $communes,
                    'value' => $site?->arrondissement_id ? (int) $site->arrondissement_id : ''],
                ['name' => 'village_ou_quartier', 'label' => 'Village / quartier', 'type' => 'text', 'value' => $v('village_ou_quartier')],
                ['name' => 'latitude', 'label' => 'Latitude', 'type' => 'text', 'value' => $v('latitude')],
                ['name' => 'longitude', 'label' => 'Longitude', 'type' => 'text', 'value' => $v('longitude')],
                ['name' => 'telephone', 'label' => 'Téléphone', 'type' => 'tel', 'value' => $v('telephone')],
                ['name' => 'email', 'label' => 'E-mail', 'type' => 'email', 'value' => $v('email')],
                ['name' => 'divers', 'label' => 'Divers', 'type' => 'richtext', 'value' => $v('divers')],
            ],
            'submits' => [$site
                ? ['label' => 'Enregistrer', 'variant' => 'primary', 'method' => 'put', 'url' => route('gestionnaire.entreprises.sites.update', [$token, $site->id])]
                : ['label' => 'Ajouter', 'variant' => 'primary', 'url' => route('gestionnaire.entreprises.sites.store', $token)]],
        ];
    }

    private function membreAction(string $token, array $siteOptions, $m = null): array
    {
        $v = fn (string $k) => $m?->{$k} ?? '';
        $d = fn (string $k) => $m?->{$k} ? Carbon::parse($m->{$k})->format('Y-m-d') : '';

        return [
            'key' => $m ? 'membre-edit-'.$m->id : 'membre-add',
            'label' => $m ? 'Modifier' : 'Ajouter un membre', 'icon' => $m ? 'edit' : 'person_add', 'variant' => 'secondary',
            'title' => $m ? 'Modifier le membre' : 'Ajouter un membre de l\'équipe', 'wide' => true,
            'fields' => array_values(array_filter([
                ['name' => 'nom', 'label' => 'Nom', 'type' => 'text', 'required' => true, 'value' => $v('nom')],
                ['name' => 'prenom', 'label' => 'Prénom', 'type' => 'text', 'value' => $v('prenom')],
                ['name' => 'telephone', 'label' => 'Téléphone', 'type' => 'tel', 'required' => true, 'value' => $v('telephone')],
                ['name' => 'email', 'label' => 'E-mail', 'type' => 'email', 'value' => $v('email')],
                ['name' => 'date_naissance', 'label' => 'Date de naissance', 'type' => 'date', 'value' => $d('date_naissance')],
                ['name' => 'fonction', 'label' => 'Fonction', 'type' => 'text', 'value' => $v('fonction')],
                ['name' => 'niveau_etude', 'label' => 'Niveau d\'étude', 'type' => 'select', 'value' => $v('niveau_etude'),
                    'options' => array_map(fn ($n) => ['value' => $n, 'label' => $n], self::NIVEAUX_ETUDE)],
                ['name' => 'specialite', 'label' => 'Spécialité', 'type' => 'text', 'value' => $v('specialite')],
                $siteOptions ? ['name' => 'entreprise_site_id', 'label' => 'Site d\'affectation', 'type' => 'select', 'options' => $siteOptions,
                    'value' => $m?->entreprise_site_id ? (int) $m->entreprise_site_id : ''] : null,
                ['name' => 'associe', 'label' => 'Associé', 'type' => 'checkbox', 'hint' => 'Associé de l\'entreprise', 'value' => $m?->associe ? 1 : 0],
                ['name' => 'dirigeant', 'label' => 'Dirigeant', 'type' => 'checkbox', 'hint' => 'Dirigeant de l\'entreprise', 'value' => $m?->dirigeant ? 1 : 0],
                ['name' => 'cni_numero', 'label' => 'Numéro CNI', 'type' => 'text', 'value' => $v('cni_numero')],
                ['name' => 'cni_expire_at', 'label' => 'Date d\'expiration CNI', 'type' => 'date', 'value' => $d('cni_expire_at')],
                ['name' => 'cni_fichier', 'label' => 'Fichier CNI (image ou PDF)', 'type' => 'file', 'accept' => '.pdf,.jpg,.jpeg,.png'],
                ['name' => 'divers', 'label' => 'Divers', 'type' => 'richtext', 'value' => $v('divers')],
            ])),
            'submits' => [$m
                ? ['label' => 'Enregistrer', 'variant' => 'primary', 'method' => 'put', 'url' => route('gestionnaire.entreprises.equipe.update', [$token, $m->id])]
                : ['label' => 'Ajouter', 'variant' => 'primary', 'url' => route('gestionnaire.entreprises.equipe.store', $token)]],
        ];
    }

    private function deleteAction(string $key, string $title, string $confirm, string $url): array
    {
        return [
            'key' => $key, 'label' => 'Supprimer', 'icon' => 'delete', 'variant' => 'ghost-danger',
            'title' => $title, 'description' => $confirm, 'fields' => [],
            'submits' => [['label' => 'Supprimer', 'variant' => 'danger', 'method' => 'delete', 'url' => $url]],
        ];
    }
}
