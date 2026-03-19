<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEvaluationSettingRequest;
use App\Models\EvaluationSetting;
use Illuminate\Http\Request;

class EvaluationSettingController extends Controller
{
    public function index()
    {
        $settings = EvaluationSetting::orderBy('group')->orderBy('key')->paginate(30);
        return view('Admin.Esg.settings_index', compact('settings'));
    }

    public function edit(EvaluationSetting $evaluationSetting)
    {
        return view('Admin.Esg.settings_form', compact('evaluationSetting'));
    }

    public function update(UpdateEvaluationSettingRequest $request, EvaluationSetting $evaluationSetting)
    {
        $evaluationSetting->update($request->validated());
        return redirect()->route('admin.evaluation-settings.index')
            ->with('success', 'Réglage mis à jour.');
    }

    public function create()
    {
        return view('Admin.Esg.settings_form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => ['required', 'string', 'max:100', 'unique:evaluation_settings,key'],
            'value' => ['nullable'],
            'type' => ['nullable', 'string', 'in:string,json,boolean,integer,decimal'],
            'description' => ['nullable', 'string'],
            'group' => ['nullable', 'string', 'max:50'],
        ]);
        EvaluationSetting::create($request->all());
        return redirect()->route('admin.evaluation-settings.index')
            ->with('success', 'Réglage créé.');
    }

    public function destroy(EvaluationSetting $evaluationSetting)
    {
        $evaluationSetting->delete();
        return redirect()->route('admin.evaluation-settings.index')
            ->with('success', 'Réglage supprimé.');
    }
}
