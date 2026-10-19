<?php

namespace App\Services\Inertia;

use App\Models\DossierEntreeRelation;
use App\Models\Entreprise;
use App\Support\Inertia\Badge;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

/**
 * Fiche entreprise / prospect / client (page Inertia Entreprises/Show), commune à tous les profils.
 *
 * Remplace le partial Blade `entreprise-fiche-client-ca-body` et ses sous-partials. Les écarts entre
 * profils (liens, actions) sont passés par l'appelant via $context.
 */
class EntrepriseSheetPresenter
{
    /** Relations nécessaires à la fiche. */
    public const RELATIONS = [
        'user', 'gestionnaire', 'forme', 'filiere', 'branche', 'produit', 'produits',
        'appuis.type', 'critereAvis.user', 'village', 'quartier', 'arrondissement', 'departement', 'region',
        'agence.representation', 'tiers.person', 'tiers.company.produit',
        'dossiers.programme', 'dossiers.instructionProgrammes.programme', 'dossiers.chefFiliereSubmittedToAgenceBy',
        'dossierEntreeRelation.qualificationUser', 'dossierEntreeRelation.programmesSubmittedBy',
        'dossierEntreeRelation.qualificationValidatedByAgenceUser', 'dossierEntreeRelation.instructionValidatedBy',
        'dossierEntreeRelation.programmeSelections.programme',
        'juridiqueAvisUser', 'conformiteAvisUser', 'promuClientUser', 'prospectRejectedUser',
        'sites.region', 'sites.departement', 'sites.arrondissement', 'equipeMembres.site', 'equipeMembres.cniFichier',
        'dossierAnalyseCritique.avis.emisPar',
    ];

    /**
     * @param  Collection  $mr  résultats du questionnaire (BuildsEntrepriseQuestionnaireResults)
     * @param  array{routePrefix: string, dossierShowRoute?: string, programmeShowRoute?: ?string, entrepriseShowRoute?: string}  $context
     */
    public function present(Entreprise $item, Collection $mr, Collection $checklist, array $context): array
    {
        $p = $context['routePrefix'];
        $dossierShow = $context['dossierShowRoute'] ?? "$p.dossiers.show";
        $programmeShow = $context['programmeShowRoute'] ?? (Route::has("$p.programmes.show") ? "$p.programmes.show" : null);
        $entrepriseShow = $context['entrepriseShowRoute'] ?? "$p.entreprises.show";

        return [
            'entreprise' => $this->header($item),
            'kpis' => [
                ['label' => 'Localisation', 'value' => trim(($item->arrondissement?->name ?? '—').($item->region ? ' / '.$item->region->name : ''))],
                ['label' => 'Contact', 'value' => $item->manager ?? '—', 'sub' => trim(($item->phone ?? '—').' · '.($item->email ?? '—'))],
                ['label' => 'Agence', 'value' => $item->agence?->name ?? '—'],
                ['label' => 'Produit principal', 'value' => $item->produit?->name ?? '—'],
            ],
            'sections' => $this->sections($item),
            'activites' => [
                'secondaires' => $item->produits->map(fn ($pr) => trim(($pr->code ? $pr->code.' ' : '').$pr->name))->values(),
                'appuisFinanciers' => $this->appuis($item, 1),
                'appuisNonFinanciers' => $this->appuis($item, 0),
            ],
            'sites' => $item->sites->map(fn ($s) => [
                'id' => $s->id,
                'libelle' => $s->libelle,
                'localite' => collect([$s->arrondissement?->name, $s->departement?->name, $s->region?->name])->filter()->implode(', '),
                'villageQuartier' => $s->village_ou_quartier,
                'gps' => $s->latitude && $s->longitude ? $s->latitude.', '.$s->longitude : null,
                'contact' => collect([$s->telephone, $s->email])->filter()->implode(' · ') ?: null,
                'divers' => $s->divers,
            ])->values(),
            'equipe' => $item->equipeMembres->map(fn ($m) => [
                'id' => $m->id,
                'nom' => trim(($m->nom ?? '').' '.($m->prenom ?? '')),
                'fonction' => $m->fonction,
                'age' => $m->date_naissance ? Carbon::parse($m->date_naissance)->age.' ans' : null,
                'niveau' => $m->niveau_etude,
                'specialite' => $m->specialite,
                'associe' => (bool) $m->associe,
                'dirigeant' => (bool) $m->dirigeant,
                'site' => $m->site?->libelle,
                'contact' => collect([$m->telephone, $m->email])->filter()->implode(' · ') ?: null,
                'cni' => $m->cniFichier?->path,
            ])->values(),
            'tiers' => [
                'physiques' => $item->tiers->where('person_id', '!=', 0)->filter(fn ($t) => $t->person)->map(fn ($t) => [
                    'name' => $t->person->name,
                    'lien' => $t->lien,
                    'contact' => collect([$t->person->phone, $t->person->email])->filter()->implode(' · ') ?: null,
                    'niu' => $t->person->niu,
                    'commentaire' => $t->commentaire,
                ])->values(),
                'morales' => $item->tiers->where('company_id', '!=', 0)->filter(fn ($t) => $t->company)->map(fn ($t) => [
                    'name' => $t->company->name,
                    'lien' => $t->lien,
                    'contact' => collect([$t->company->manager, $t->company->phone, $t->company->email])->filter()->implode(' · ') ?: null,
                    'statut' => $t->company->prospect ? 'Prospect' : 'Client',
                    'href' => $t->company->token && Route::has($entrepriseShow) ? route($entrepriseShow, $t->company->token) : null,
                    'commentaire' => $t->commentaire,
                ])->values(),
            ],
            'checklist' => $this->checklist($checklist),
            'questionnaire' => $this->questionnaire($item, $mr),
            'structuration' => $this->structuration($item),
            'dossiers' => $this->dossiers($item, $dossierShow, $programmeShow),
            'avis' => [
                ['title' => 'Avis juridique', 'at' => $this->dt($item->juridique_avis_at), 'by' => $item->juridiqueAvisUser?->name, 'html' => $item->juridique_avis],
                ['title' => 'Avis conformité', 'at' => $this->dt($item->conformite_avis_at), 'by' => $item->conformiteAvisUser?->name, 'html' => $item->conformite_avis],
            ],
        ];
    }

    /**
     * Checklist des pièces exigibles (Entreprise::piecesExigiblesChecklist()).
     */
    public function checklist(Collection $checklist): array
    {
        return $checklist->map(fn ($row) => [
            'id' => $row['definition']->id,
            'label' => $row['definition']->label,
            'description' => $row['definition']->description,
            'fourni' => (bool) $row['fourni'],
            'providedAt' => $row['entreprise_piece']?->provided_at ? Carbon::parse($row['entreprise_piece']->provided_at)->format('d/m/Y H:i') : null,
            'url' => $row['entreprise_piece']?->fichier?->path,
            'notes' => $row['entreprise_piece']?->notes,
        ])->values()->all();
    }

    private function header(Entreprise $item): array
    {
        return [
            'token' => $item->token,
            'name' => $item->name,
            'prospect' => (bool) $item->prospect,
            'taille' => $item->taille,
            'caractere' => $item->caractere,
            'forme' => $item->forme?->name,
            'agence' => $item->agence?->name,
            'gestionnaire' => $item->gestionnaire?->name ?? $item->user?->name,
            'statut' => Badge::fromPresentation($item->clientStatutPresentation()),
            // Prospect : date de soumission pour avis (null = brouillon).
            'submittedAt' => $item->prospect ? $this->dt($item->prospect_submitted_at) : null,
        ];
    }

    private function sections(Entreprise $item): array
    {
        $money = fn ($v) => ($v !== null && $v !== '') ? number_format((float) $v, 0, ',', ' ').' FCFA' : null;
        $num = fn ($v) => ($v !== null && $v !== '') ? number_format((float) $v, 0, ',', ' ') : null;
        $date = fn ($v) => $v ? Carbon::parse($v)->format('d/m/Y') : null;
        $villageQuartier = $item->village_ou_quartier
            ?? (trim(($item->village?->name ?? '').' '.($item->quartier?->name ?? '')) ?: null);

        return [
            ['title' => 'Identité de l\'entreprise', 'items' => [
                ['Dénomination', $item->name], ['RCCM', $item->rccm], ['NIU', $item->niu],
                ['N° employeur / assurance', $item->cnps], ['Forme juridique', $item->forme?->name],
                ['Taille', $item->taille], ['Caractère', $item->caractere], ['Système comptable', $item->systeme],
                ['Mobile Money', $item->mm_phone],
            ]],
            ['title' => 'Localisation et contacts', 'items' => [
                ['Région', $item->region?->name], ['Département', $item->departement?->name],
                ['Commune', $item->arrondissement?->name], ['Village ou quartier', $villageQuartier],
                ['Latitude', $item->latitude], ['Longitude', $item->longitude], ['Téléphone', $item->phone],
                ['E-mail', $item->email], ['Agence', $item->agence?->name], ['Direction', $item->agence?->representation?->name],
            ]],
            ['title' => 'Profil du dirigeant', 'items' => [
                ['Nom du dirigeant', $item->manager], ['Contact dirigeant', $item->manager_contact],
                ['Sexe', $item->manager_sexe], ['Niveau d\'instruction', $item->manager_niveau],
                ['Date de naissance', $date($item->manager_dtn)],
                ['Dirigeant promoteur', $item->manager_promoteur === null ? null : ($item->manager_promoteur ? 'Oui' : 'Non')],
            ]],
            ['title' => 'Structure et indicateurs', 'items' => [
                ['Date de création formelle', $date($item->dt_creation)], ['Début des activités', $date($item->dt_start)],
                ['Capital social', $money($item->capital)], ['Chiffre d\'affaires', $money($item->chiffre_affaire)],
                ['Ressources propres', $money($item->ressources_propres)], ['Total actif', $money($item->total_actif)],
                ['Nombre total de personnes', $num($item->nb_personnel)], ['Personnel permanent', $num($item->nb_personnel_permanent)],
                ['Personnel saisonnier', $num($item->nb_personnel_saisonier)],
                ['Type de personnel dominant', $item->tperso && $item->tperso !== 'xxx' ? ucfirst($item->tperso) : null],
            ]],
            ['title' => 'Activités', 'items' => [
                ['Produit principal', $item->produit ? trim(($item->produit->code ? $item->produit->code.' ' : '').$item->produit->name) : null],
                ['Ancienneté du produit principal', ($item->produit_year_start !== null && $item->produit_year_start !== '') ? $item->produit_year_start.' an(s)' : null],
                ['Filière', $item->filiere?->name], ['Branche', $item->branche?->name],
            ]],
        ];
    }

    private function appuis(Entreprise $item, int $financier): array
    {
        return $item->appuis->where('financier', $financier)
            ->map(fn ($s) => $s->name.($s->type ? ' ('.$s->type->name.')' : ''))
            ->values()->all();
    }

    private function questionnaire(Entreprise $item, Collection $mr): array
    {
        $avisMap = ($item->critereAvis ?? collect())->keyBy('critere_id');

        return $mr->map(function ($result) use ($avisMap) {
            $critereId = (int) ($result['critere']?->id ?? 0);
            $avis = $critereId ? $avisMap->get($critereId) : null;

            return [
                'critereId' => $critereId ?: null,
                'critere' => $result['critere']?->name ?? 'Critère',
                'groups' => collect($result['items'])->map(fn ($group) => [
                    'sousCritere' => $group['sous_critere']?->name ?? 'Sous-critère',
                    'reponses' => collect($group['items'])->map(fn ($rep) => [
                        'question' => $rep->question?->name ?? '—',
                        'choix' => $rep->choice?->name ?? '—',
                    ])->values(),
                ])->values(),
                'avisGestionnaire' => $avis && $avis->avis ? [
                    'html' => $avis->avis,
                    'by' => $avis->user?->name,
                    'at' => $this->dt($avis->saved_at ?? $avis->updated_at),
                ] : null,
            ];
        })->values()->all();
    }

    private function structuration(Entreprise $item): array
    {
        $eer = $item->dossierEntreeRelation;
        $status = Badge::fromPresentation(DossierEntreeRelation::clientStructurationPresentation($eer));

        $decision = null;
        if ($item->promu_client_at) {
            $decision = ['label' => 'Passage client validé par le chef d\'agence', 'variant' => 'success',
                'by' => $item->promuClientUser?->name, 'at' => $this->dt($item->promu_client_at)];
        } elseif ($item->prospect_rejected_at) {
            $decision = ['label' => 'Prospect refusé par le chef d\'agence', 'variant' => 'warning',
                'by' => $item->prospectRejectedUser?->name, 'at' => $this->dt($item->prospect_rejected_at)];
        }

        $analyseCritique = $this->analyseCritique($item);

        if (! $eer) {
            return ['status' => $status, 'decision' => $decision, 'started' => false, 'besoins' => [], 'analyseCritique' => $analyseCritique];
        }

        $sections = collect([
            'Analyse stratégique' => $eer->analyse_strategique,
            'Analyse opérationnelle' => $eer->analyse_operationnelle,
            'Éligibilité' => $eer->analyse_eligibilite,
            'Identification des besoins' => $eer->identification_besoins,
            'Notes' => $eer->qualification_notes,
        ])->filter(fn ($html) => trim(strip_tags((string) $html)) !== '')
            ->map(fn ($html, $label) => ['label' => $label, 'html' => $html])->values();

        return [
            'status' => $status,
            'decision' => $decision,
            'started' => (bool) $eer->qualification_completed_at,
            'events' => array_values(array_filter([
                $eer->qualification_completed_at ? ['label' => 'Structuration enregistrée', 'by' => $eer->qualificationUser?->name, 'at' => $this->dt($eer->qualification_completed_at)] : null,
                $eer->programmes_submitted_at ? ['label' => 'Soumise au chef d\'agence', 'by' => $eer->programmesSubmittedBy?->name, 'at' => $this->dt($eer->programmes_submitted_at)] : null,
                $eer->qualification_validated_by_agence_at ? ['label' => 'Validée par le chef d\'agence', 'by' => $eer->qualificationValidatedByAgenceUser?->name, 'at' => $this->dt($eer->qualification_validated_by_agence_at)] : null,
                $eer->qualification_rejected_by_agence_at ? ['label' => 'Refusée par le chef d\'agence', 'by' => null, 'at' => $this->dt($eer->qualification_rejected_by_agence_at)] : null,
            ])),
            'sections' => $sections,
            'programmes' => $eer->programmeSelections->map(fn ($sel) => $sel->programme?->name)->filter()->values(),
            // Besoins cochés par le chef de filière (colonnes booléennes).
            'besoins' => array_values(array_filter([
                $eer->besoin_financement ? 'Financement' : null,
                $eer->besoin_accompagnement ? 'Accompagnement' : null,
                $eer->besoin_structuration ? 'Structuration' : null,
            ])),
            'analyseCritique' => $analyseCritique,
        ];
    }

    /**
     * Dossier d'analyse critique de l'entreprise (synthèse et avis consolidés), null s'il n'existe pas.
     */
    public function analyseCritique(Entreprise $item): ?array
    {
        $ac = $item->dossierAnalyseCritique;
        if (! $ac) {
            return null;
        }

        return [
            'statut' => $ac->statut,
            'synthese' => $ac->synthese,
            'avis' => $ac->avis->map(fn ($a) => [
                'source' => $a->source_label ?? $a->source_type,
                'type' => $a->source_type,
                'etat' => $a->etat,
                'at' => $this->dt($a->emis_at),
                'by' => $a->emisPar?->name ?? 'Système',
                'contenu' => $a->contenu,
            ])->values()->all(),
        ];
    }

    private function dossiers(Entreprise $item, string $dossierShow, ?string $programmeShow): array
    {
        return $item->dossiers->sortByDesc('created_at')->map(function ($d) use ($dossierShow, $programmeShow) {
            $programmes = $d->instructionProgrammes->isNotEmpty()
                ? $d->instructionProgrammes->map(fn ($dip) => [
                    'name' => $dip->programme?->name ?? '—',
                    'financier' => number_format((float) $dip->budget_appui_financier, 0, ',', ' ').' FCFA',
                    'nonFinancier' => number_format((float) $dip->budget_appui_non_financier, 0, ',', ' ').' FCFA',
                ])->values()
                : collect([['name' => $d->programme?->name ?? '—', 'financier' => null, 'nonFinancier' => null,
                    'href' => $programmeShow && $d->programme?->token ? route($programmeShow, $d->programme->token) : null]]);

            return [
                'token' => $d->token,
                'programmes' => $programmes,
                'statut' => Badge::fromPresentation($d->instructionStatutPresentation()),
                'etape' => $d->currentOrganisationLabel(),
                'transmisLe' => $this->dt($d->chef_filiere_submitted_to_agence_at),
                'href' => Route::has($dossierShow) ? route($dossierShow, $d->token) : null,
            ];
        })->values()->all();
    }

    private function dt($value): ?string
    {
        return $value ? Carbon::parse($value)->format('d/m/Y H:i') : null;
    }
}
