<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvaluationCategoryRequest;
use App\Http\Requests\UpdateEvaluationCategoryRequest;
use App\Models\EvaluationCategory;
use App\Models\EvaluationFramework;
use Illuminate\Http\Request;

class EvaluationCategoryController extends Controller
{
    public function index()
    {
        $categories = EvaluationCategory::with('framework')->orderBy('sort_order')->orderBy('name')->paginate(20);
        return view('Admin.Esg.categories_index', compact('categories'));
    }

    public function create()
    {
        $frameworks = EvaluationFramework::where('is_active', true)->orderBy('name')->get();
        return view('Admin.Esg.categories_form', compact('frameworks'));
    }

    public function store(StoreEvaluationCategoryRequest $request)
    {
        EvaluationCategory::create($request->validated());
        return redirect()->route('admin.evaluation-categories.index')
            ->with('success', 'Catégorie créée.');
    }

    public function show(EvaluationCategory $evaluationCategory)
    {
        $evaluationCategory->load(['framework', 'indicators']);
        return view('Admin.Esg.categories_show', compact('evaluationCategory'));
    }

    public function edit(EvaluationCategory $evaluationCategory)
    {
        $frameworks = EvaluationFramework::where('is_active', true)->orderBy('name')->get();
        return view('Admin.Esg.categories_form', compact('evaluationCategory', 'frameworks'));
    }

    public function update(UpdateEvaluationCategoryRequest $request, EvaluationCategory $evaluationCategory)
    {
        $evaluationCategory->update($request->validated());
        return redirect()->route('admin.evaluation-categories.index')
            ->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(EvaluationCategory $evaluationCategory)
    {
        $evaluationCategory->delete();
        return redirect()->route('admin.evaluation-categories.index')
            ->with('success', 'Catégorie supprimée.');
    }
}
