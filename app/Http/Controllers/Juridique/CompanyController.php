<?php

namespace App\Http\Controllers\Juridique;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Liste des entreprises (hors prospect) — vue portefeuille responsable juridique.
     */
    public function index(Request $request)
    {
        $query = Entreprise::query()
            ->where('prospect', 0)
            ->with(['agence'])
            ->orderBy('name');

        $q = trim((string) $request->query('q', ''));
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%'.$q.'%')
                    ->orWhere('niu', 'like', '%'.$q.'%')
                    ->orWhere('rccm', 'like', '%'.$q.'%');
            });
        }

        $items = $query->paginate(25)->withQueryString();

        return view('Juridique.Companies.index', compact('items', 'q'));
    }

    /**
     * Fiche entreprise (lecture seule).
     */
    public function show(string $token)
    {
        $item = Entreprise::where('token', $token)->where('prospect', 0)->firstOrFail();
        $item->load(['agence', 'region', 'arrondissement']);

        return view('Juridique.Companies.show', compact('item'));
    }
}
