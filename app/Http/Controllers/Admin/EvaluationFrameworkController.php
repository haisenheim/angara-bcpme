<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvaluationFrameworkRequest;
use App\Http\Requests\UpdateEvaluationFrameworkRequest;
use App\Models\EvaluationFramework;
use Illuminate\Http\Request;

class EvaluationFrameworkController extends Controller
{
    public function index()
    {
        $frameworks = EvaluationFramework::orderBy('name')->paginate(20);
        return view('Admin.Esg.frameworks_index', compact('frameworks'));
    }

    public function create()
    {
        return view('Admin.Esg.frameworks_form');
    }

    public function store(StoreEvaluationFrameworkRequest $request)
    {
        EvaluationFramework::create($request->validated());
        return redirect()->route('admin.evaluation-frameworks.index')
            ->with('success', 'Framework créé.');
    }

    public function show(EvaluationFramework $evaluationFramework)
    {
        $evaluationFramework->load(['categories', 'indicators']);
        return view('Admin.Esg.frameworks_show', compact('evaluationFramework'));
    }

    public function edit(EvaluationFramework $evaluationFramework)
    {
        return view('Admin.Esg.frameworks_form', compact('evaluationFramework'));
    }

    public function update(UpdateEvaluationFrameworkRequest $request, EvaluationFramework $evaluationFramework)
    {
        $evaluationFramework->update($request->validated());
        return redirect()->route('admin.evaluation-frameworks.index')
            ->with('success', 'Framework mis à jour.');
    }

    public function destroy(EvaluationFramework $evaluationFramework)
    {
        $evaluationFramework->delete();
        return redirect()->route('admin.evaluation-frameworks.index')
            ->with('success', 'Framework supprimé.');
    }
}
