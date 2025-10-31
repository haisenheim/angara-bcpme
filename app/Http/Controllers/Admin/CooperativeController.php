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
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'address' => 'required|string',
            'arrondissement_id' => 'required|exists:arrondissements,id',
            'domaine_id' => 'required|exists:domaines,id',
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // Create Entreprise record first
        $ar = Arrondissement::find($request->arrondissement_id);
        $ent = Entreprise::create([
            'name' => $request->name,
            'token' => sha1(time() . rand(0, 99)),
            'departement_id' => $ar->departement_id,
            'region_id' => $ar->departement->region_id,
            'user_id' => auth()->user()->id,
            'taille' => 'COOPERATIVE',
            'agence_id' => auth()->user()->agence_id ?? null,
            'representation_id' => auth()->user()->representation_id ?? null,
        ]);

        // Create Cooperative record
        $coop = new Cooperative();
        $coop->name = $request->name;
        $coop->phone = $request->phone;
        $coop->token = sha1(time() . rand(0, 99));
        $coop->address = $request->address;
        $coop->region_id = $ar->departement->region_id;
        $coop->departement_id = $ar->departement_id;
        $coop->arrondissement_id = $ar->id;
        $coop->entreprise_id = $ent->id;
        $coop->domaine_id = $request->domaine_id;
        $coop->secteur_id = $request->secteur_id ?? null;
        $coop->agence_id = auth()->user()->agence_id ?? null;
        $coop->user_id = auth()->user()->id;
        $coop->representation_id = auth()->user()->representation_id ?? null;

        // Handle photo upload if present
        $photo = request()->photo;
        if ($photo) {
            $coop->photo_uri = $this->entityImgCreate($photo, 'cooperatives', $coop->token);
        }

        $coop->save();

        // Create Tenant record
        $tenant = new Tenant();
        $tenant->id = $coop->token;
        $tenant->data = json_encode([
            'cooperative_id' => $coop->id,
            'name' => $coop->name,
        ]);
        $tenant->user_id = auth()->user()->id;
        $tenant->save();

        // Create cooperative admin user
        $user = new User();
        $user->role_id = 21; // Cooperative admin role
        $user->name = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->token = sha1(time() . rand(0, 99));
        $user->cooperative_id = $coop->id;
        $user->save();

        Session::flash('success', 'Coopérative créée avec succès!');
        return redirect()->route('admin.cooperatives.show', $coop->token);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function show($token)
    {
        $item = Cooperative::where('token', $token)->firstOrFail();
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
        $item = Cooperative::where('token', $token)->firstOrFail();
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
        $coop = Cooperative::where('token', $token)->firstOrFail();

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
        $coop = Cooperative::where('token', $token)->firstOrFail();

        // Delete associated tenant
        Tenant::where('id', $coop->token)->delete();

        // Delete cooperative
        $coop->delete();

        Session::flash('success', 'Coopérative supprimée avec succès!');
        return redirect()->route('admin.cooperatives.index');
    }

    /**
     * Get cooperative statistics
     */
    public function getStats($token)
    {
        $coop = Cooperative::where('token', $token)->firstOrFail();

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

