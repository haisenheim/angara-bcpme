<?php

namespace App\Services\Inertia;

use App\Models\Dossier;
use App\Models\DossierEntreeRelation;
use App\Models\Entreprise;
use App\Models\User;
use App\Services\InstructionDelegationService;
use App\Support\Inertia\Badge;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/**
 * Validations du chef d'agence (espace « ca ») : files de validation et fiche de décision
 * sur un dossier d'instruction multi-programmes (pages Inertia Workflow/Index et Workflow/DossierValidation).
 *
 * Les règles d'accès et de décision restent celles de Ca\WorkflowController et de
 * StructurationClosureService ; ce presenter ne fait que décrire l'écran.
 */
class AgenceValidationPresenter
{
    public function __construct(private readonly InstructionDelegationService $delegation)
    {
    }

    /**
     * Onglets entre les trois files du chef d'agence. Les DG / DGA n'accèdent qu'aux dossiers d'instruction
     * (middleware `ca`) : seule cette file leur est proposée.
     *
     * @param  'prospects'|'structurations'|'dossiers'  $active
     */
    public function queues(User $user, string $active): array
    {
        $isChefAgence = (int) $user->role_id === (int) config('angara.role_chef_agence', 15);
        $agenceId = $user->agence_id;

        $dossiers = Dossier::on('central_app_mysql')->whereHas('instructionProgrammes')
            ->when(! $this->delegation->isDirectionRole($user), fn ($q) => $q->whereHas('entreprise', fn ($e) => $e->where('agence_id', $agenceId)))
            ->whereNotNull('chef_filiere_submitted_to_agence_at')
            ->whereNull('instruction_agence_validated_at')
            ->whereNull('instruction_agence_rejected_at');

        $queues = [];
        if ($isChefAgence) {
            $queues[] = ['key' => 'prospects', 'label' => 'Prospects à valider', 'icon' => 'how_to_reg',
                'href' => route('ca.workflow.prospects.index'),
                'count' => Entreprise::query()->submittedProspect()->count()];
            $queues[] = ['key' => 'structurations', 'label' => 'Structurations à valider', 'icon' => 'account_tree',
                'href' => route('ca.workflow.instructions.index'),
                'count' => DossierEntreeRelation::query()
                    ->where('statut', DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION)
                    ->whereNull('qualification_validated_by_agence_at')
                    ->whereHas('entreprise', fn ($q) => $q->where('agence_id', $agenceId))
                    ->count()];
        }
        $queues[] = ['key' => 'dossiers', 'label' => 'Dossiers d\'instruction à valider', 'icon' => 'fact_check',
            'href' => route('ca.workflow.instruction-dossiers.index'), 'count' => $dossiers->count()];

        return array_map(fn ($q) => $q + ['active' => $q['key'] === $active], $queues);
    }

    /**
     * Fiche de décision sur un dossier d'instruction soumis par le chef de filière.
     *
     * @param  array  $consultation  InstructionDossierConsultationService::build()
     */
    public function bundle(Dossier $dossier, array $consultation, bool $canDecide, string $closureLabel): array
    {
        $pending = $dossier->isInstructionPendingAgenceValidation();
        $canAct = $canDecide && $pending;
        $statut = $dossier->instructionStatutPresentation();
        $e = $dossier->entreprise;

        return [
            'dossier' => [
                'token' => $dossier->token,
                'reference' => 'DI-'.str_pad((string) $dossier->id, 5, '0', STR_PAD_LEFT),
                'programmesLabel' => $dossier->programmesLabel(),
                'statut' => Badge::fromPresentation($statut),
                'entreprise' => $e ? ['name' => $e->name, 'rccm' => $e->rccm, 'manager' => $e->manager, 'phone' => $e->phone] : null,
                'agence' => $e?->agence?->name,
                'submittedAt' => $this->dt($dossier->chef_filiere_submitted_to_agence_at),
                'submittedBy' => $dossier->chefFiliereSubmittedToAgenceBy?->name,
            ],
            'engagements' => [
                'sollicites' => $this->money($dossier->engagements_sollicites_total),
                'enCours' => $this->money($dossier->engagements_en_cours_total),
            ],
            'budgets' => [
                'lignes' => collect($consultation['lignes'] ?? [])->map(fn ($l) => [
                    'programme' => $l['programme_name'],
                    'financier' => $this->money($l['financier']),
                    'nonFinancier' => $this->money($l['non_financier']),
                    'total' => $this->money($l['total_ligne']),
                ])->values()->all(),
                'totaux' => [
                    'financier' => $this->money($consultation['totaux']['financier'] ?? 0),
                    'nonFinancier' => $this->money($consultation['totaux']['non_financier'] ?? 0),
                    'general' => $this->money($consultation['totaux']['general'] ?? 0),
                ],
            ],
            'timeline' => collect($consultation['timeline'] ?? [])->map(fn (array $row) => [
                'label' => $row['label'] ?? '—',
                'kind' => $row['kind'] ?? 'event',
                'at' => $this->dt($row['at'] ?? null),
                'actor' => ($row['actor'] ?? null)?->name,
                'actorRole' => $row['actor_role'] ?? null,
                'html' => $row['body_html'] ?? null,
                'source' => $row['avis_source'] ?? null,
            ])->values()->all(),
            'pieces' => $dossier->fichiersDossier->map(fn ($f) => [
                'id' => $f->id,
                'name' => $f->original_name ?: basename((string) $f->name),
                'type' => $f->type?->name,
                'url' => $f->path,
                'uploadedBy' => $f->uploadedBy?->name,
                'uploadedAt' => $this->dt($f->uploaded_at),
            ])->values()->all(),
            'decision' => [
                'status' => $closureLabel,
                'pending' => $pending,
                'message' => match (true) {
                    $canAct => null,
                    $pending => 'Vous n\'êtes pas habilité à valider ou rejeter cette structuration pour ce dossier.',
                    default => null,
                },
                'validated' => $dossier->instruction_agence_validated_at
                    ? ['at' => $this->dt($dossier->instruction_agence_validated_at), 'by' => $dossier->instructionAgenceValidatedBy?->name, 'note' => $dossier->instruction_agence_closing_note]
                    : null,
                'rejected' => $dossier->instruction_agence_rejected_at
                    ? ['label' => 'Structuration rejetée par le chef d\'agence', 'at' => $this->dt($dossier->instruction_agence_rejected_at),
                        'by' => $dossier->instructionAgenceRejectedBy?->name, 'motif' => $dossier->instruction_agence_reject_motif]
                    : null,
            ],
            'actions' => $canAct ? $this->bundleActions($dossier) : [],
            'links' => array_values(array_filter([
                Route::has('ca.dossiers.show') ? ['label' => 'Fiche dossier complète', 'icon' => 'folder_open', 'href' => route('ca.dossiers.show', $dossier->token)] : null,
                $e?->token && Route::has('ca.entreprises.show') ? ['label' => 'Fiche client', 'icon' => 'storefront', 'href' => route('ca.entreprises.show', $e->token)] : null,
            ])),
        ];
    }

    /**
     * Mêmes formulaires que partials/ca-instruction-transmission-modals (motif et note optionnels).
     */
    private function bundleActions(Dossier $dossier): array
    {
        return [
            [
                'key' => 'approve',
                'label' => 'Valider la structuration',
                'icon' => 'task_alt',
                'variant' => 'primary',
                'title' => 'Valider la structuration',
                'description' => 'Confirmez la validation de la structuration soumise par le chef de filière (décision chef d\'agence).',
                'fields' => [
                    ['name' => 'closing_note', 'label' => 'Commentaire interne (optionnel)', 'type' => 'textarea', 'required' => false],
                ],
                'submits' => [['label' => 'Valider la structuration', 'url' => route('ca.workflow.instruction-dossiers.approve', $dossier->token), 'variant' => 'primary']],
            ],
            [
                'key' => 'reject',
                'label' => 'Rejeter la structuration',
                'icon' => 'block',
                'variant' => 'danger',
                'title' => 'Rejeter la structuration',
                'description' => 'Le chef de filière pourra corriger la structuration et la soumettre à nouveau.',
                'fields' => [
                    ['name' => 'reject_motif', 'label' => 'Motif du rejet', 'type' => 'textarea', 'required' => false],
                    ['name' => 'closing_note', 'label' => 'Commentaire interne (optionnel)', 'type' => 'textarea', 'required' => false],
                ],
                'submits' => [['label' => 'Rejeter la structuration', 'url' => route('ca.workflow.instruction-dossiers.reject', $dossier->token), 'variant' => 'danger']],
            ],
        ];
    }

    public function money($value): ?string
    {
        return ($value !== null && $value !== '') ? number_format((float) $value, 0, ',', ' ').' FCFA' : null;
    }

    public function dt($value): ?string
    {
        return $value ? Carbon::parse($value)->format('d/m/Y H:i') : null;
    }
}
