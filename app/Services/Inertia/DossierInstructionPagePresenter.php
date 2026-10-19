<?php

namespace App\Services\Inertia;

use App\Models\Dossier;
use App\Models\User;
use App\Services\InstructionDelegationService;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

/**
 * Données de la fiche « dossier d'instruction » (page Inertia Dossiers/Show).
 *
 * Point d'évolution unique : la page Vue est générique, c'est ce presenter qui décide,
 * selon l'espace du profil connecté, des pôles visibles et des actions disponibles.
 * Les règles reprennent celles des partials Blade RoleSpace/dossiers/* (mêmes endpoints POST).
 */
class DossierInstructionPagePresenter
{
    /** Espaces qui voient le parcours complet des pôles (équivalent $hubSpace du Blade). */
    public const HUB_SPACES = [
        'respexp', 'juridique', 'analyste-juridique', 'reng', 'analyste-credit',
        'rerx', 'analyste-risques', 'dg', 'dga', 'conformite',
    ];

    /** Relations nécessaires à la page (chargées en une fois). */
    public const RELATIONS = [
        'entreprise', 'agence', 'gestionnaire', 'analyste',
        'instructionProgrammes.programme',
        'fichiersDossier.type', 'fichiersDossier.uploadedBy',
        'chefFiliereSubmittedToAgenceBy', 'instructionAgenceValidatedBy', 'instructionAgenceRejectedBy',
        'instructionCaTransmittedToExploitationBy',
        'exploitationAnalysteAssignedBy', 'exploitationAnalysteTransmittedToExploitationBy', 'exploitationAnalysteRejectedBy',
        'exploitationAvisCreditUser', 'exploitationEngagementsDecisionUser', 'juridiqueInstructionSubmittedBy',
        'juridiqueRejectedToExploitationBy',
        'juridiqueAnalysteUser', 'juridiqueAnalysteAssignedBy', 'juridiqueAnalysteSubmittedToRejuBy', 'juridiqueAnalysteRejectedBy',
        'juridiqueResponsableAvisBy', 'juridiqueSubmittedToEngagementsBy', 'engagementsRejectedToJuridiqueBy',
        'rengAnalysteCreditUser', 'rengAnalysteCreditAssignedBy', 'rengAnalysteCreditSubmittedBy', 'rengAnalysteCreditRejectedBy',
        'rengResponsableAvisBy', 'rengSubmittedToRisquesBy', 'risquesRejectedToEngagementsBy',
        'rerxAnalysteRisquesUser', 'rerxAnalysteRisquesAssignedBy', 'rerxAnalysteRisquesSubmittedBy', 'rerxAnalysteRisquesRejectedBy',
        'rerxResponsableAvisBy', 'rerxSubmittedToDirectionBy', 'directionRejectedToRisquesBy',
        'instructionClosureValidatedBy', 'instructionClosureRejectedBy',
    ];

    public function __construct(private readonly InstructionDelegationService $delegation)
    {
    }

    /**
     * @param  array{route: string, label: string, title: string}  $space
     * @param  array<string, Collection<int, User>>  $analystes  analystes affectables par espace responsable
     */
    public function present(Dossier $dossier, array $space, User $user, bool $readonly, array $analystes, string $legacyUrl): array
    {
        $route = $space['route'];
        $isHub = in_array($route, self::HUB_SPACES, true);
        $canClose = ! $readonly && $this->delegation->userCanCloseInstruction($user, $dossier);

        return [
            'space' => $space,
            'readonly' => $readonly,
            'dossier' => $this->header($dossier),
            'steps' => $this->steps($dossier),
            'poles' => $isHub ? $this->poles($dossier) : [],
            'timeline' => $this->timeline($dossier),
            'pieces' => $this->pieces($dossier),
            'delegation' => [
                'rule' => $this->delegation->describeRuleForInstructionClosure($dossier),
                'status' => $this->delegation->instructionClosureStatutLabel($dossier),
                'canClose' => $canClose,
            ],
            'actions' => $readonly ? [] : $this->actions($dossier, $route, $user, $analystes, $canClose),
            'links' => $this->links($dossier, $route, $legacyUrl),
        ];
    }

    private function header(Dossier $d): array
    {
        $statut = $d->instructionStatutPresentation();

        return [
            'token' => $d->token,
            'reference' => 'DI-'.str_pad((string) $d->id, 5, '0', STR_PAD_LEFT),
            'programmesLabel' => $d->programmesLabel(),
            'currentStep' => $d->currentOrganisationLabel(),
            'statut' => [
                'code' => $statut['code'],
                'label' => $statut['label'],
                'variant' => $this->variant($statut['badge_variant'] ?? 'secondary'),
                'detail' => $statut['detail'] ?? null,
            ],
            'entreprise' => $d->entreprise ? [
                'name' => $d->entreprise->name,
                'rccm' => $d->entreprise->rccm,
                'phone' => $d->entreprise->phone,
                'email' => $d->entreprise->email,
                'manager' => $d->entreprise->manager,
            ] : null,
            'agence' => $d->agence?->name,
            'gestionnaire' => $d->gestionnaire?->name,
            'analysteFinancier' => $d->analyste?->name,
            'montants' => [
                'sollicite' => $this->money($d->engagements_sollicites_total),
                'enCours' => $this->money($d->engagements_en_cours_total),
            ],
            'programmes' => $d->instructionProgrammes->map(fn ($dip) => [
                'name' => $dip->programme?->name ?? '—',
                'financier' => $this->money($dip->budget_appui_financier),
                'nonFinancier' => $this->money($dip->budget_appui_non_financier),
            ])->values(),
            'closed' => $d->isInstructionClosed(),
            'createdAt' => $this->date($d->created_at),
        ];
    }

    /**
     * Stepper horizontal : Agence → Exploitation → Juridique → Engagements → Risques → Direction.
     */
    private function steps(Dossier $d): array
    {
        $closed = $d->isInstructionClosed();
        $raw = [
            // Même règle que Dossier::currentOrganisation() : l'agence est franchie dès que le dossier
            // n'est plus en attente de validation agence.
            ['Agence', 'storefront', ! $d->isInstructionPendingAgenceValidation() && $d->instruction_agence_rejected_at === null,
                $d->instruction_agence_rejected_at !== null, $d->instructionAgenceValidatedBy ?? $d->instructionAgenceRejectedBy,
                $d->instruction_agence_validated_at ?? $d->instruction_agence_rejected_at],
            ['Exploitation', 'query_stats', $d->isSubmittedToJuridique(), false,
                $d->juridiqueInstructionSubmittedBy, $d->juridique_instruction_submitted_at],
            ['Juridique', 'gavel', $d->isSubmittedToEngagementsFromJuridique(), false,
                $d->juridiqueSubmittedToEngagementsBy, $d->juridique_submitted_to_engagements_at],
            ['Engagements', 'request_quote', $d->isSubmittedToRisquesFromReng(), false,
                $d->rengSubmittedToRisquesBy, $d->reng_submitted_to_risques_at],
            ['Risques', 'shield', $d->isSubmittedToDirectionFromRerx(), false,
                $d->rerxSubmittedToDirectionBy, $d->rerx_submitted_to_direction_at],
            ['Direction', 'workspace_premium', $d->instruction_closure_validated_at !== null, $d->instruction_closure_rejected_at !== null,
                $d->instructionClosureValidatedBy ?? $d->instructionClosureRejectedBy,
                $d->instruction_closure_validated_at ?? $d->instruction_closure_rejected_at],
        ];

        $currentFound = false;
        $steps = [];
        foreach ($raw as $i => [$label, $icon, $done, $rejected, $actor, $at]) {
            $state = match (true) {
                $rejected => 'rejected',
                $done => 'done',
                ! $currentFound && ! $closed => 'current',
                default => 'pending',
            };
            if ($state === 'current' || $state === 'rejected') {
                $currentFound = true;
            }
            $steps[] = [
                'key' => $i + 1,
                'label' => $label,
                'icon' => $icon,
                'state' => $state,
                'actor' => $actor?->name,
                'at' => $this->date($at),
            ];
        }

        return $steps;
    }

    /**
     * Une carte par pôle : travail de l'analyste, avis du responsable, rejets éventuels.
     */
    private function poles(Dossier $d): array
    {
        $poles = [
            [
                'key' => 'exploitation',
                'title' => 'Pôle Exploitation',
                'icon' => 'query_stats',
                'reached' => $d->isInstructionCaTransmittedToExploitation() || $d->analyste_id !== null,
                'done' => $d->isSubmittedToJuridique(),
                'analyst' => $this->person($d->analyste, $d->exploitation_analyste_assigned_at, $d->exploitationAnalysteAssignedBy),
                'analystRole' => 'Analyste financier',
                'analystSubmitted' => $this->event($d->exploitation_analyste_transmitted_to_exploitation_at, $d->exploitationAnalysteTransmittedToExploitationBy),
                'analystSections' => $this->sections([
                    'Brèves données sur le client' => $d->exploitation_af_breves_donnees_client,
                    'Analyse financière du client' => $d->exploitation_af_analyse_financiere_client,
                    'Analyse du risque de remboursement' => $d->exploitation_af_analyse_risque_remboursement,
                    'Rentabilité de la relation' => $d->exploitation_af_rentabilite_relation,
                    'Appuis proposés' => $d->exploitation_af_appuis_proposes,
                    'Analyse critique d\'ensemble' => $d->exploitation_af_analyse_critique_ensemble,
                    'Conclusions et recommandations' => $d->exploitation_af_conclusions_recommandations,
                    'Avis de l\'analyste' => $d->exploitation_analyste_instruction_avis,
                ]),
                'responsableRole' => 'Responsable Exploitation',
                'responsableAvis' => $this->opinion($d->exploitation_avis_credit, $d->exploitation_avis_credit_at, $d->exploitationAvisCreditUser),
                'decision' => $d->exploitation_engagements_decision ? [
                    'value' => $d->exploitation_engagements_decision,
                    'label' => $d->exploitation_engagements_decision === 'accord' ? 'Accord sur les engagements' : 'Rejet des engagements',
                    'variant' => $d->exploitation_engagements_decision === 'accord' ? 'success' : 'danger',
                    'comment' => $d->exploitation_engagements_decision_comment,
                    'event' => $this->event($d->exploitation_engagements_decision_at, $d->exploitationEngagementsDecisionUser),
                ] : null,
                'rejections' => array_values(array_filter([
                    $this->rejection('Travail de l\'analyste rejeté', $d->exploitation_analyste_rejected_at, $d->exploitation_analyste_reject_motif, $d->exploitationAnalysteRejectedBy),
                    $this->rejection('Dossier renvoyé par le pôle Juridique', $d->juridique_rejected_to_exploitation_at, $d->juridique_rejected_to_exploitation_motif, $d->juridiqueRejectedToExploitationBy),
                ])),
                'transmitted' => $this->event($d->juridique_instruction_submitted_at, $d->juridiqueInstructionSubmittedBy),
            ],
            [
                'key' => 'juridique',
                'title' => 'Pôle Juridique',
                'icon' => 'gavel',
                'reached' => $d->isSubmittedToJuridique(),
                'done' => $d->isSubmittedToEngagementsFromJuridique(),
                'analyst' => $this->person($d->juridiqueAnalysteUser, $d->juridique_analyste_assigned_at, $d->juridiqueAnalysteAssignedBy),
                'analystRole' => 'Analyste juridique',
                'analystSubmitted' => $this->event($d->juridique_analyste_submitted_to_reju_at, $d->juridiqueAnalysteSubmittedToRejuBy),
                'analystSections' => $this->sections(['Avis juridique' => $d->juridique_analyste_avis]),
                'responsableRole' => 'Responsable Juridique',
                'responsableAvis' => $this->opinion($d->juridique_responsable_avis, $d->juridique_responsable_avis_at, $d->juridiqueResponsableAvisBy),
                'decision' => null,
                'rejections' => array_values(array_filter([
                    $this->rejection('Travail de l\'analyste rejeté', $d->juridique_analyste_rejected_at, $d->juridique_analyste_reject_motif, $d->juridiqueAnalysteRejectedBy),
                    $this->rejection('Dossier renvoyé par le pôle Engagements', $d->engagements_rejected_to_juridique_at, $d->engagements_rejected_to_juridique_motif, $d->engagementsRejectedToJuridiqueBy),
                ])),
                'transmitted' => $this->event($d->juridique_submitted_to_engagements_at, $d->juridiqueSubmittedToEngagementsBy),
            ],
            [
                'key' => 'engagements',
                'title' => 'Pôle Engagements',
                'icon' => 'request_quote',
                'reached' => $d->isSubmittedToEngagementsFromJuridique(),
                'done' => $d->isSubmittedToRisquesFromReng(),
                'analyst' => $this->person($d->rengAnalysteCreditUser, $d->reng_analyste_credit_assigned_at, $d->rengAnalysteCreditAssignedBy),
                'analystRole' => 'Analyste crédit',
                'analystSubmitted' => $this->event($d->reng_analyste_credit_submitted_at, $d->rengAnalysteCreditSubmittedBy),
                'analystSections' => $this->sections([
                    'Contre-analyse' => $d->reng_contre_analyse,
                    'Avis de l\'analyste crédit' => $d->reng_analyste_credit_avis,
                ]),
                'responsableRole' => 'Responsable Engagements',
                'responsableAvis' => $this->opinion($d->reng_responsable_avis, $d->reng_responsable_avis_at, $d->rengResponsableAvisBy),
                'decision' => null,
                'rejections' => array_values(array_filter([
                    $this->rejection('Travail de l\'analyste rejeté', $d->reng_analyste_credit_rejected_at, $d->reng_analyste_credit_reject_motif, $d->rengAnalysteCreditRejectedBy),
                    $this->rejection('Dossier renvoyé par le pôle Risques', $d->risques_rejected_to_engagements_at, $d->risques_rejected_to_engagements_motif, $d->risquesRejectedToEngagementsBy),
                ])),
                'transmitted' => $this->event($d->reng_submitted_to_risques_at, $d->rengSubmittedToRisquesBy),
            ],
            [
                'key' => 'risques',
                'title' => 'Pôle Risques',
                'icon' => 'shield',
                'reached' => $d->isSubmittedToRisquesFromReng(),
                'done' => $d->isSubmittedToDirectionFromRerx(),
                'analyst' => $this->person($d->rerxAnalysteRisquesUser, $d->rerx_analyste_risques_assigned_at, $d->rerxAnalysteRisquesAssignedBy),
                'analystRole' => 'Analyste risques',
                'analystSubmitted' => $this->event($d->rerx_analyste_risques_submitted_at, $d->rerxAnalysteRisquesSubmittedBy),
                'analystSections' => $this->sections([
                    'Analyse des risques' => $d->rerx_analyse_risques,
                    'Avis de l\'analyste risques' => $d->rerx_analyste_risques_avis,
                ]),
                'responsableRole' => 'Responsable Risques',
                'responsableAvis' => $this->opinion($d->rerx_responsable_avis, $d->rerx_responsable_avis_at, $d->rerxResponsableAvisBy),
                'decision' => null,
                'rejections' => array_values(array_filter([
                    $this->rejection('Travail de l\'analyste rejeté', $d->rerx_analyste_risques_rejected_at, $d->rerx_analyste_risques_reject_motif, $d->rerxAnalysteRisquesRejectedBy),
                    $this->rejection('Dossier renvoyé par la Direction', $d->direction_rejected_to_risques_at, $d->direction_rejected_to_risques_motif, $d->directionRejectedToRisquesBy),
                ])),
                'transmitted' => $this->event($d->rerx_submitted_to_direction_at, $d->rerxSubmittedToDirectionBy),
            ],
        ];

        $poles[] = [
            'key' => 'direction',
            'title' => 'Direction générale — décision',
            'icon' => 'workspace_premium',
            'reached' => $d->isSubmittedToDirectionFromRerx() || $d->isInstructionClosed(),
            'done' => $d->isInstructionClosed(),
            'analyst' => null,
            'analystRole' => null,
            'analystSubmitted' => null,
            'analystSections' => [],
            'responsableRole' => 'Décision de clôture',
            'responsableAvis' => $this->opinion(
                $d->instruction_closure_note ? nl2br(e($d->instruction_closure_note)) : null,
                $d->instruction_closure_validated_at ?? $d->instruction_closure_rejected_at,
                $d->instructionClosureValidatedBy ?? $d->instructionClosureRejectedBy
            ),
            'decision' => $d->isInstructionClosed() ? [
                'value' => $d->instruction_closure_validated_at ? 'accord' : 'rejet',
                'label' => $d->instruction_closure_validated_at ? 'Dossier accordé' : 'Dossier rejeté',
                'variant' => $d->instruction_closure_validated_at ? 'success' : 'danger',
                'comment' => $d->instruction_closure_reject_motif,
                'event' => $this->event(
                    $d->instruction_closure_validated_at ?? $d->instruction_closure_rejected_at,
                    $d->instructionClosureValidatedBy ?? $d->instructionClosureRejectedBy
                ),
            ] : null,
            'rejections' => [],
            'transmitted' => null,
        ];

        foreach ($poles as &$pole) {
            $pole['state'] = match (true) {
                $pole['done'] => 'done',
                $pole['reached'] => 'current',
                default => 'pending',
            };
        }

        return $poles;
    }

    /**
     * Actions disponibles pour l'utilisateur, décrites de façon déclarative pour un rendu générique.
     *
     * @param  array<string, Collection<int, User>>  $analystes
     */
    private function actions(Dossier $d, string $route, User $user, array $analystes, bool $canClose): array
    {
        $t = $d->token;
        $uid = (int) $user->id;
        $actions = [];

        $assign = function (string $key, string $routeName, string $field, string $label, Collection $users) use (&$actions, $t) {
            if ($users->isEmpty() || ! Route::has($routeName)) {
                return;
            }
            $actions[] = [
                'key' => $key,
                'label' => $label,
                'icon' => 'person_add',
                'variant' => 'secondary',
                'title' => $label,
                'description' => 'L\'analyste désigné reçoit une notification par e-mail.',
                'fields' => [[
                    'name' => $field,
                    'label' => 'Analyste',
                    'type' => 'select',
                    'required' => true,
                    'options' => $users->map(fn (User $u) => [
                        'value' => $u->id,
                        'label' => $u->name.($u->agence?->name ? ' — '.$u->agence->name : ''),
                    ])->values(),
                ]],
                'submits' => [['label' => 'Affecter', 'url' => route($routeName, $t), 'variant' => 'primary']],
            ];
        };

        $opinion = function (string $key, string $routeName, string $field, string $label, ?string $value) use (&$actions, $t) {
            $actions[] = [
                'key' => $key,
                'label' => $label,
                'icon' => 'rate_review',
                'variant' => 'secondary',
                'title' => $label,
                'fields' => [['name' => $field, 'label' => 'Avis', 'type' => 'richtext', 'required' => true, 'value' => $value]],
                'submits' => [['label' => 'Enregistrer l\'avis', 'url' => route($routeName, $t), 'variant' => 'primary']],
            ];
        };

        $transmit = function (string $key, string $routeName, string $label, string $confirm) use (&$actions, $t) {
            $actions[] = [
                'key' => $key,
                'label' => $label,
                'icon' => 'arrow_forward',
                'variant' => 'primary',
                'title' => $label,
                'description' => $confirm,
                'fields' => [],
                'submits' => [['label' => 'Confirmer la transmission', 'url' => route($routeName, $t), 'variant' => 'primary']],
            ];
        };

        $reject = function (string $key, string $routeName, string $label, string $description) use (&$actions, $t) {
            $actions[] = [
                'key' => $key,
                'label' => $label,
                'icon' => 'undo',
                'variant' => 'danger',
                'title' => $label,
                'description' => $description,
                'fields' => [['name' => 'rejet_motif', 'label' => 'Motif du rejet', 'type' => 'textarea', 'required' => true]],
                'submits' => [['label' => 'Confirmer le rejet', 'url' => route($routeName, $t), 'variant' => 'danger']],
            ];
        };

        $analystWork = function (string $key, array $fields, ?string $draftRoute, string $submitRoute, string $submitLabel) use (&$actions, $t) {
            $submits = [];
            if ($draftRoute) {
                $submits[] = ['label' => 'Enregistrer le brouillon', 'url' => route($draftRoute, $t), 'variant' => 'secondary'];
            }
            $submits[] = ['label' => $submitLabel, 'url' => route($submitRoute, $t), 'variant' => 'primary',
                'confirm' => 'Après soumission, vous ne pourrez plus modifier votre travail (sauf rejet).'];
            $actions[] = [
                'key' => $key,
                'label' => 'Rédiger mon analyse',
                'icon' => 'edit_note',
                'variant' => 'primary',
                'title' => 'Mon analyse',
                'fields' => $fields,
                'submits' => $submits,
                'wide' => true,
            ];
        };

        switch ($route) {
            case 'respexp':
                $locked = $d->analyste_id && ! $d->isInstructionVisibleToResponsableExploitation();
                $transmis = $d->isSubmittedToJuridique();
                if (! $transmis) {
                    $assign('assign', 'respexp.dossiers.assign-analyste', 'analyste_user_id',
                        $d->analyste_id ? 'Réaffecter l\'analyste financier' : 'Affecter un analyste financier', $analystes['respexp'] ?? collect());
                }
                if (! $locked && ! $transmis) {
                    $opinion('avis', 'respexp.dossiers.avis-credit', 'exploitation_avis_credit', 'Saisir mon avis de crédit', $d->exploitation_avis_credit);
                }
                if ($d->analyste_id && ! $locked && ! $transmis) {
                    $actions[] = [
                        'key' => 'decision',
                        'label' => 'Décision sur les engagements',
                        'icon' => 'fact_check',
                        'variant' => 'secondary',
                        'title' => 'Validation du dossier des engagements',
                        'fields' => [
                            ['name' => 'decision', 'label' => 'Décision', 'type' => 'radio', 'required' => true,
                                'value' => $d->exploitation_engagements_decision,
                                'options' => [['value' => 'accord', 'label' => 'Accord'], ['value' => 'rejet', 'label' => 'Rejet']]],
                            ['name' => 'comment', 'label' => 'Commentaire (obligatoire en cas de rejet)', 'type' => 'textarea',
                                'required' => false, 'value' => $d->exploitation_engagements_decision_comment],
                        ],
                        'submits' => [['label' => 'Enregistrer la décision', 'url' => route('respexp.dossiers.validation-engagements', $d->token), 'variant' => 'primary']],
                    ];
                }
                if ($d->canRespexpSoumettreAuJuridique()) {
                    $transmit('submit', 'respexp.dossiers.soumettre-juridique', 'Soumettre au Juridique',
                        'Le dossier sera transmis au responsable juridique. Votre avis et votre décision ne seront plus modifiables.');
                }
                if ($d->isInstructionTransmittedToExploitationByAnalysteFinancier() && ! $transmis) {
                    $reject('reject-analyst', 'respexp.dossiers.rejeter-analyste', 'Rejeter le travail de l\'analyste',
                        'L\'instruction de l\'analyste financier redevient modifiable. Le motif est tracé.');
                }
                break;

            case 'juridique':
                $toReju = $d->isJuridiqueAnalysteAvisSubmittedToReju();
                $toEng = $d->isSubmittedToEngagementsFromJuridique();
                if (! $toEng && ! $toReju) {
                    $assign('assign', 'juridique.dossiers.assign-juridique-analyste', 'juridique_analyste_user_id',
                        $d->juridique_analyste_user_id ? 'Réaffecter l\'analyste juridique' : 'Affecter un analyste juridique', $analystes['juridique'] ?? collect());
                }
                if ($toReju && ! $toEng) {
                    $opinion('avis', 'juridique.dossiers.responsable-avis', 'juridique_responsable_avis', 'Saisir mon avis juridique', $d->juridique_responsable_avis);
                    $transmit('submit', 'juridique.dossiers.soumettre-engagements', 'Soumettre aux Engagements',
                        'Le dossier sera transmis au responsable engagements.');
                    $reject('reject-analyst', 'juridique.dossiers.rejeter-analyste', 'Rejeter vers l\'analyste',
                        'L\'avis de l\'analyste juridique redevient modifiable.');
                    $reject('reject-pole', 'juridique.dossiers.rejeter-vers-exploitation', 'Renvoyer au pôle Exploitation',
                        'Le dossier retourne au responsable exploitation pour révision.');
                }
                break;

            case 'analyste-juridique':
                $mine = (int) $d->juridique_analyste_user_id === $uid;
                if ($mine && (! $d->isJuridiqueAnalysteAvisSubmittedToReju() || $d->isJuridiqueAnalysteRejectedByReju())) {
                    $analystWork('work', [
                        ['name' => 'juridique_analyste_avis', 'label' => 'Avis juridique', 'type' => 'richtext', 'required' => true, 'value' => $d->juridique_analyste_avis],
                    ], null, 'analyste-juridique.dossiers.soumettre-reju', 'Soumettre au responsable juridique');
                }
                break;

            case 'reng':
                $byAnalyst = $d->isRengAnalysteCreditSubmittedToReng();
                $toRisq = $d->isSubmittedToRisquesFromReng();
                if (! $toRisq && ! $byAnalyst) {
                    $assign('assign', 'reng.dossiers.assign-analyste-credit', 'reng_analyste_credit_user_id',
                        $d->reng_analyste_credit_user_id ? 'Réaffecter l\'analyste crédit' : 'Affecter un analyste crédit', $analystes['reng'] ?? collect());
                }
                if ($byAnalyst && ! $toRisq) {
                    $opinion('avis', 'reng.dossiers.responsable-avis', 'reng_responsable_avis', 'Saisir mon avis d\'engagement', $d->reng_responsable_avis);
                    $transmit('submit', 'reng.dossiers.soumettre-risques', 'Soumettre aux Risques',
                        'Le dossier sera transmis au responsable risques.');
                    $reject('reject-analyst', 'reng.dossiers.rejeter-analyste-credit', 'Rejeter vers l\'analyste',
                        'La contre-analyse et l\'avis de l\'analyste crédit redeviennent modifiables.');
                    $reject('reject-pole', 'reng.dossiers.rejeter-vers-juridique', 'Renvoyer au pôle Juridique',
                        'Le dossier retourne au responsable juridique pour révision.');
                }
                break;

            case 'analyste-credit':
                $mine = (int) $d->reng_analyste_credit_user_id === $uid;
                if ($mine && (! $d->isRengAnalysteCreditSubmittedToReng() || $d->isRengAnalysteCreditRejectedByReng())) {
                    $analystWork('work', [
                        ['name' => 'reng_contre_analyse', 'label' => 'Contre-analyse', 'type' => 'richtext', 'required' => true, 'value' => $d->reng_contre_analyse],
                        ['name' => 'reng_analyste_credit_avis', 'label' => 'Avis', 'type' => 'richtext', 'required' => true, 'value' => $d->reng_analyste_credit_avis],
                    ], 'analyste-credit.dossiers.brouillon', 'analyste-credit.dossiers.soumettre-reng', 'Soumettre au responsable engagements');
                }
                break;

            case 'rerx':
                $byAnalyst = $d->isRerxAnalysteRisquesSubmittedToRerx();
                $toDir = $d->isSubmittedToDirectionFromRerx();
                if (! $toDir && ! $byAnalyst) {
                    $assign('assign', 'rerx.dossiers.assign-analyste-risques', 'rerx_analyste_risques_user_id',
                        $d->rerx_analyste_risques_user_id ? 'Réaffecter l\'analyste risques' : 'Affecter un analyste risques', $analystes['rerx'] ?? collect());
                }
                if ($byAnalyst && ! $toDir) {
                    $opinion('avis', 'rerx.dossiers.responsable-avis', 'rerx_responsable_avis', 'Saisir mon avis risques', $d->rerx_responsable_avis);
                    $transmit('submit', 'rerx.dossiers.soumettre-direction', 'Soumettre à la Direction',
                        'Le dossier sera transmis à la Direction générale (ou au délégataire) pour décision.');
                    $reject('reject-analyst', 'rerx.dossiers.rejeter-analyste-risques', 'Rejeter vers l\'analyste',
                        'L\'analyse et l\'avis de l\'analyste risques redeviennent modifiables.');
                    $reject('reject-pole', 'rerx.dossiers.rejeter-vers-engagements', 'Renvoyer au pôle Engagements',
                        'Le dossier retourne au responsable engagements pour révision.');
                }
                break;

            case 'analyste-risques':
                $mine = (int) $d->rerx_analyste_risques_user_id === $uid;
                if ($mine && (! $d->isRerxAnalysteRisquesSubmittedToRerx() || $d->isRerxAnalysteRisquesRejectedByRisq())) {
                    $analystWork('work', [
                        ['name' => 'rerx_analyse_risques', 'label' => 'Analyse des risques', 'type' => 'richtext', 'required' => true, 'value' => $d->rerx_analyse_risques],
                        ['name' => 'rerx_analyste_risques_avis', 'label' => 'Avis', 'type' => 'richtext', 'required' => true, 'value' => $d->rerx_analyste_risques_avis],
                    ], 'analyste-risques.dossiers.brouillon', 'analyste-risques.dossiers.soumettre-rerx', 'Soumettre au responsable risques');
                }
                break;
        }

        if ($canClose && ! $d->isInstructionClosed()) {
            $actions[] = [
                'key' => 'close-approve',
                'label' => 'Accorder et clôturer',
                'icon' => 'verified',
                'variant' => 'success',
                'title' => 'Accorder le dossier',
                'description' => 'Fin de parcours du dossier d\'instruction au titre de la délégation de pouvoir.',
                'fields' => [['name' => 'instruction_closure_note', 'label' => 'Note de clôture', 'type' => 'textarea', 'required' => true]],
                'submits' => [['label' => 'Accorder', 'url' => route('instruction.dossiers.closure.approve', $d->token), 'variant' => 'success']],
            ];
            $actions[] = [
                'key' => 'close-reject',
                'label' => 'Rejeter et clôturer',
                'icon' => 'block',
                'variant' => 'danger',
                'title' => 'Rejeter le dossier',
                'fields' => [
                    ['name' => 'instruction_closure_reject_motif', 'label' => 'Motif (optionnel)', 'type' => 'textarea', 'required' => false],
                    ['name' => 'instruction_closure_note', 'label' => 'Note de clôture', 'type' => 'textarea', 'required' => true],
                ],
                'submits' => [['label' => 'Rejeter', 'url' => route('instruction.dossiers.closure.reject', $d->token), 'variant' => 'danger']],
            ];
            if ($d->isSubmittedToDirectionFromRerx() && ! $d->isDirectionRejectedToRisques()) {
                $reject('reject-pole', 'instruction.dossiers.rejeter-vers-risques', 'Renvoyer au pôle Risques',
                    'Le dossier retourne au responsable risques pour révision, sans être clôturé.');
            }
        }

        return $actions;
    }

    private function links(Dossier $d, string $route, string $legacyUrl): array
    {
        $links = ['legacy' => $legacyUrl];
        foreach ([
            'instruction' => "$route.dossiers.instruction",
            'instructionPdf' => "$route.dossiers.instruction.pdf",
            'analyseCritique' => "$route.dossiers.dossier-analyse-critique",
            'analyseCritiquePdf' => "$route.dossiers.dossier-analyse-critique.pdf",
            'list' => "$route.dossiers.index",
        ] as $key => $name) {
            $links[$key] = Route::has($name)
                ? route($name, $key === 'list' ? [] : $d->token)
                : null;
        }
        if ($d->entreprise?->token && Route::has("$route.entreprises.show")) {
            $links['entreprise'] = route("$route.entreprises.show", $d->entreprise->token);
        }

        return $links;
    }

    private function timeline(Dossier $d): array
    {
        return $d->instructionWorkflowHistoryTimeline()
            ->sortByDesc(fn ($row) => $row['at']?->getTimestamp() ?? 0)
            ->values()
            ->map(fn ($row) => [
                'label' => $row['label'],
                'actor' => $row['actor']?->name,
                'at' => $this->date($row['at'], true),
                'kind' => $row['kind'],
            ])
            ->all();
    }

    private function pieces(Dossier $d): array
    {
        return $d->fichiersDossier->map(fn ($f) => [
            'id' => $f->id,
            'name' => $f->original_name ?: basename((string) $f->name),
            'type' => $f->type?->name,
            'url' => $f->path,
            'uploadedBy' => $f->uploadedBy?->name,
            'uploadedAt' => $this->date($f->uploaded_at, true),
        ])->values()->all();
    }

    private function person(?User $user, ?CarbonInterface $at, ?User $by): ?array
    {
        return $user ? ['name' => $user->name, 'assignedAt' => $this->date($at, true), 'assignedBy' => $by?->name] : null;
    }

    private function event(?CarbonInterface $at, ?User $by): ?array
    {
        return $at ? ['at' => $this->date($at, true), 'by' => $by?->name] : null;
    }

    private function opinion(?string $html, ?CarbonInterface $at, ?User $by): ?array
    {
        return $this->filled($html) ? ['html' => $html, 'at' => $this->date($at, true), 'by' => $by?->name] : null;
    }

    private function rejection(string $label, ?CarbonInterface $at, ?string $motif, ?User $by): ?array
    {
        return $at ? ['label' => $label, 'motif' => $motif, 'at' => $this->date($at, true), 'by' => $by?->name] : null;
    }

    /**
     * @param  array<string, ?string>  $sections
     */
    private function sections(array $sections): array
    {
        $out = [];
        foreach ($sections as $label => $html) {
            if ($this->filled($html)) {
                $out[] = ['label' => $label, 'html' => $html];
            }
        }

        return $out;
    }

    private function filled(?string $html): bool
    {
        return trim(strip_tags((string) $html)) !== '';
    }

    private function money($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return number_format((float) $value, 0, ',', ' ').' FCFA';
    }

    private function date($value, bool $withTime = false): ?string
    {
        if (! $value instanceof CarbonInterface) {
            return null;
        }

        return $value->format($withTime ? 'd/m/Y H:i' : 'd/m/Y');
    }

    private function variant(string $bootstrap): string
    {
        return match ($bootstrap) {
            'success' => 'success',
            'danger' => 'danger',
            'warning' => 'warning',
            'info', 'primary' => 'info',
            default => 'neutral',
        };
    }
}
