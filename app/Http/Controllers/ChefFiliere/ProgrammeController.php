<?php

namespace App\Http\Controllers\ChefFiliere;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgrammeListResource;
use App\Models\Programme;
use Illuminate\Support\Facades\Session;

class ProgrammeController extends Controller
{
    public function index()
    {
        return view('ChefFiliere.Programmes.index');
    }

    public function fetchAll()
    {
        $items = Programme::all();
        $items = ProgrammeListResource::collection($items);

        return response()->json($items);
    }

    public function show(string $token)
    {
        $item = Programme::query()
            ->where('token', $token)
            ->with([
                'produits.filiere',
                'produits.branche',
                'appuis.type',
                'composantes',
                'resultats.indicateur',
                'entreprises',
            ])
            ->first();

        if (! $item) {
            Session::flash('error', 'Programme introuvable.');

            return redirect()->route('chef-filiere.programmes.index');
        }

        return view('ChefFiliere.Programmes.show', compact('item'));
    }
}
