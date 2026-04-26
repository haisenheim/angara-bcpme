<?php

namespace App\Http\Controllers\Juridique;

use App\Http\Controllers\Concerns\AppliesEntrepriseListIndexFilters;
use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\DossierEntreeRelation;
use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    use AppliesEntrepriseListIndexFilters;

    /**
     * Liste des entreprises (hors prospect) — vue portefeuille responsable juridique.
     */
    public function index(Request $request)
    {
        $query = Entreprise::query()
            ->where('prospect', 0)
            ->with(['agence', 'dossierEntreeRelation', 'gestionnaire'])
            ->orderBy('name');

        $q = trim((string) $request->query('q', ''));
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%'.$q.'%')
                    ->orWhere('niu', 'like', '%'.$q.'%')
                    ->orWhere('rccm', 'like', '%'.$q.'%');
            });
        }

        $structurationStatus = DossierEntreeRelation::normalizeClientStructurationFilter($request->query('client_structuration_status'));
        if ($structurationStatus) {
            $query->whereClientStructurationStatus($structurationStatus);
        }

        $filters = $this->parsePromuClientAndAgenceGestionnaireFilters($request, true);
        $this->applyPromuAgenceGestionnaireFiltersToQuery($query, $filters, true);

        $items = $query->paginate(25)->withQueryString();

        $agenceIds = Entreprise::query()->where('prospect', 0)->whereNotNull('agence_id')->distinct()->pluck('agence_id');
        $gestionnaireIds = Entreprise::query()->where('prospect', 0)->whereNotNull('gestionnaire_id')->distinct()->pluck('gestionnaire_id');
        $agences = Agence::query()->whereIn('id', $agenceIds)->orderBy('name')->get(['id', 'name']);
        $gestionnaires = User::query()->whereIn('id', $gestionnaireIds)->orderBy('name')->get(['id', 'name']);

        return view('Juridique.Companies.index', compact('items', 'q', 'structurationStatus', 'agences', 'gestionnaires'));
    }

    /**
     * Fiche entreprise (lecture seule).
     */
    public function show(string $token)
    {
        $item = Entreprise::where('token', $token)->where('prospect', 0)->firstOrFail();
        $item->load([
            'agence',
            'region',
            'arrondissement',
            'promuClientUser',
            'prospectRejectedUser',
            'dossierEntreeRelation.qualificationUser',
            'dossierEntreeRelation.programmesSubmittedBy',
            'dossierEntreeRelation.instructionValidatedBy',
            'dossierEntreeRelation.programmeSelections.programme',
            'dossierEntreeRelation.programmeSelections.instructionDossier',
        ]);

        return view('Juridique.Companies.show', compact('item'));
    }
}
