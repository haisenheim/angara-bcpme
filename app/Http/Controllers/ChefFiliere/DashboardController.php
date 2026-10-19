<?php

namespace App\Http\Controllers\ChefFiliere;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\DossierEntreeRelation;
use App\Models\Entreprise;

class DashboardController extends Controller
{
    use \App\Http\Controllers\Concerns\RendersInertiaDashboard;

    public function index()
    {
        $qualifShow = fn (?string $token) => $token ? $this->routeIfExists('chef-filiere.qualifications.show', $token) : null;
        $qualifIndex = $this->routeIfExists('chef-filiere.qualifications.index');

        return $this->renderInertiaDashboard('Tableau de bord — chef de filière', [
            ['key' => 'pending_qualif', 'label' => 'Structurations en attente', 'icon' => 'account_tree', 'tone' => 'warning', 'href' => $qualifIndex],
            ['key' => 'clients_count', 'label' => 'Clients (agence)', 'icon' => 'storefront', 'href' => $this->routeIfExists('chef-filiere.clients.index')],
            ['key' => 'instruction_pending', 'label' => 'Instruction — en attente (chef d\'agence)', 'icon' => 'pending_actions',
                'href' => $this->routeIfExists('chef-filiere.instructions.pending')],
            ['key' => 'instruction_en_cours', 'label' => 'Dossiers d\'instruction en cours', 'icon' => 'autorenew',
                'href' => $this->routeIfExists('chef-filiere.instructions.in-progress')],
        ], fn () => $this->getStats()->getData(true), null, [
            'subtitle' => 'Pilotage de la structuration et des dossiers d\'instruction de votre agence.',
            'plainLinks' => true,
            'widgets' => [
                ['key' => 'structuration', 'type' => 'stacked', 'title' => 'Structuration des clients', 'span' => 'third',
                    'link' => $qualifIndex ? ['label' => 'Ouvrir', 'href' => $qualifIndex] : null],
                ['key' => 'a_structurer', 'type' => 'list', 'title' => 'À structurer (priorité)', 'span' => 'third', 'empty' => 'Aucun client à structurer.',
                    'link' => ['label' => 'Voir les clients', 'href' => $this->routeIfExists('chef-filiere.clients.index')]],
                ['key' => 'rejets', 'type' => 'list', 'title' => 'Rejets agence à corriger', 'span' => 'third', 'empty' => 'Aucun rejet à corriger.',
                    'link' => $qualifIndex ? ['label' => 'Ouvrir la file', 'href' => $qualifIndex] : null],
                ['key' => 'raccourcis', 'type' => 'links', 'title' => 'Documentation', 'span' => 'full',
                    'items' => array_values(array_filter([
                        ['label' => 'Référentiel des programmes (fiches et critères d\'éligibilité)', 'icon' => 'volunteer_activism',
                            'href' => $this->routeIfExists('chef-filiere.programmes.index')],
                    ], fn ($l) => $l['href'] !== null))],
            ],
            'widgetData' => function () use ($qualifShow) {
                $data = $this->getInsights()->getData(true);
                $s = $data['structuration'] ?? [];

                return [
                    'structuration' => [
                        ['label' => 'Structurés', 'value' => $s['structure'] ?? 0, 'tone' => 'success'],
                        ['label' => 'En cours', 'value' => $s['en_cours'] ?? 0, 'tone' => 'info'],
                        ['label' => 'En attente', 'value' => $s['attente'] ?? 0, 'tone' => 'neutral'],
                        ['label' => 'Rejetés', 'value' => $s['rejetee'] ?? 0, 'tone' => 'danger'],
                    ],
                    'a_structurer' => collect($data['to_structure'] ?? [])->map(fn ($e) => [
                        'title' => $e['name'] ?? '—',
                        'meta' => ! empty($e['promu_client_at']) ? 'Client depuis le '.\Illuminate\Support\Carbon::parse($e['promu_client_at'])->format('d/m/Y') : null,
                        'href' => $qualifShow($e['token'] ?? null),
                    ])->values()->all(),
                    'rejets' => collect($data['rejected_to_fix'] ?? [])->map(fn ($e) => [
                        'title' => $e['name'] ?? '—',
                        'subtitle' => $e['dossier_entree_relation']['qualification_reject_motif'] ?? null,
                        'badge' => ['label' => 'Rejeté', 'variant' => 'danger'],
                        'href' => $qualifShow($e['token'] ?? null),
                    ])->values()->all(),
                ];
            },
        ]);
    }

    public function getStats()
    {
        $agenceId = auth()->user()->agence_id;

        $pendingQualif = Entreprise::query()
            ->where('agence_id', $agenceId)
            ->whereNotNull('promu_client_at')
            ->where(function ($q) {
                $q->whereDoesntHave('dossierEntreeRelation')
                    ->orWhereHas('dossierEntreeRelation', fn ($e) => $e->whereNull('programmes_submitted_at'));
            })
            ->count();

        $clientsCount = Entreprise::query()
            ->where('agence_id', $agenceId)
            ->whereNotNull('promu_client_at')
            ->count();

        $instructionPending = DossierEntreeRelation::query()
            ->where('statut', DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION)
            ->whereNull('qualification_validated_by_agence_at')
            ->whereHas('entreprise', fn ($q) => $q->where('agence_id', $agenceId))
            ->count();

        $instructionEnCours = Dossier::query()
            ->where('agence_id', $agenceId)
            ->whereNull('instruction_closure_validated_at')
            ->whereNull('instruction_closure_rejected_at')
            ->count();

        return response()->json([
            'pending_qualif' => $pendingQualif,
            'clients_count' => $clientsCount,
            'instruction_pending' => $instructionPending,
            'instruction_en_cours' => $instructionEnCours,
        ]);
    }

    public function getInsights()
    {
        $agenceId = auth()->user()->agence_id;

        // Répartition "structuration client" (EER) basée sur le scope existant.
        $struct = [
            'structure' => Entreprise::query()
                ->where('agence_id', $agenceId)
                ->whereNotNull('promu_client_at')
                ->whereClientStructurationStatus(DossierEntreeRelation::CLIENT_STRUCT_STATUS_STRUCTURE)
                ->count(),
            'en_cours' => Entreprise::query()
                ->where('agence_id', $agenceId)
                ->whereNotNull('promu_client_at')
                ->whereClientStructurationStatus(DossierEntreeRelation::CLIENT_STRUCT_STATUS_EN_COURS)
                ->count(),
            'attente' => Entreprise::query()
                ->where('agence_id', $agenceId)
                ->whereNotNull('promu_client_at')
                ->whereClientStructurationStatus(DossierEntreeRelation::CLIENT_STRUCT_STATUS_ATTENTE)
                ->count(),
            'rejetee' => Entreprise::query()
                ->where('agence_id', $agenceId)
                ->whereNotNull('promu_client_at')
                ->whereClientStructurationStatus(DossierEntreeRelation::CLIENT_STRUCT_STATUS_REJETEE)
                ->count(),
        ];

        // File "à structurer" (clients promus sans structuration complétée).
        $toStructure = Entreprise::query()
            ->where('agence_id', $agenceId)
            ->whereNotNull('promu_client_at')
            ->where(function ($q) {
                $q->whereDoesntHave('dossierEntreeRelation')
                    ->orWhereHas('dossierEntreeRelation', fn ($eer) => $eer->whereNull('qualification_completed_at'));
            })
            ->orderByDesc('promu_client_at')
            ->limit(10)
            ->get(['id', 'token', 'name', 'promu_client_at']);

        // File "rejets agence à corriger" (structuration refusée, non resoumise).
        $rejectedToFix = Entreprise::query()
            ->where('agence_id', $agenceId)
            ->whereNotNull('promu_client_at')
            ->whereHas('dossierEntreeRelation', function ($eer) {
                $eer->whereNotNull('qualification_rejected_by_agence_at')
                    ->whereNull('qualification_validated_by_agence_at')
                    ->whereNull('programmes_submitted_at');
            })
            ->with(['dossierEntreeRelation:id,entreprise_id,qualification_rejected_by_agence_at,qualification_reject_motif'])
            ->orderByDesc('promu_client_at')
            ->limit(10)
            ->get(['id', 'token', 'name', 'promu_client_at']);

        return response()->json([
            'structuration' => $struct,
            'to_structure' => $toStructure,
            'rejected_to_fix' => $rejectedToFix,
        ]);
    }
}
