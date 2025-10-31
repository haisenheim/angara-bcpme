<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ExtendedController;
use App\Http\Resources\CooperativeListResource;
use App\Models\Arrondissement;
use App\Models\Banque;
use App\Models\Domaine;
use App\Models\Entreprise;
use App\Models\Secteur;
use App\Models\Structuration\Cooperative;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CooperativeController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Admin/Cooperatives/index');
    }

    /**
     * Fetch all cooperatives for DataTable
     */
    public function fetchAll()
    {
        $items = Tenant::all();
        $items = CooperativeListResource::collection($items);
        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $domaines = Domaine::all();
        $secteurs = Secteur::all();
        $arrondissements = Arrondissement::all();
        return view('Admin/Cooperatives/create', compact('domaines', 'secteurs', 'arrondissements'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function show($token)
    {
        $item = Tenant::where('token', $token)->firstOrFail();
        $item->load(['domaine', 'region', 'departement', 'arrondissement', 'entreprise', 'wallets', 'caisses', 'entrepots', 'exploitants']);

        return view('Admin/Cooperatives/show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function edit($token)
    {
        $item = Tenant::where('token', $token)->firstOrFail();
        $domaines = Domaine::all();
        $secteurs = Secteur::all();
        $arrondissements = Arrondissement::all();

        return view('Admin/Cooperatives/edit', compact('item', 'domaines', 'secteurs', 'arrondissements'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $token)
    {
        $coop = Tenant::where('token', $token)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'address' => 'required|string',
            'arrondissement_id' => 'required|exists:arrondissements,id',
            'domaine_id' => 'required|exists:domaines,id',
        ]);

        $ar = Arrondissement::find($request->arrondissement_id);

        $coop->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'region_id' => $ar->departement->region_id,
            'departement_id' => $ar->departement_id,
            'arrondissement_id' => $ar->id,
            'domaine_id' => $request->domaine_id,
            'secteur_id' => $request->secteur_id ?? null,
        ]);

        // Handle photo upload if present
        $photo = request()->photo;
        if ($photo) {
            $coop->photo_uri = $this->entityImgCreate($photo, 'cooperatives', $coop->token);
            $coop->save();
        }

        Session::flash('success', 'Coopérative mise à jour avec succès!');
        return redirect()->route('admin.cooperatives.show', $coop->token);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function destroy($token)
    {
    }

    /**
     * Get cooperative statistics
     */
    public function getStats($token)
    {
        $coop = Tenant::where('token', $token)->firstOrFail();

        $stats = [
            'total_membres' => $coop->exploitants()->count(),
            'total_entrepots' => $coop->entrepots()->count(),
            'total_caisses' => $coop->caisses()->count(),
            'total_wallets' => $coop->wallets()->count(),
            'stock_total' => $coop->stock ?? 0,
        ];

        return response()->json($stats);
    }
}

