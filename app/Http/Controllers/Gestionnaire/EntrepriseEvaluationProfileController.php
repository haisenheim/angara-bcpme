<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEntrepriseEvaluationProfileRequest;
use App\Http\Requests\UpdateEntrepriseEvaluationProfileRequest;
use App\Models\Entreprise;
use App\Models\EntrepriseEvaluationProfile;
use Illuminate\Http\Request;

class EntrepriseEvaluationProfileController extends Controller
{
    /**
     * Liste des entreprises du portefeuille avec état du profil d'évaluation.
     */
    public function index()
    {
        $user = auth()->user();
        $entreprises = Entreprise::with(['dossiers.esgEvaluation'])->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)->orWhere('gestionnaire_id', $user->id);
        })->where('prospect', 0)->orderBy('name')->get();

        $profiles = EntrepriseEvaluationProfile::whereIn('entreprise_id', $entreprises->pluck('id'))->get()->keyBy('entreprise_id');

        return view('Gestionnaire.Esg.evaluation_profiles_index', compact('entreprises', 'profiles'));
    }

    /**
     * Afficher le profil d'évaluation d'une entreprise.
     */
    public function show(Entreprise $entreprise)
    {
        $this->authorizePortfolio($entreprise);
        $entreprise->load(['evaluationProfile', 'dossiers.esgEvaluation', 'dossiers.programme']);
        $profile = $entreprise->evaluationProfile;

        return view('Gestionnaire.Esg.evaluation_profile_show', compact('entreprise', 'profile'));
    }

    /**
     * Formulaire de création du profil.
     */
    public function create(Entreprise $entreprise)
    {
        $this->authorizePortfolio($entreprise);
        if ($entreprise->evaluationProfile) {
            return redirect()->route('gestionnaire.entreprises.evaluation-profile.edit', $entreprise);
        }

        $profile = null;
        return view('Gestionnaire.Esg.evaluation_profile_form', compact('entreprise', 'profile'));
    }

    /**
     * Enregistrer le nouveau profil.
     */
    public function store(StoreEntrepriseEvaluationProfileRequest $request, Entreprise $entreprise)
    {
        $this->authorizePortfolio($entreprise);
        if ($entreprise->evaluationProfile) {
            return redirect()->route('gestionnaire.entreprises.evaluation-profile.edit', $entreprise);
        }

        $data = $request->validated();
        $data['entreprise_id'] = $entreprise->id;
        $data['gestionnaire_id'] = auth()->id();
        $data['agence_id'] = auth()->user()->agence_id;
        $data['last_updated_by'] = auth()->id();

        EntrepriseEvaluationProfile::create($data);

        return redirect()->route('gestionnaire.entreprises.evaluation-profile.show', $entreprise)
            ->with('success', 'Profil d\'évaluation créé avec succès.');
    }

    /**
     * Formulaire de modification.
     */
    public function edit(Entreprise $entreprise)
    {
        $this->authorizePortfolio($entreprise);
        $profile = $entreprise->evaluationProfile;
        if (!$profile) {
            return redirect()->route('gestionnaire.entreprises.evaluation-profile.create', $entreprise);
        }

        return view('Gestionnaire.Esg.evaluation_profile_form', compact('entreprise', 'profile'));
    }

    /**
     * Mettre à jour le profil.
     */
    public function update(UpdateEntrepriseEvaluationProfileRequest $request, Entreprise $entreprise)
    {
        $this->authorizePortfolio($entreprise);
        $profile = $entreprise->evaluationProfile;
        if (!$profile) {
            return redirect()->route('gestionnaire.entreprises.evaluation-profile.create', $entreprise);
        }

        $data = $request->validated();
        $data['last_updated_by'] = auth()->id();

        $profile->update($data);

        return redirect()->route('gestionnaire.entreprises.evaluation-profile.show', $entreprise)
            ->with('success', 'Profil d\'évaluation mis à jour.');
    }

    protected function authorizePortfolio(Entreprise $entreprise): void
    {
        $user = auth()->user();
        if ($entreprise->user_id != $user->id && $entreprise->gestionnaire_id != $user->id) {
            abort(403, 'Cette entreprise n\'appartient pas à votre portefeuille.');
        }
    }
}
