<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvaluationIndicatorRequest;
use App\Http\Requests\UpdateEvaluationIndicatorRequest;
use App\Models\EvaluationCategory;
use App\Models\EvaluationFramework;
use App\Models\EvaluationIndicator;
use Illuminate\Http\Request;

class EvaluationIndicatorController extends Controller
{
    public function index()
    {
        $indicators = EvaluationIndicator::with(['framework', 'category'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);
        return view('Admin.Esg.indicators_index', compact('indicators'));
    }

    public function create()
    {
        $frameworks = EvaluationFramework::where('is_active', true)->orderBy('name')->get();
        $categories = EvaluationCategory::where('is_active', true)->orderBy('name')->get();
        return view('Admin.Esg.indicators_form', compact('frameworks', 'categories'));
    }

    public function store(StoreEvaluationIndicatorRequest $request)
    {
        EvaluationIndicator::create($request->validated());
        return redirect()->route('admin.evaluation-indicators.index')
            ->with('success', 'Indicateur créé.');
    }

    public function show(EvaluationIndicator $evaluationIndicator)
    {
        $evaluationIndicator->load(['framework', 'category']);
        return view('Admin.Esg.indicators_show', compact('evaluationIndicator'));
    }

    public function edit(EvaluationIndicator $evaluationIndicator)
    {
        $frameworks = EvaluationFramework::where('is_active', true)->orderBy('name')->get();
        $categories = EvaluationCategory::where('is_active', true)->orderBy('name')->get();
        return view('Admin.Esg.indicators_form', compact('evaluationIndicator', 'frameworks', 'categories'));
    }

    public function update(UpdateEvaluationIndicatorRequest $request, EvaluationIndicator $evaluationIndicator)
    {
        $evaluationIndicator->update($request->validated());
        return redirect()->route('admin.evaluation-indicators.index')
            ->with('success', 'Indicateur mis à jour.');
    }

    public function destroy(EvaluationIndicator $evaluationIndicator)
    {
        $evaluationIndicator->delete();
        return redirect()->route('admin.evaluation-indicators.index')
            ->with('success', 'Indicateur supprimé.');
    }
}
