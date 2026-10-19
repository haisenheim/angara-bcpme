<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DelegationPouvoir;
use App\Models\Role;
use App\Services\Inertia\Admin\CrudKit as K;
use App\Support\Inertia\DataTable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;

class DelegationPouvoirController extends Controller
{
    public function index()
    {
        $items = DelegationPouvoir::query()->with('profil')->orderBy('seuil_engagements_max')->get();

        $table = DataTable::make(K::singlePage($items))
            ->columns([
                ['key' => 'seuil', 'label' => 'Seuil max (engagements sollicités, XAF)', 'align' => 'right'],
                ['key' => 'profil', 'label' => 'Profil habilité'],
            ])
            ->row(fn (DelegationPouvoir $row) => [
                'cells' => [
                    'seuil' => number_format((float) $row->seuil_engagements_max, 0, ',', ' '),
                    'profil' => $row->profil?->name ?? ('#'.$row->profil_id),
                ],
                'actions' => [
                    K::link('edit', 'Modifier', route('admin.delegation-pouvoirs.edit', $row), 'edit'),
                    K::destroy(route('admin.delegation-pouvoirs.destroy', $row), 'Supprimer cette règle ?'),
                ],
            ])
            ->empty('Aucune règle — seuls le DG et le DGA peuvent valider ou rejeter la clôture des dossiers d’instruction.');

        return Inertia::render('Admin/Crud', [
            'title' => 'Délégation de pouvoir — clôture dossier d’instruction',
            'notice' => 'Pour chaque seuil (total des engagements sollicités sur le dossier, en XAF), le profil indiqué est seul habilité à valider ou rejeter la clôture. '
                .'Les règles sont triées par seuil croissant : la première dont le total du dossier est ≤ au seuil s’applique. '
                .'Au-delà de tous les seuils, ou si aucune règle n’est définie, seuls le DG et le DGA peuvent clôturer.',
            'countLabel' => 'règle(s)',
            'table' => $table->toArray(),
            'actions' => [
                K::link('create', 'Ajouter une règle', route('admin.delegation-pouvoirs.create'), 'add', 'primary'),
            ],
            'breadcrumb' => [
                ['label' => 'Angara', 'href' => route('admin.dashboard')],
                ['label' => 'Délégation de pouvoir'],
            ],
        ]);
    }

    public function create()
    {
        $profils = Role::on('central_app_mysql')->orderBy('name')->get();

        return $this->renderForm($profils);
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

        return $this->renderForm($profils, $item);
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

    private function renderForm($profils, ?DelegationPouvoir $item = null): \Inertia\Response
    {
        $old = fn (string $k, $default) => old($k, $default);

        return Inertia::render('Admin/Form', [
            'title' => $item ? 'Modifier la règle' : 'Nouvelle règle',
            'subtitle' => $item ? null : 'Exemple : seuil 10 000 000 → Chef d’agence ; seuil 100 000 000 → Responsable engagements.',
            'form' => [
                'narrow' => true,
                'fields' => [
                    K::field('seuil_engagements_max', 'Seuil maximal (XAF)', 'number', ['required' => true, 'min' => 0, 'step' => 1,
                        'value' => (string) $old('seuil_engagements_max', $item ? (float) $item->seuil_engagements_max : '')]),
                    K::field('profil_id', 'Profil habilité', 'select', ['required' => true,
                        'value' => (string) $old('profil_id', $item?->profil_id ?? ''),
                        'placeholder' => '— Choisir —',
                        'options' => K::options($profils->mapWithKeys(fn ($p) => [$p->id => $p->name.' ('.$p->abb.')']))]),
                ],
                'submit' => $item
                    ? ['url' => route('admin.delegation-pouvoirs.update', $item), 'method' => 'put', 'label' => 'Mettre à jour']
                    : ['url' => route('admin.delegation-pouvoirs.store'), 'method' => 'post', 'label' => 'Enregistrer'],
                'cancel' => ['href' => route('admin.delegation-pouvoirs.index'), 'label' => 'Annuler'],
            ],
            'breadcrumb' => [
                ['label' => 'Angara', 'href' => route('admin.dashboard')],
                ['label' => 'Délégation de pouvoir', 'href' => route('admin.delegation-pouvoirs.index')],
                ['label' => $item ? 'Modifier' : 'Nouvelle règle'],
            ],
        ]);
    }

    public function destroy(DelegationPouvoir $delegation_pouvoir)
    {
        $delegation_pouvoir->delete();
        Session::flash('success', 'Règle supprimée.');

        return redirect()->route('admin.delegation-pouvoirs.index');
    }
}
