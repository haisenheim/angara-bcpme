<?php

namespace App\Services\Inertia;

use App\Models\Dossier;
use App\Models\User;
use App\Services\DossierInstructionShowPresenter;
use App\Services\StructurationClosureService;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Route;
use Modules\Simulator\Persistence\Models\Scenario;

/**
 * Espaces de travail de la fiche dossier d'instruction (page Inertia Dossiers/Show, prop `workspace`)
 * et contenus d'instruction (pages Dossiers/Instruction et Dossiers/AnalyseCritique).
 *
 * Reprend les partials Blade RoleSpace/dossiers/partials/instruction_workspace_analyste,
 * instruction_workspace_ca, instruction_grille_notation, analyste_instruction_budgets_engagements_form,
 * partials/ca-instruction-agence-avis-saisie et ca-instruction-transmission-modals (mêmes endpoints POST,
 * mêmes conditions de verrouillage). Aucune règle métier côté Vue.
 */
class DossierWorkspacePresenter
{
    /** Zones historiques de la « grille d'analyse critique » (colonnes Dossier, séquence de setAnalyse). */
    public const LEGACY_ZONES = [
        1 => ['donnees_generales', '1. Informations générales', 'Brèves données générales actualisées sur l\'emprunteur'],
        2 => ['analyse_ensemble', '2. Analyse d\'ensemble', 'Analyse critique d\'ensemble'],
        3 => ['analyse_financiere', '3. Analyse financière', 'Analyse financière de l\'emprunteur'],
        4 => ['appuis', '4. Appuis financiers et non financiers', 'Appuis financiers et non-financiers proposés'],
        5 => ['analyse_risque', '5. Analyse du risque et de la capacité de remboursement', 'Analyse du risque et de la capacité de remboursement de l\'emprunteur'],
        6 => ['analyse_rentabilite', '6. Rentabilité de la relation pour l\'établissement', 'Rentabilité de la relation pour l’établissement'],
        7 => ['conclusions_analyste', '7. Conclusions motivées, recommandations de l\'analyste financier', 'Conclusions motivées, recommandations de l’Analyste Financier'],
    ];

    /** Consignes de rédaction affichées dans le dialogue de saisie (reprises du Blade). */
    public const LEGACY_ZONE_HINTS = [
        1 => 'Diagnostic des options stratégiques et politiques (vision du ou des promoteur(s), aspect juridique, répartition du capital, organigramme, administrateurs, équipe de direction, aspects économiques et commerciaux macro et méso, relations avec d\'autres institutions financières ou directions de l’établissement, principaux banquiers, auditeurs).',
        2 => 'Diagnostic opérationnel de l\'emprunteur, aspects non financiers (activités, processus de production, relations d’affaires, climat social, perspectives de développement à moyen terme, etc.).',
        3 => 'Passé récent, présent, futur : analyse du compte de résultats, diagnostic financier (bilan), diagnostic prévisionnel (états financiers prévisionnels).',
        4 => 'Type, objet, montant, syndication, validité des concours ; formes d\'utilisation, sous-limites ; modalités de remboursement ; garanties et protections.',
        5 => 'Risque global, spécifique, technique, juridique, de marché ; sorties des crédits.',
        6 => 'Conditions, commissions, retombées attendues, soldes moyens, commentaires ratios de couverture de risques.',
        7 => 'Conclusions motivées, recommandations de l’analyste financier.',
    ];

    public function __construct(
        private readonly DossierInstructionShowPresenter $showPresenter,
        private readonly DossierInstructionPagePresenter $page,
    ) {
    }

    /**
     * Props communes de la fiche Dossiers/Show pour les espaces hors RoleSpace
     * (analyste financier, chef d'agence, gestionnaire, administrateur, responsable régional, chef de filière).
     *
     * @param  array{route: string, label?: string, title: string}  $space
     * @param  array{store?: ?string, update?: ?string, destroy?: ?string, upload?: bool, manageOwn?: bool, lockWhenClosed?: bool}  $pieces
     */
    public function fiche(Dossier $d, array $space, User $user, bool $readonly, array $pieces, string $listRoute, string $listLabel, ?string $entrepriseRoute, bool $hub = true): array
    {
        $props = $this->page->present($d, $space, $user, $readonly, [], [
            'prefix' => $space['route'].'.dossier',
            'list' => $listRoute,
        ], [
            'pieces' => $pieces + ['lockWhenClosed' => false],
            'entrepriseRoute' => $entrepriseRoute,
            'hub' => $hub,
        ]);
        $props['fichierTypes'] = $props['links']['piecesStore'] || collect($props['pieces'])->contains(fn ($p) => $p['updateUrl'])
            ? \App\Models\FichierType::query()->orderBy('name')->get(['id', 'name'])
            : [];
        $props['workspace'] = null;
        $props['breadcrumb'] = [
            ['label' => 'Tableau de bord', 'href' => Route::has($space['route'].'.dashboard') ? route($space['route'].'.dashboard') : null],
            ['label' => $listLabel, 'href' => Route::has($listRoute) ? route($listRoute) : null],
            ['label' => $props['dossier']['reference']],
        ];

        return $props;
    }

    /**
     * Props de la page Dossiers/AnalyseCritique (grille d'analyse critique, sept zones, dossier d'analyse critique).
     *
     * @param  array{title: string, kicker?: string, intro?: ?string, notice?: ?array, zones?: array, zonesTitle?: ?string, legacy?: ?string, meta?: array,
     *     budgets?: bool, actions?: array, links?: array, showRoute: string, listRoute: string, dashboardRoute: string, listLabel?: string}  $o
     */
    public function analyseCritiquePage(Dossier $d, User $user, array $o): array
    {
        $d->loadMissing(['entreprise', 'instructionProgrammes.programme', 'fichiersDossier.type', 'fichiersDossier.uploadedBy']);
        $reference = 'DI-'.str_pad((string) $d->id, 5, '0', STR_PAD_LEFT);
        $showUrl = Route::has($o['showRoute']) ? route($o['showRoute'], $d->token) : null;

        return [
            'title' => $o['title'],
            'kicker' => $o['kicker'] ?? null,
            'dossier' => [
                'reference' => $reference,
                'entreprise' => $d->entreprise?->name,
                'programmes' => $d->programmesLabel() ?: null,
            ],
            'intro' => $o['intro'] ?? null,
            'notice' => $o['notice'] ?? null,
            'meta' => $o['meta'] ?? [],
            'budgets' => ($o['budgets'] ?? false) ? $d->instructionProgrammes->map(fn ($dip) => [
                'name' => $dip->programme?->name ?? '—',
                'financier' => $this->page->money($dip->budget_appui_financier),
                'nonFinancier' => $this->page->money($dip->budget_appui_non_financier),
            ])->values()->all() : [],
            'zonesTitle' => $o['zonesTitle'] ?? null,
            'zones' => $o['zones'] ?? [],
            'legacy' => $o['legacy'] ?? null,
            'pieces' => $this->page->pieces($d, $user, [
                'store' => null, 'update' => null, 'destroy' => null, 'upload' => false, 'manageOwn' => false, 'lockWhenClosed' => true,
            ]),
            'actions' => $o['actions'] ?? [],
            'links' => array_values(array_filter(array_merge($o['links'] ?? [], [
                $showUrl ? ['label' => 'Retour à la fiche dossier', 'href' => $showUrl, 'icon' => 'arrow_back'] : null,
            ]))),
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => Route::has($o['dashboardRoute']) ? route($o['dashboardRoute']) : null],
                ['label' => $o['listLabel'] ?? 'Dossiers d\'instruction', 'href' => Route::has($o['listRoute']) ? route($o['listRoute']) : null],
                ['label' => $reference, 'href' => $showUrl],
                ['label' => $o['kicker'] ?? $o['title']],
            ],
        ];
    }

    /**
     * Page Dossiers/Instruction (ancienne vue RoleSpace.dossiers.instruction_detail) : travail d'instruction en lecture seule.
     *
     * @param  array{route: string, title: string}  $space
     * @param  array{prefix: string, list: string}  $routes
     */
    public function instructionPage(Dossier $d, array $space, User $user, array $routes, array $n): array
    {
        $d->loadMissing(['analyste', 'entreprise', 'instructionProgrammes.programme']);
        $prefix = $routes['prefix'];
        $page = $this->analyseCritiquePage($d, $user, [
            'title' => 'Travail d’instruction (lecture seule)',
            'kicker' => 'Contenu d’instruction',
            'showRoute' => "$prefix.show",
            'listRoute' => $routes['list'],
            'dashboardRoute' => $space['route'].'.dashboard',
            'links' => array_values(array_filter([
                Route::has("$prefix.instruction.pdf") ? ['label' => 'Imprimer le dossier complet (PDF)', 'href' => route("$prefix.instruction.pdf", $d->token), 'icon' => 'print', 'external' => true] : null,
                Route::has("$prefix.dossier-analyse-critique") ? ['label' => 'Dossier d’analyse critique (avis analyste)', 'href' => route("$prefix.dossier-analyse-critique", $d->token), 'icon' => 'description'] : null,
                $d->entreprise?->token && Route::has($space['route'].'.entreprises.show') ? ['label' => 'Fiche entreprise', 'href' => route($space['route'].'.entreprises.show', $d->entreprise->token), 'icon' => 'storefront'] : null,
            ])),
        ]);

        $page['notice'] = [
            'variant' => 'neutral',
            'text' => ($space['route'] === 'juridique'
                ? 'Consultation réservée au pôle juridique — contenu transmis par l’analyste financier après validation exploitation.'
                : 'Consultation réservée au responsable exploitation après transmission du dossier par l’analyste financier ou par le chef d’agence.')
                .' Aucune modification n’est possible depuis cet écran.',
        ];
        $page['consultation'] = [
            'sollicite' => $this->page->money($d->engagements_sollicites_total),
            'enCours' => $this->page->money($d->engagements_en_cours_total),
            'programmes' => $d->instructionProgrammes->map(fn ($dip) => [
                'name' => $dip->programme?->name ?? '—',
                'financier' => $this->page->money((float) $dip->budget_appui_financier),
                'nonFinancier' => $this->page->money((float) $dip->budget_appui_non_financier),
                'total' => $this->page->money((float) $dip->budget_appui_financier + (float) $dip->budget_appui_non_financier),
            ])->values()->all(),
            'totaux' => $d->instructionProgrammes->isEmpty() ? null : [
                'financier' => $this->page->money($d->instructionProgrammes->sum(fn ($x) => (float) $x->budget_appui_financier)),
                'nonFinancier' => $this->page->money($d->instructionProgrammes->sum(fn ($x) => (float) $x->budget_appui_non_financier)),
                'general' => $this->page->money($d->instructionProgrammes->sum(fn ($x) => (float) $x->budget_appui_financier + (float) $x->budget_appui_non_financier)),
            ],
        ];
        $af = $this->afZones($d);
        $showAf = $d->isInstructionSubmittedToExploitation() && $d->hasExploitationAnalysteInstructionAvisSubstance();
        $page['zonesTitle'] = $showAf ? 'Analyse critique faite par '.($d->analyste?->name ?? 'l’analyste financier (non renseigné)') : null;
        $page['zonesIntro'] = $showAf ? 'Rubriques transmises au responsable exploitation avec le dossier.' : null;
        $page['zones'] = $showAf ? $af['zones'] : [];
        $page['legacy'] = $showAf ? $af['legacy'] : null;
        $caAvis = (string) ($d->instruction_agence_ca_avis ?? '');
        $page['caAvis'] = $d->isInstructionCaTransmittedToExploitation() && trim(strip_tags($caAvis)) !== '' ? $caAvis : null;
        $page['financier'] = [
            'indicateurs' => count($n['indicateurs'] ?? []),
            'sme' => $this->sme($n),
        ];
        $page['grille'] = $this->grille($d, $n, null);

        return $page;
    }

    /**
     * Méta du « dossier d'analyse critique » (partial dossier-analyse-critique-document).
     */
    public function analyseCritiqueDocument(Dossier $d, array $doc): array
    {
        $analyste = $doc['analyste'] ?? null;
        $af = $this->afZones($d);

        return [
            'meta' => [
                ['label' => 'Chargé d’instruction', 'value' => $analyste?->name ?? 'Non affecté ou non renseigné.',
                    'sub' => $analyste ? ($analyste->role?->name ?? 'Profil non renseigné') : null],
                ['label' => 'Dernière sauvegarde (brouillon / avis)',
                    'value' => $this->page->date($doc['saisies_meta']['edited_at'] ?? null, true) ?? 'Non renseignée.'],
            ],
            'notice' => ($doc['has_analyste_document'] ?? false) ? null : [
                'variant' => 'warning',
                'text' => 'Aucun contenu d’avis chargé d’instruction n’est encore disponible pour ce dossier (rubriques vides et avis non compilé).',
            ],
            'zonesTitle' => 'Analyse critique faite par '.($d->analyste?->name ?? 'l’analyste financier (non renseigné)'),
            'zones' => $af['zones'],
            'legacy' => $af['legacy'],
        ];
    }

    /** Données de notation (DossierInstructionShowPresenter), calculées une fois par requête. */
    public function notation(Dossier $dossier): array
    {
        return $this->showPresenter->presentForDossier($dossier);
    }

    /**
     * Grille de notation corporate (partial instruction_grille_notation).
     *
     * @param  array{choicesUrl: string, reponseUrl: string, sendProgramme?: bool}|null  $edit  null = lecture seule
     */
    public function grille(Dossier $d, array $n, ?array $edit, ?string $emptyText = null): array
    {
        $criteres = $n['criteres'] ?? [];
        $groups = [];
        foreach ([0, 1] as $i) {
            if (isset($criteres[$i])) {
                $groups[] = $this->critereGroup($criteres[$i], $edit !== null);
            }
        }
        $reference = $n['indicateurReference'] ?? null;
        $finance = $reference?->notation;
        if (! empty($finance['details'])) {
            $groups[] = [
                'name' => 'Finance',
                'note' => $finance['note'] ?? 0,
                'rows' => collect($finance['details'])->map(fn ($sc) => [
                    'id' => null,
                    'sequence' => $sc['sequence'] ?? null,
                    'pourcentage' => $sc['pourcentage'] ?? 0,
                    'name' => $sc['critere'] ?? '—',
                    'valeur' => $sc['valeur'] ?? null,
                    'note' => $sc['note'] ?? null,
                    'pondere' => $sc['pondere'] ?? null,
                    'editable' => false,
                ])->values()->all(),
            ];
        }
        if (isset($criteres[3])) {
            $groups[] = $this->critereGroup($criteres[3], $edit !== null);
        }

        return [
            'hasData' => count($n['indicateurs'] ?? []) > 0,
            'groups' => $groups,
            'noteFinale' => $n['noteFinale'] ?? null,
            'sme' => $this->sme($n),
            'edit' => $edit ? [
                'choicesUrl' => $edit['choicesUrl'],
                'reponseUrl' => $edit['reponseUrl'],
                'dossierId' => $d->id,
                'programmeId' => ($edit['sendProgramme'] ?? true) ? $d->programme_id : null,
            ] : null,
            'emptyText' => $emptyText ?? ($edit
                ? 'Importez un fichier DSF pour afficher la grille de notation.'
                : 'L’analyste n’a pas encore importé de fichier DSF pour ce dossier, ou les indicateurs ne sont pas disponibles.'),
        ];
    }

    private function critereGroup(array $critere, bool $editable): array
    {
        return [
            'name' => $critere['name'] ?? '—',
            'note' => $critere['note'] ?? 0,
            'rows' => collect($critere['souscriteres'] ?? [])->map(function ($sc) use ($editable) {
                $choice = $sc['reponse']['choice'] ?? null;

                return [
                    'id' => $sc['id'] ?? null,
                    'sequence' => $sc['sequence'] ?? null,
                    'pourcentage' => $sc['default'] ?? 0,
                    'name' => $sc['name'] ?? '—',
                    'valeur' => $choice['valeur'] ?? null,
                    'note' => $choice ? ($sc['reponse']['note'] ?? null) : null,
                    'pondere' => $choice ? round(($sc['reponse']['note'] ?? 0) * ($sc['default'] ?? 0) / 100, 2) : null,
                    'editable' => $editable && ! empty($sc['id']),
                ];
            })->values()->all(),
        ];
    }

    public function sme(array $n): ?array
    {
        $sme = $n['sme'] ?? null;
        if (! $sme && ($n['noteFinale'] ?? null) === null) {
            return null;
        }
        $mention = $n['smeMention'] ?? ($sme->mention ?? null);

        return [
            'noteFinale' => $n['noteFinale'] ?? null,
            'name' => $sme->name ?? null,
            'mention' => $mention,
            'mentionVariant' => $this->mentionVariant($mention),
            'description' => $n['smeDescription'] ?? ($sme->description ?? null),
        ];
    }

    /** Équivalent de _sme_mention_badge (couleurs Bootstrap → variantes StatusBadge). */
    private function mentionVariant(?string $mention): string
    {
        $m = mb_strtolower(trim((string) $mention));

        return match (true) {
            str_contains($m, 'excellent') => 'success',
            str_contains($m, 'très bon') || str_contains($m, 'tres bon') => 'info',
            trim((string) $mention) === 'Bon' || str_contains($m, 'assez bon') => 'info',
            str_contains($m, 'moyen') => 'warning',
            str_contains($m, 'acceptable') => 'neutral',
            str_contains($m, 'vulnérable') || str_contains($m, 'vulnerable'), str_contains($m, 'douteux') => 'danger',
            default => 'info',
        };
    }

    /**
     * Espace de travail de l'analyste financier (instruction_workspace_analyste).
     */
    public function analyste(Dossier $d, User $user, array $n): array
    {
        $national = $user->isAnalysteFinancierNational();
        $mine = (int) ($d->analyste_id ?? 0) === (int) $user->id;

        // Bloc montants : partial instruction-dossier-consultation ($canEditMontantsAnalyste).
        $budgets = null;
        if ($d->analysteFinancierPeutMettreAJourBudgetsEtEngagements() && ($mine || $national)) {
            $budgets = [
                'url' => route('analyste.dossiers.instruction-budgets-engagements', $d->token),
                'sollicite' => $d->engagements_sollicites_total,
                'enCours' => $d->engagements_en_cours_total,
                'programmes' => $d->instructionProgrammes->sortBy(['sort_order', 'id'])->values()->map(fn ($dip) => [
                    'id' => $dip->id,
                    'name' => $dip->programme?->name ?? '—',
                    'financier' => $dip->budget_appui_financier,
                    'nonFinancier' => $dip->budget_appui_non_financier,
                ])->all(),
            ];
        }

        $sections = null;
        if ($d->analyste_id) {
            $locked = $d->isInstructionSubmittedToExploitation();
            $sections = [
                'locked' => $locked,
                'submitted' => $locked ? $this->event($d->exploitation_analyste_transmitted_to_exploitation_at, $d->exploitationAnalysteTransmittedToExploitationBy) : null,
                'items' => collect(Dossier::EXPLOITATION_AF_INSTRUCTION_SECTIONS)->map(fn ($label, $column) => [
                    'name' => $column,
                    'label' => $label,
                    'html' => (string) ($d->{$column} ?? ''),
                    'filled' => $d->afInstructionSectionHasSubstance($column),
                ])->values()->all(),
                'draftSavedAt' => $this->page->date($d->exploitation_analyste_instruction_avis_saved_at, true),
                'draftUrl' => $locked ? null : route('analyste.dossiers.instruction-avis-brouillon', $d->token),
                'submitUrl' => $locked ? null : route('analyste.dossiers.soumettre-exploitation', $d->token),
            ];
        }

        return [
            'kind' => 'analyste',
            'rejection' => $d->isExploitationAnalysteRejectedByRexp() ? [
                'label' => 'Soumission rejetée par votre responsable',
                'motif' => $d->exploitation_analyste_reject_motif,
                'at' => $this->page->date($d->exploitation_analyste_rejected_at, true),
                'by' => $d->exploitationAnalysteRejectedBy?->name,
                'hint' => 'Vous pouvez désormais modifier vos rubriques d’analyse et retransmettre au responsable exploitation.',
            ] : null,
            'dsf' => ['url' => route('analyste.dossier.dsf'), 'dossierId' => $d->id],
            'sme' => $this->sme($n),
            'grille' => $this->grille($d, $n, [
                'choicesUrl' => route('analyste.instruction.critere.choices'),
                'reponseUrl' => route('analyste.instruction.critere.reponse'),
            ]),
            'budgets' => $budgets,
            'budgetsClosedNotice' => $d->isInstructionClosed()
                ? 'Les totaux d’engagements et les budgets par programme ne sont plus modifiables : le dossier d’instruction est clos.'
                : null,
            'sections' => $sections,
            'engagementGridUrl' => $n['engagementGridUrl'] ?? null,
            'simulations' => $this->simulations($d),
        ];
    }

    /**
     * Espace de travail du chef d'agence (instruction_workspace_ca + avis agence).
     */
    public function chefAgence(Dossier $d, array $n): array
    {
        $summary = [
            ['label' => 'Entreprise', 'value' => $d->entreprise?->name ?? '—'],
            ['label' => 'Programme(s)', 'value' => $d->programmesLabel() ?: '—'],
        ];
        if ($d->chef_filiere_submitted_to_agence_at) {
            $summary[] = ['label' => 'Transmis à l’agence', 'value' => $this->stamp($d->chef_filiere_submitted_to_agence_at, $d->chefFiliereSubmittedToAgenceBy)];
        }
        if ($d->instruction_agence_validated_at) {
            $summary[] = ['label' => 'Validé agence', 'value' => $this->stamp($d->instruction_agence_validated_at, $d->instructionAgenceValidatedBy)];
        } elseif ($d->instruction_agence_rejected_at) {
            $summary[] = ['label' => 'Rejet agence', 'value' => $this->stamp($d->instruction_agence_rejected_at, $d->instructionAgenceRejectedBy), 'danger' => true];
        }
        if ($d->instruction_ca_transmitted_to_exploitation_at) {
            $summary[] = ['label' => 'Transmission REXP', 'value' => $this->stamp($d->instruction_ca_transmitted_to_exploitation_at, $d->instructionCaTransmittedToExploitationBy)];
        }
        $summary[] = ['label' => 'Engagements sollicités', 'value' => $this->page->money($d->engagements_sollicites_total) ?? '—'];
        $summary[] = ['label' => 'Engagements en cours', 'value' => $this->page->money($d->engagements_en_cours_total) ?? '—'];

        $avis = null;
        if ($d->isInstructionValidatedByAgence()) {
            $transmitted = $d->isInstructionCaTransmittedToExploitation();
            $avis = [
                'editable' => ! $transmitted,
                'html' => (string) ($d->instruction_agence_ca_avis ?? ''),
                'saved' => $this->event($d->instruction_agence_ca_avis_saved_at, $d->instructionAgenceCaAvisSavedBy),
                'transmitted' => $transmitted ? $this->event($d->instruction_ca_transmitted_to_exploitation_at, $d->instructionCaTransmittedToExploitationBy) : null,
                'saveUrl' => $transmitted ? null : route('ca.dossier.instruction-agence-avis.save', $d->token),
            ];
        }

        return [
            'kind' => 'ca',
            'summary' => $summary,
            'sme' => $this->sme($n),
            'grille' => $this->grille($d, $n, [
                'choicesUrl' => route('ca.instruction.critere.choices'),
                'reponseUrl' => route('ca.instruction.critere.reponse'),
            ]),
            'avis' => $avis,
        ];
    }

    /**
     * Actions du chef d'agence décrites pour ActionPanel / ActionDialog :
     * validation / rejet de la structuration (ca-instruction-transmission-modals), transmission à l'exploitation.
     */
    public function chefAgenceActions(Dossier $d, User $user, bool $withTransmit = true): array
    {
        $actions = [];
        if (app(StructurationClosureService::class)->canChefAgenceDecide($user, $d) && $d->isInstructionPendingAgenceValidation()) {
            $actions[] = [
                'key' => 'ca-approve',
                'label' => 'Valider la transmission',
                'icon' => 'check_circle',
                'variant' => 'primary',
                'title' => 'Valider la structuration',
                'description' => 'Confirmez la validation de la structuration soumise par le chef de filière (décision chef d’agence).',
                'fields' => [
                    ['name' => 'instruction_agence_ca_avis', 'label' => 'Avis du chef d’agence', 'type' => 'richtext', 'required' => true, 'value' => (string) ($d->instruction_agence_ca_avis ?? '')],
                    ['name' => 'closing_note', 'label' => 'Commentaire interne (optionnel)', 'type' => 'textarea', 'required' => false],
                ],
                'submits' => [['label' => 'Valider la structuration', 'url' => route('ca.workflow.instruction-dossiers.approve', $d->token), 'variant' => 'primary']],
            ];
            $actions[] = [
                'key' => 'ca-reject',
                'label' => 'Rejeter la transmission',
                'icon' => 'undo',
                'variant' => 'danger',
                'title' => 'Rejeter la structuration',
                'description' => 'Le chef de filière pourra corriger la structuration et la soumettre à nouveau.',
                'fields' => [
                    ['name' => 'reject_motif', 'label' => 'Motif du rejet', 'type' => 'textarea', 'required' => false],
                    ['name' => 'closing_note', 'label' => 'Commentaire interne (optionnel)', 'type' => 'textarea', 'required' => false],
                ],
                'submits' => [['label' => 'Rejeter la structuration', 'url' => route('ca.workflow.instruction-dossiers.reject', $d->token), 'variant' => 'danger']],
            ];
        }
        if ($withTransmit && $d->isInstructionValidatedByAgence() && ! $d->isInstructionCaTransmittedToExploitation()
            && $d->hasInstructionAgenceCaAvisSubstance()) {
            $actions[] = [
                'key' => 'ca-transmit',
                'label' => 'Transmettre au responsable exploitation',
                'icon' => 'arrow_forward',
                'variant' => 'primary',
                'title' => 'Transmettre au responsable exploitation',
                'description' => 'Cette action est définitive : l’avis du chef d’agence ne pourra plus être modifié.',
                'fields' => [],
                'submits' => [['label' => 'Confirmer la transmission', 'url' => route('ca.dossier.transmit-exploitation', $d->token), 'variant' => 'primary',
                    'confirm' => 'Transmettre ce dossier au responsable exploitation ? L’avis du chef d’agence ne pourra plus être modifié.']],
            ];
        }

        return $actions;
    }

    /**
     * Consultation administrateur / responsable régional (anciennes fiches Admin/Dossiers/show et Regional/dossiers/show) :
     * grille de notation, rapport d'analyse critique (zones historiques), lien vers la grille des engagements.
     *
     * @param  array{choicesUrl: string, reponseUrl: string}|null  $grilleEdit
     */
    public function consultation(Dossier $d, array $n, ?array $grilleEdit, ?string $zonesUrl): array
    {
        return [
            'kind' => 'consultation',
            'sme' => $this->sme($n),
            'grille' => $this->grille($d, $n, $grilleEdit),
            'zones' => $this->legacyZones($d, $zonesUrl, range(1, 7), 'sequence', false, false),
            'zonesTitle' => 'Rapport d’analyse critique',
            'engagementGridUrl' => $n['engagementGridUrl'] ?? null,
        ];
    }

    /**
     * Zones historiques (grille d'analyse critique : sequences 1 à 7, 8 = gestionnaire, 9 = chef d'agence).
     *
     * @param  int[]  $editable  séquences modifiables (POST $url : dossier_id, sequence, content)
     */
    public function legacyZones(Dossier $d, ?string $url, array $editable, string $mode = 'sequence', bool $withGestionnaire = false, bool $withCa = true, string $caLabel = 'Complément — chef d’agence'): array
    {
        $zones = [];
        foreach (self::LEGACY_ZONES as $seq => [$column, $label, $title]) {
            $zones[] = $this->zone($d, $seq, $column, $label, $title, self::LEGACY_ZONE_HINTS[$seq] ?? null, $url, $editable);
        }
        if ($withGestionnaire) {
            $zones[] = $this->zone($d, 8, 'conclusions_gestionnaire', '8. Conclusions et recommandations du gestionnaire',
                'Conclusions et recommandations du gestionnaire',
                'Saisissez vos conclusions motivées et recommandations pour compléter la grille d’analyse critique.', $url, $editable);
        }
        if ($withCa) {
            $zones[] = $this->zone($d, 9, 'conclusions_ca', $caLabel, 'Remarques et recommandations du chef d’agence', null, $url, $editable)
                + ['highlight' => true];
        }

        return $zones;
    }

    private function zone(Dossier $d, int $seq, string $column, string $label, string $title, ?string $hint, ?string $url, array $editable): array
    {
        $html = (string) ($d->{$column} ?? '');

        return [
            'key' => $column,
            'label' => $label,
            'html' => $html,
            'filled' => trim(strip_tags($html)) !== '',
            'edit' => $url && in_array($seq, $editable, true) ? [
                'title' => $title,
                'description' => $hint,
                'url' => $url,
                'fields' => ['dossier_id' => $d->id, 'sequence' => $seq],
            ] : null,
        ];
    }

    /** Rubriques AF de l'analyste financier (partial exploitation-analyste-instruction-zones). */
    public function afZones(Dossier $d): array
    {
        $zones = collect($d->exploitationAfInstructionZonesForDisplay())->map(fn ($z) => [
            'key' => $z['column'],
            'label' => $z['label'],
            'html' => $z['html'],
            'filled' => $z['filled'],
            'edit' => null,
        ])->all();

        $legacy = (string) ($d->exploitation_analyste_instruction_avis ?? '');
        $anyFilled = collect($zones)->contains(fn ($z) => $z['filled']);

        return [
            'zones' => $zones,
            'legacy' => ! $anyFilled && trim(strip_tags($legacy)) !== '' ? $legacy : null,
        ];
    }

    /** Panneau des simulations de crédit (composant x-simulator::scenarios-panel). */
    public function simulations(Dossier $d): ?array
    {
        if (! Route::has('simulator.index') || ! class_exists(Scenario::class)) {
            return null;
        }
        $lines = $d->instructionProgrammes;
        $ids = $lines->pluck('id')->all();
        $scenarios = Scenario::query()
            ->where(function ($q) use ($d, $ids) {
                $q->where('dossier_id', $d->id);
                if ($ids !== []) {
                    $q->orWhereIn('dossier_instruction_programme_id', $ids);
                }
            })
            ->latest()
            ->get();

        return [
            'newDossierUrl' => route('simulator.index', ['dossier_id' => $d->id]),
            'newLines' => $lines->map(fn ($line) => [
                'label' => ($line->programme?->name ?? 'Programme #'.$line->programme_id)
                    .' ('.number_format((float) $line->budget_appui_financier, 0, ',', ' ').')',
                'url' => route('simulator.index', ['dossier_instruction_programme_id' => $line->id]),
            ])->values()->all(),
            'scenarios' => $scenarios->map(fn ($s) => [
                'name' => $s->name,
                'url' => route('simulator.scenarios.show', $s),
                'target' => $s->dossier_id ? 'Dossier' : 'Ligne #'.$s->dossier_instruction_programme_id,
                'principal' => number_format((float) $s->principal, 0, ',', ' ').' '.$s->currency,
                'rate' => number_format((float) $s->annual_rate, 2, ',', ' ').' %',
                'maxPayment' => number_format((float) $s->max_payment, 0, ',', ' '),
                'teg' => $s->computed_teg !== null ? number_format((float) $s->computed_teg, 2, ',', ' ').' %' : null,
                'status' => __('simulator::simulator.workflow.status.'.$s->status),
                'variant' => match ($s->status) {
                    'submitted' => 'info',
                    'validated' => 'success',
                    'rejected' => 'danger',
                    default => 'neutral',
                },
            ])->values()->all(),
        ];
    }

    /** Budgets programme en lecture (partial instruction-dossier-consultation). */
    public function consultationBudgets(Dossier $d): array
    {
        return [
            'sollicite' => $this->page->money($d->engagements_sollicites_total),
            'enCours' => $this->page->money($d->engagements_en_cours_total),
        ];
    }

    private function stamp(?CarbonInterface $at, ?User $by): string
    {
        return trim(($this->page->date($at, true) ?? '').($by ? ' — '.$by->name : ''));
    }

    private function event(?CarbonInterface $at, ?User $by): ?array
    {
        return $at ? ['at' => $this->page->date($at, true), 'by' => $by?->name] : null;
    }
}
