<?php

namespace App\Http\Controllers\Analyste;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDossierEsgEvaluationRequest;
use App\Http\Requests\SubmitDossierEsgEvaluationRequest;
use App\Http\Requests\UpdateDossierEsgEvaluationRequest;
use App\Models\Dossier;
use App\Models\DossierEsgEvaluation;
use App\Services\EvaluationScoringService;
use Illuminate\Http\Request;

class DossierEsgEvaluationController extends Controller
{
    public function __construct(
        protected EvaluationScoringService $scoringService
    ) {}

    /**
     * Liste des dossiers affectés avec état de l'évaluation ESG.
     */
    public function index()
    {
        $dossiers = Dossier::where('analyste_id', auth()->id())
            ->with(['entreprise', 'programme', 'esgEvaluation'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Analyste.Esg.esg_evaluations_index', compact('dossiers'));
    }

    /**
     * Détail de l'évaluation ESG du dossier.
     */
    public function show(Dossier $dossier)
    {
        $this->authorizeDossier($dossier);
        $evaluation = $dossier->esgEvaluation;

        return view('Analyste.Esg.esg_evaluation_show', compact('dossier', 'evaluation'));
    }

    /**
     * Formulaire de création de l'évaluation.
     */
    public function create(Dossier $dossier)
    {
        $this->authorizeDossier($dossier);
        if ($dossier->esgEvaluation) {
            return redirect()->route('analyste.dossiers.esg-evaluation.edit', $dossier);
        }

        $evaluation = null;
        return view('Analyste.Esg.esg_evaluation_form', compact('dossier', 'evaluation'));
    }

    /**
     * Enregistrer la nouvelle évaluation.
     */
    public function store(StoreDossierEsgEvaluationRequest $request, Dossier $dossier)
    {
        $this->authorizeDossier($dossier);
        if ($dossier->esgEvaluation) {
            return redirect()->route('analyste.dossiers.esg-evaluation.edit', $dossier);
        }

        $data = $this->buildEvaluationData($request->validated(), $dossier);
        $evaluation = DossierEsgEvaluation::create($data);

        $this->scoringService->rebuildScores($evaluation);

        return redirect()->route('analyste.dossiers.esg-evaluation.show', $dossier)
            ->with('success', 'Évaluation ESG créée.');
    }

    /**
     * Formulaire de modification.
     */
    public function edit(Dossier $dossier)
    {
        $this->authorizeDossier($dossier);
        $evaluation = $dossier->esgEvaluation;
        if (!$evaluation) {
            return redirect()->route('analyste.dossiers.esg-evaluation.create', $dossier);
        }
        if (!$evaluation->canBeEdited()) {
            return redirect()->route('analyste.dossiers.esg-evaluation.show', $dossier)
                ->with('error', 'Cette évaluation ne peut plus être modifiée.');
        }

        return view('Analyste.Esg.esg_evaluation_form', compact('dossier', 'evaluation'));
    }

    /**
     * Mettre à jour l'évaluation.
     */
    public function update(UpdateDossierEsgEvaluationRequest $request, Dossier $dossier)
    {
        $this->authorizeDossier($dossier);
        $evaluation = $dossier->esgEvaluation;
        if (!$evaluation || !$evaluation->canBeEdited()) {
            return redirect()->route('analyste.dossiers.esg-evaluation.index')
                ->with('error', 'Action non autorisée.');
        }

        $evaluation->update($request->validated());
        $evaluation->update(['updated_by' => auth()->id()]);
        $this->scoringService->rebuildScores($evaluation);

        return redirect()->route('analyste.dossiers.esg-evaluation.show', $dossier)
            ->with('success', 'Évaluation mise à jour.');
    }

    /**
     * Soumettre l'évaluation.
     */
    public function submit(SubmitDossierEsgEvaluationRequest $request, Dossier $dossier)
    {
        $this->authorizeDossier($dossier);
        $evaluation = $dossier->esgEvaluation;
        if (!$evaluation || !$evaluation->isDraft()) {
            return redirect()->back()->with('error', 'Seule une évaluation en brouillon peut être soumise.');
        }

        $evaluation->update([
            'status' => DossierEsgEvaluation::STATUS_SUBMITTED,
            'submitted_at' => now(),
            'submitted_by' => auth()->id(),
        ]);

        return redirect()->route('analyste.dossiers.esg-evaluation.show', $dossier)
            ->with('success', 'Évaluation soumise pour validation.');
    }

    /**
     * Recalculer les scores.
     */
    public function rebuildScores(Dossier $dossier)
    {
        $this->authorizeDossier($dossier);
        $evaluation = $dossier->esgEvaluation;
        if (!$evaluation || !$evaluation->canBeEdited()) {
            return redirect()->back()->with('error', 'Action non autorisée.');
        }

        $this->scoringService->rebuildScores($evaluation);

        return redirect()->back()->with('success', 'Scores recalculés.');
    }

    protected function authorizeDossier(Dossier $dossier): void
    {
        if ($dossier->analyste_id != auth()->id()) {
            abort(403, 'Ce dossier ne vous est pas affecté.');
        }
    }

    protected function buildEvaluationData(array $data, Dossier $dossier): array
    {
        return array_merge($data, [
            'dossier_id' => $dossier->id,
            'entreprise_id' => $dossier->entreprise_id,
            'programme_id' => $dossier->programme_id,
            'agence_id' => $dossier->agence_id,
            'gestionnaire_id' => $dossier->gestionnaire_id,
            'analyste_id' => $dossier->analyste_id,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);
    }
}
