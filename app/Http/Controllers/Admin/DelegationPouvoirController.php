<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DelegationPouvoir;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DelegationPouvoirController extends Controller
{
    public function index()
    {
        $items = DelegationPouvoir::query()->with('profil')->orderBy('seuil_engagements_max')->get();

        return view('Admin.DelegationPouvoir.index', compact('items'));
    }

    public function create()
    {
        $profils = Role::on('central_app_mysql')->orderBy('name')->get();

        return view('Admin.DelegationPouvoir.create', compact('profils'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'seuil_engagements_max' => 'required|numeric|min:0',
            'profil_id' => 'required|integer',
        ]);
        if (! Role::on('central_app_mysql')->whereKey($data['profil_id'])->exists()) {
            return redirect()->back()->withInput()->withErrors(['profil_id' => 'Profil inconnu.']);
        }

        DelegationPouvoir::query()->create([
            'seuil_engagements_max' => $data['seuil_engagements_max'],
            'profil_id' => (int) $data['profil_id'],
        ]);

        Session::flash('success', 'Règle de délégation enregistrée.');

        return redirect()->route('admin.delegation-pouvoirs.index');
    }

    public function edit(DelegationPouvoir $delegation_pouvoir)
    {
        $item = $delegation_pouvoir;
        $profils = Role::on('central_app_mysql')->orderBy('name')->get();

        return view('Admin.DelegationPouvoir.edit', compact('item', 'profils'));
    }

    public function update(Request $request, DelegationPouvoir $delegation_pouvoir)
    {
        $item = $delegation_pouvoir;

        $data = $request->validate([
            'seuil_engagements_max' => 'required|numeric|min:0',
            'profil_id' => 'required|integer',
        ]);
        if (! Role::on('central_app_mysql')->whereKey($data['profil_id'])->exists()) {
            return redirect()->back()->withInput()->withErrors(['profil_id' => 'Profil inconnu.']);
        }

        $item->update([
            'seuil_engagements_max' => $data['seuil_engagements_max'],
            'profil_id' => (int) $data['profil_id'],
        ]);

        Session::flash('success', 'Règle mise à jour.');

        return redirect()->route('admin.delegation-pouvoirs.index');
    }

    public function destroy(DelegationPouvoir $delegation_pouvoir)
    {
        $delegation_pouvoir->delete();
        Session::flash('success', 'Règle supprimée.');

        return redirect()->route('admin.delegation-pouvoirs.index');
    }
}
