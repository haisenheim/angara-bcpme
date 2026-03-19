<?php

namespace App\Http\Controllers\Ca;

use App\Http\Controllers\Controller;
use App\Http\Requests\RejectDossierEsgEvaluationRequest;
use App\Http\Requests\ValidateDossierEsgEvaluationRequest;
use App\Models\DossierEsgEvaluation;
use Illuminate\Http\Request;

class DossierEsgValidationController extends Controller
{
    /**
     * Liste des évaluations ESG soumises dans l'agence.
     */
    public function index()
    {
        $agenceId = auth()->user()->agence_id;
        $evaluations = DossierEsgEvaluation::where('agence_id', $agenceId)
            ->with(['dossier.entreprise', 'dossier.programme', 'analyste'])
            ->orderBy('submitted_at', 'desc')
            ->get();

        $submitted = $evaluations->where('status', DossierEsgEvaluation::STATUS_SUBMITTED);
        $validated = $evaluations->where('status', DossierEsgEvaluation::STATUS_VALIDATED);
        $rejected = $evaluations->where('status', DossierEsgEvaluation::STATUS_REJECTED);

        return view('Ca.Esg.esg_validations_index', compact('evaluations', 'submitted', 'validated', 'rejected'));
    }

    /**
     * Détail d'une évaluation.
     */
    public function show(DossierEsgEvaluation $evaluation)
    {
        $this->authorizeAgence($evaluation);
        $evaluation->load(['dossier.entreprise', 'dossier.programme', 'analyste', 'items']);

        return view('Ca.Esg.esg_validation_show', compact('evaluation'));
    }

    /**
     * Valider une évaluation.
     */
    public function validateEvaluation(ValidateDossierEsgEvaluationRequest $request, DossierEsgEvaluation $evaluation)
    {
        $this->authorizeAgence($evaluation);
        if (!$evaluation->isSubmitted()) {
            return redirect()->back()->with('error', 'Seule une évaluation soumise peut être validée.');
        }

        $evaluation->update([
            'status' => DossierEsgEvaluation::STATUS_VALIDATED,
            'validated_at' => now(),
            'validated_by' => auth()->id(),
            'validation_comment' => $request->validated('validation_comment'),
        ]);

        return redirect()->route('ca.esg-evaluations.index')
            ->with('success', 'Évaluation validée.');
    }

    /**
     * Rejeter une évaluation.
     */
    public function rejectEvaluation(RejectDossierEsgEvaluationRequest $request, DossierEsgEvaluation $evaluation)
    {
        $this->authorizeAgence($evaluation);
        if (!$evaluation->isSubmitted()) {
            return redirect()->back()->with('error', 'Seule une évaluation soumise peut être rejetée.');
        }

        $evaluation->update([
            'status' => DossierEsgEvaluation::STATUS_REJECTED,
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
            'rejection_reason' => $request->validated('rejection_reason'),
        ]);

        return redirect()->route('ca.esg-evaluations.index')
            ->with('success', 'Évaluation rejetée.');
    }

    /**
     * Dashboard synthétique ESG de l'agence.
     */
    public function dashboard()
    {
        $agenceId = auth()->user()->agence_id;
        $evaluations = DossierEsgEvaluation::where('agence_id', $agenceId)->get();

        $stats = [
            'total' => $evaluations->count(),
            'draft' => $evaluations->where('status', DossierEsgEvaluation::STATUS_DRAFT)->count(),
            'submitted' => $evaluations->where('status', DossierEsgEvaluation::STATUS_SUBMITTED)->count(),
            'validated' => $evaluations->where('status', DossierEsgEvaluation::STATUS_VALIDATED)->count(),
            'rejected' => $evaluations->where('status', DossierEsgEvaluation::STATUS_REJECTED)->count(),
            'avg_score' => round($evaluations->where('status', DossierEsgEvaluation::STATUS_VALIDATED)->avg('score_global') ?? 0, 1),
        ];

        $riskDistribution = $evaluations->where('status', DossierEsgEvaluation::STATUS_VALIDATED)
            ->groupBy('risk_level')
            ->map->count();

        return view('Ca.Esg.esg_dashboard', compact('stats', 'riskDistribution'));
    }

    protected function authorizeAgence(DossierEsgEvaluation $evaluation): void
    {
        if ($evaluation->agence_id != auth()->user()->agence_id) {
            abort(403, 'Cette évaluation n\'appartient pas à votre agence.');
        }
    }
}
