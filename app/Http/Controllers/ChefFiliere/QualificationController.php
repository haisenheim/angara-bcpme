<?php

namespace App\Http\Controllers\ChefFiliere;

use App\Http\Controllers\Controller;
use App\Models\DossierEntreeRelation;
use App\Models\Entreprise;
use App\Services\AnalyseCritiqueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class QualificationController extends Controller
{
    public function __construct(private readonly AnalyseCritiqueService $analyseCritiqueService) {}

    public function index()
    {
        $agenceId = auth()->user()->agence_id;
        $items = Entreprise::query()
            ->where('agence_id', $agenceId)
            ->whereNotNull('promu_client_at')
            ->where(function ($q) {
                $q->whereDoesntHave('dossierEntreeRelation')
                    ->orWhereHas('dossierEntreeRelation', fn ($e) => $e->whereNull('programmes_submitted_at'));
            })
            ->with(['agence', 'dossierEntreeRelation'])
            ->orderByDesc('promu_client_at')
            ->get();

        return view('ChefFiliere.qualifications.index', compact('items'));
    }

    public function show(string $token)
    {
        if (! Session::get('chef_filiere_dossier_consulte_'.$token)) {
            return redirect()->route('chef-filiere.clients.show', $token)
                ->with('info', 'Veuillez consulter le dossier client complet avant d\'accéder à la qualification.');
        }

        $item = Entreprise::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->whereNotNull('promu_client_at')
            ->with([
                'agence',
                'tiers.person',
                'tiers.company',
                'dossierEntreeRelation.programmeSelections.programme',
                'programmes',
                'promuClientUser',
                'prospectRejectedUser',
            ])
            ->firstOrFail();

        $eer = $this->getOrCreateEer($item);

        $lockedPendingCa = $eer->programmes_submitted_at && $eer->qualification_validated_by_agence_at === null;
        $lockedAfterValidation = (bool) $eer->qualification_validated_by_agence_at;
        $qualificationEditable = ! $lockedPendingCa && ! $lockedAfterValidation;

        return view('ChefFiliere.qualifications.show', compact('item', 'eer', 'qualificationEditable', 'lockedPendingCa', 'lockedAfterValidation'));
    }

    public function update(Request $request, string $token)
    {
        $item = Entreprise::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->whereNotNull('promu_client_at')
            ->firstOrFail();

        $eer = $this->getOrCreateEer($item);

        if ($eer->programmes_submitted_at && $eer->qualification_validated_by_agence_at === null) {
            Session::flash('info', 'Qualification transmise au chef d\'agence : modification impossible en attendant la validation.');

            return redirect()->route('chef-filiere.qualifications.show', $token);
        }

        if ($eer->qualification_validated_by_agence_at) {
            Session::flash('info', 'La qualification a été validée par le chef d\'agence. Les inscriptions aux programmes se font depuis la fiche client.');

            return redirect()->route('chef-filiere.clients.show', $token);
        }

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

    public function submit(string $token)
    {
        if ($r = $this->redirectIfDossierNonConsulte($token)) {
            return $r;
        }

        $item = Entreprise::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->whereNotNull('promu_client_at')
            ->with('dossierEntreeRelation')
            ->firstOrFail();

        $eer = $this->getOrCreateEer($item);
        if ($eer->qualification_completed_at === null) {
            Session::flash('info', 'Complétez d’abord la qualification du client.');

            return redirect()->route('chef-filiere.qualifications.show', $token);
        }

        if ($eer->programmes_submitted_at) {
            Session::flash('info', 'La qualification a déjà été transmise au chef d\'agence.');

            return redirect()->route('chef-filiere.qualifications.show', $token);
        }

        $eer->programmes_submitted_at = now();
        $eer->programmes_submitted_by_user_id = auth()->id();
        $eer->statut = DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION;
        $eer->instruction_validation_status = DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION;
        $eer->save();

        $this->analyseCritiqueService->syncChefFiliereQualification($item, $eer);
        Session::flash('success', 'Qualification soumise au chef d\'agence pour validation. Après validation, vous pourrez inscrire le client à un programme depuis sa fiche (un programme à la fois).');

        return redirect()->route('chef-filiere.qualifications.show', $token);
    }

    private function redirectIfDossierNonConsulte(string $token): ?\Illuminate\Http\RedirectResponse
    {
        if (! Session::get('chef_filiere_dossier_consulte_'.$token)) {
            return redirect()->route('chef-filiere.clients.show', $token)
                ->with('info', 'Veuillez consulter le dossier client complet avant la qualification.');
        }

        return null;
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
