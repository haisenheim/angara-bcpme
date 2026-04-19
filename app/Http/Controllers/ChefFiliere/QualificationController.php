<?php

namespace App\Http\Controllers\ChefFiliere;

use App\Http\Controllers\Controller;
use App\Models\DossierEntreeRelation;
use App\Models\DossierEntreeRelationProgramme;
use App\Models\Entreprise;
use App\Models\Programme;
use App\Services\AnalyseCritiqueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class QualificationController extends Controller
{
    public function __construct(private readonly AnalyseCritiqueService $analyseCritiqueService)
    {
    }

    public function index()
    {
        $items = Entreprise::query()
            ->whereNotNull('promu_client_at')
            ->with(['agence', 'dossierEntreeRelation'])
            ->orderByDesc('promu_client_at')
            ->get();

        return view('ChefFiliere.qualifications.index', compact('items'));
    }

    public function show(string $token)
    {
        $item = Entreprise::query()
            ->where('token', $token)
            ->whereNotNull('promu_client_at')
            ->with([
                'agence',
                'tiers.person',
                'tiers.company',
                'dossierEntreeRelation.programmeSelections.programme',
                'programmes',
            ])
            ->firstOrFail();

        $eer = $this->getOrCreateEer($item);
        $programmes = Programme::orderBy('name')->get(['id', 'name']);
        $checklist = $item->piecesExigiblesChecklist();

        return view('ChefFiliere.qualifications.show', compact('item', 'eer', 'programmes', 'checklist'));
    }

    public function update(Request $request, string $token)
    {
        $item = Entreprise::query()
            ->where('token', $token)
            ->whereNotNull('promu_client_at')
            ->firstOrFail();

        $eer = $this->getOrCreateEer($item);
        $data = $request->validate([
            'analyse_strategique' => 'nullable|string|max:20000',
            'analyse_operationnelle' => 'nullable|string|max:20000',
            'analyse_eligibilite' => 'nullable|string|max:20000',
            'identification_besoins' => 'nullable|string|max:20000',
            'qualification_notes' => 'nullable|string|max:20000',
            'besoin_financement' => 'nullable|boolean',
            'besoin_accompagnement' => 'nullable|boolean',
            'besoin_structuration' => 'nullable|boolean',
        ]);

        $eer->fill($data);
        $eer->besoin_financement = $request->boolean('besoin_financement');
        $eer->besoin_accompagnement = $request->boolean('besoin_accompagnement');
        $eer->besoin_structuration = $request->boolean('besoin_structuration');
        $eer->qualification_completed_at = now();
        $eer->qualification_user_id = auth()->id();
        $eer->statut = DossierEntreeRelation::STATUT_QUALIFIE;
        $eer->save();

        $this->analyseCritiqueService->syncChefFiliereQualification($item, $eer);

        Session::flash('success', 'Qualification et identification des besoins enregistrées.');

        return redirect()->route('chef-filiere.qualifications.show', $token);
    }

    public function saveProgrammes(Request $request, string $token)
    {
        $item = Entreprise::query()
            ->where('token', $token)
            ->whereNotNull('promu_client_at')
            ->firstOrFail();

        $eer = $this->getOrCreateEer($item);
        $validated = $request->validate([
            'programmes' => 'nullable|array',
            'programmes.*' => 'integer|exists:programmes,id',
            'type_appui' => 'nullable|array',
            'type_appui.*' => 'nullable|in:financier,non_financier,mixte',
            'programme_notes' => 'nullable|array',
            'programme_notes.*' => 'nullable|string|max:5000',
        ]);

        $selected = collect($validated['programmes'] ?? [])->map(fn ($id) => (int) $id)->unique()->values();
        $existing = $eer->programmeSelections()->get()->keyBy('programme_id');

        foreach ($existing as $programmeId => $selection) {
            if (! $selected->contains((int) $programmeId) && $selection->instruction_dossier_id === null) {
                $selection->delete();
            }
        }

        foreach ($selected as $programmeId) {
            $selection = $existing->get($programmeId) ?? new DossierEntreeRelationProgramme([
                'dossier_entree_relation_id' => $eer->id,
                'programme_id' => $programmeId,
            ]);
            $selection->type_appui = $validated['type_appui'][$programmeId] ?? DossierEntreeRelationProgramme::TYPE_FINANCIER;
            $selection->notes = $validated['programme_notes'][$programmeId] ?? null;
            if (! $selection->exists) {
                $selection->statut = DossierEntreeRelationProgramme::STATUT_PROPOSE;
            }
            $selection->save();
        }

        Session::flash('success', 'Affectation programme mise à jour.');

        return redirect()->route('chef-filiere.qualifications.show', $token);
    }

    public function submit(string $token)
    {
        $item = Entreprise::query()
            ->where('token', $token)
            ->whereNotNull('promu_client_at')
            ->with('dossierEntreeRelation.programmeSelections')
            ->firstOrFail();

        $eer = $this->getOrCreateEer($item);
        if ($eer->qualification_completed_at === null) {
            Session::flash('info', 'Complétez d’abord la qualification du client.');

            return redirect()->route('chef-filiere.qualifications.show', $token);
        }

        if ($eer->programmeSelections()->count() === 0) {
            Session::flash('info', 'Sélectionnez au moins un programme avant soumission.');

            return redirect()->route('chef-filiere.qualifications.show', $token);
        }

        $eer->programmes_submitted_at = now();
        $eer->programmes_submitted_by_user_id = auth()->id();
        $eer->statut = DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION;
        $eer->instruction_validation_status = DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION;
        $eer->save();

        $eer->programmeSelections()->each(function (DossierEntreeRelationProgramme $selection) {
            $selection->submitted_at = now();
            $selection->statut = DossierEntreeRelationProgramme::STATUT_SOUMIS;
            $selection->save();
        });

        $this->analyseCritiqueService->syncChefFiliereQualification($item, $eer);
        Session::flash('success', 'Dossier client soumis au chef d\'agence pour validation et creation des dossiers d\'instruction.');

        return redirect()->route('chef-filiere.qualifications.show', $token);
    }

    private function getOrCreateEer(Entreprise $entreprise): DossierEntreeRelation
    {
        return DossierEntreeRelation::firstOrCreate(
            ['entreprise_id' => $entreprise->id],
            [
                'token' => sha1('eer-'.$entreprise->id.'-'.microtime(true)),
                'statut' => DossierEntreeRelation::STATUT_CLIENT_VALIDE,
            ]
        );
    }
}
