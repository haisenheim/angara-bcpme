<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvaluationScoreThresholdRequest;
use App\Http\Requests\UpdateEvaluationScoreThresholdRequest;
use App\Models\EvaluationScoreThreshold;
use Illuminate\Http\Request;

class EvaluationScoreThresholdController extends Controller
{
    public function index()
    {
        $thresholds = EvaluationScoreThreshold::orderBy('threshold_type')->orderBy('min_value')->paginate(20);
        return view('Admin.Esg.thresholds_index', compact('thresholds'));
    }

    public function create()
    {
        return view('Admin.Esg.thresholds_form');
    }

    public function store(StoreEvaluationScoreThresholdRequest $request)
    {
        EvaluationScoreThreshold::create($request->validated());
        return redirect()->route('admin.evaluation-thresholds.index')
            ->with('success', 'Seuil créé.');
    }

    public function show(EvaluationScoreThreshold $evaluationThreshold)
    {
        return view('Admin.Esg.thresholds_show', compact('evaluationThreshold'));
    }

    public function edit(EvaluationScoreThreshold $evaluationThreshold)
    {
        return view('Admin.Esg.thresholds_form', compact('evaluationThreshold'));
    }

    public function update(UpdateEvaluationScoreThresholdRequest $request, EvaluationScoreThreshold $evaluationThreshold)
    {
        $evaluationThreshold->update($request->validated());
        return redirect()->route('admin.evaluation-thresholds.index')
            ->with('success', 'Seuil mis à jour.');
    }

    public function destroy(EvaluationScoreThreshold $evaluationThreshold)
    {
        $evaluationThreshold->delete();
        return redirect()->route('admin.evaluation-thresholds.index')
            ->with('success', 'Seuil supprimé.');
    }
}
