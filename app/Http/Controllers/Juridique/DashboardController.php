<?php

namespace App\Http\Controllers\Juridique;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Entreprise;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('Juridique.dashboard');
    }

    public function getStats()
    {
        $pendingProspects = Entreprise::query()
            ->where('prospect', true)
            ->whereNotNull('prospect_submitted_at')
            ->whereNull('juridique_avis_at')
            ->count();

        $baseDossiers = Dossier::query()
            ->whereNotNull('juridique_instruction_submitted_at')
            ->whereNull('juridique_submitted_to_engagements_at');

        return response()->json([
            'pending_prospects' => $pendingProspects,
            'instruction_dossiers' => (clone $baseDossiers)->count(),
            'a_affecter_analyste' => (clone $baseDossiers)->whereNull('juridique_analyste_assigned_at')->count(),
            'avis_analyste_a_traiter' => (clone $baseDossiers)
                ->whereNotNull('juridique_analyste_submitted_to_reju_at')
                ->whereNull('juridique_responsable_avis_at')
                ->count(),
            'a_soumettre_engagements' => (clone $baseDossiers)
                ->whereNotNull('juridique_responsable_avis_at')
                ->whereNull('juridique_submitted_to_engagements_at')
                ->count(),
        ]);
    }

    public function getTodos()
    {
        $prospects = Entreprise::query()
            ->where('prospect', true)
            ->whereNotNull('prospect_submitted_at')
            ->whereNull('juridique_avis_at')
            ->orderBy('prospect_submitted_at', 'asc')
            ->limit(10)
            ->get(['token', 'name', 'prospect_submitted_at'])
            ->map(function (Entreprise $e) {
                $dt = $e->prospect_submitted_at instanceof Carbon ? $e->prospect_submitted_at : null;
                return [
                    'token' => $e->token,
                    'name' => $e->name ?? '—',
                    'when_human' => $dt?->diffForHumans(),
                ];
            })
            ->values();

        $dossiers = Dossier::query()
            ->whereNotNull('juridique_instruction_submitted_at')
            ->whereNull('juridique_submitted_to_engagements_at')
            ->with(['entreprise', 'programme', 'instructionProgrammes.programme'])
            ->orderBy('juridique_instruction_submitted_at', 'asc')
            ->limit(10)
            ->get()
            ->map(function (Dossier $d) {
                return [
                    'token' => $d->token,
                    'entreprise' => $d->entreprise?->name ?? '—',
                    'programmes' => method_exists($d, 'programmesLabel') ? $d->programmesLabel() : ($d->programme?->name ?? '—'),
                ];
            })
            ->values();

        return response()->json([
            'prospects' => $prospects,
            'dossiers' => $dossiers,
        ]);
    }
}
