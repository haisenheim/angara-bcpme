<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Agence;
use App\Models\OrganisationEntite;
use App\Models\Role;
use App\Models\User;
use App\Services\Inertia\Admin\CrudKit as K;
use App\Support\Inertia\DataTable;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureUserTokens();
        $filters = [
            'q' => trim((string) $request->query('q', '')),
            'role_id' => (string) $request->query('role_id', ''),
            'active' => (string) $request->query('active', ''),
        ];

        $items = $this->getUsersQuery()
            ->when($filters['q'] !== '', function (Builder $query) use ($filters) {
                $query->where(function (Builder $userQuery) use ($filters) {
                    $userQuery
                        ->where('name', 'like', '%' . $filters['q'] . '%')
                        ->orWhere('email', 'like', '%' . $filters['q'] . '%')
                        ->orWhere('phone', 'like', '%' . $filters['q'] . '%');
                });
            })
            ->when($filters['role_id'] !== '', fn (Builder $query) => $query->where('role_id', (int) $filters['role_id']))
            ->when($filters['active'] !== '', fn (Builder $query) => $query->where('active', (int) $filters['active']))
            ->paginate(15);

        $stats = [
            'total' => User::where('role_id', '>', 1)->count(),
            'active' => User::where('role_id', '>', 1)->where('active', 1)->count(),
            'locked' => User::where('role_id', '>', 1)->where('active', 0)->count(),
        ];

        $ref = $this->getReferenceData();
        $items->appends($request->query());

        $table = DataTable::make($items, $request)
            ->columns([
                ['key' => 'user', 'label' => 'Utilisateur'],
                ['key' => 'role', 'label' => 'Rôle'],
                ['key' => 'affectation', 'label' => 'Affectation'],
                ['key' => 'status', 'label' => 'Statut'],
            ])
            ->row(fn (User $item) => [
                'href' => route('admin.users.show', $item->token),
                'cells' => [
                    'user' => ['title' => $item->name, 'subtitle' => $item->email, 'meta' => $item->phone ?: '-'],
                    'role' => $item->role?->name ?? '-',
                    'affectation' => self::affectationCell($item),
                    'status' => K::statusBadge($item->status),
                ],
                'actions' => [
                    K::link('show', 'Voir', route('admin.users.show', $item->token)),
                    K::link('edit', 'Modifier', route('admin.users.edit', $item->token), 'edit'),
                    K::toggle((bool) $item->active, route('admin.user.enable', $item->token), route('admin.user.disable', $item->token)),
                ],
            ])
            ->search('Nom, email ou telephone')
            ->select('role_id', 'Rôle', $ref['roles']->pluck('name', 'id'), 'Tous les rôles')
            ->select('active', 'Statut', ['1' => 'Actif', '0' => 'Verrouillé'], 'Tous')
            ->empty('Aucun compte utilisateur ne correspond aux critères courants.');

        return Inertia::render('Admin/Crud', [
            'title' => 'Comptes utilisateurs',
            'subtitle' => "Pilotage, filtrage et activation des comptes de l'espace admin.",
            'countLabel' => 'compte(s)',
            'stats' => [
                K::stat('Total', $stats['total']),
                K::stat('Actifs', $stats['active'], 'success'),
                K::stat('Verrouillés', $stats['locked'], 'danger'),
            ],
            'table' => $table->toArray(),
            'actions' => [K::link('create', 'Nouveau compte', route('admin.users.create'), 'person_add', 'primary')],
            'breadcrumb' => [
                ['label' => 'Angara', 'href' => route('admin.dashboard')],
                ['label' => 'Utilisateurs'],
            ],
        ]);
    }

    public function getRoles()
    {
        $items = Role::where('metier', 1)->get();

        return view('/Admin/Users/roles')->with(compact('items'));
    }

    public function create()
    {
        return $this->renderForm(new User([
            'active' => 1,
            'agence_id' => 0,
        ]));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $this->normalizePayload($request->validated());
        $data['token'] = sha1(Str::uuid()->toString());
        $data['active'] = $request->boolean('active', true);

        $user = new User($data);
        $this->resetLegacyAssignments($user);
        $user->save();

        Session::flash('success', 'Compte utilisateur cree avec succes.');

        return redirect()->route('admin.users.index');
    }

    public function show($token)
    {
        $item = $this->findUserByToken($token);

        $isEntite = ($item->organisation_type ?? null) === 'entite';

        return Inertia::render('Admin/Fiche', [
            'title' => $item->name,
            'subtitle' => 'Consultation détaillée du compte utilisateur.',
            'badge' => K::statusBadge($item->status),
            'sections' => [
                [
                    'title' => 'Compte',
                    'span' => 1,
                    'items' => [
                        ['label' => 'Nom', 'value' => $item->name],
                        ['label' => 'Email', 'value' => $item->email],
                        ['label' => 'Téléphone', 'value' => $item->phone ?: '-'],
                        ['label' => 'Rôle', 'value' => $item->role?->name ?? '-'],
                        ['label' => 'Statut', 'badge' => K::statusBadge($item->status)],
                    ],
                ],
                [
                    'title' => 'Affectations',
                    'span' => 2,
                    'items' => $isEntite
                        ? [
                            ['label' => 'Entité', 'value' => $item->organisationEntite?->name ?? '-'],
                            ['label' => 'Type', 'value' => $item->organisationEntite?->type ?? '-'],
                        ]
                        : [
                            ['label' => 'Agence', 'value' => $item->agence?->name ?? '-'],
                            ['label' => 'Direction', 'value' => $item->agence?->representation?->name ?? '-'],
                        ],
                ],
            ],
            'actions' => [
                K::link('edit', 'Modifier', route('admin.users.edit', $item->token), 'edit'),
                K::toggle((bool) $item->active, route('admin.user.enable', $item->token), route('admin.user.disable', $item->token)),
            ],
            'breadcrumb' => [
                ['label' => 'Angara', 'href' => route('admin.dashboard')],
                ['label' => 'Utilisateurs', 'href' => route('admin.users.index')],
                ['label' => $item->name],
            ],
        ]);
    }

    public function edit($token)
    {
        $item = $this->findUserByToken($token);

        return $this->renderForm($item);
    }

    public function update(UpdateUserRequest $request, $token)
    {
        $item = $this->findUserByToken($token);
        $data = $this->normalizePayload($request->validated(), false);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $item->fill($data);
        $this->resetLegacyAssignments($item);
        $item->active = $request->boolean('active');
        $item->save();

        Session::flash('success', 'Compte utilisateur mis a jour avec succes.');

        return redirect()->route('admin.users.edit', $item->token);
    }

    public function enable($token)
    {
        $user = $this->findUserByToken($token);
        $user->active = 1;
        $user->save();

        Session::flash('success', 'Le compte utilisateur a ete active.');

        return back();
    }

    public function disable($token)
    {
        $user = $this->findUserByToken($token);
        $user->active = 0;
        $user->save();

        Session::flash('success', 'Le compte utilisateur a ete verrouille.');

        return back();
    }

    /**
     * Formulaire de création / modification (même contenu que l'ancien partial Admin/Users/_form).
     */
    protected function renderForm(User $item): \Inertia\Response
    {
        $isEdit = $item->exists;
        $ref = $this->getReferenceData();
        $organisationType = old('organisation_type', $item->organisation_type ?: ((int) ($item->agence_id ?? 0) > 0 ? 'agence' : 'entite'));
        $directionLabel = old('agence_id', $item->agence_id)
            ? ($item->agence?->representation?->name ?? "Direction determinee par l'agence selectionnee")
            : "La direction sera deduite de l'agence selectionnee";

        $fields = [
            K::field('name', 'Nom complet', 'text', ['required' => true, 'value' => (string) old('name', $item->name), 'width' => 'half']),
            K::field('phone', 'Téléphone', 'text', ['required' => true, 'value' => (string) old('phone', $item->phone), 'width' => 'third']),
            K::field('role_id', 'Rôle', 'select', ['required' => true, 'value' => (string) old('role_id', $item->role_id), 'width' => 'third',
                'placeholder' => 'Selectionner un role', 'options' => K::options($ref['roles']->pluck('name', 'id'))]),
            K::field('email', 'Email de connexion', 'email', ['required' => true, 'value' => (string) old('email', $item->email), 'width' => 'half']),
            K::field('password', $isEdit ? 'Nouveau mot de passe' : 'Mot de passe', 'password', ['required' => ! $isEdit, 'width' => 'third',
                'help' => $isEdit ? 'Laisser vide pour conserver le mot de passe actuel.' : null]),
            K::field('active', 'Compte actif', 'checkbox', ['value' => (int) (bool) old('active', $item->active ?? 1), 'width' => 'third']),
            ['type' => 'section', 'name' => '_affectation', 'label' => 'Affectation'],
            K::field('organisation_type', 'Type d’affectation', 'radio', ['value' => $organisationType,
                'options' => [['value' => 'agence', 'label' => 'Agence'], ['value' => 'entite', 'label' => 'Entité']]]),
            K::field('agence_id', 'Agence', 'select', ['value' => (string) old('agence_id', $item->agence_id ?? 0), 'width' => 'half', 'placeholder' => false,
                'options' => K::options($ref['agences']->pluck('name', 'id'), ['0' => 'Aucune']),
                'showIf' => ['field' => 'organisation_type', 'value' => 'agence']]),
            K::field('_direction', 'Direction', 'text', ['readonly' => true, 'value' => $directionLabel, 'width' => 'half',
                'showIf' => ['field' => 'organisation_type', 'value' => 'agence']]),
            K::field('organisation_entite_id', 'Entité', 'select', ['value' => (string) old('organisation_entite_id', $item->organisation_entite_id), 'width' => 'half',
                'placeholder' => 'Selectionner une entite', 'options' => K::options($ref['organisationEntites']->pluck('name', 'id')),
                'showIf' => ['field' => 'organisation_type', 'value' => 'entite']]),
        ];

        $breadcrumb = [['label' => 'Angara', 'href' => route('admin.dashboard')], ['label' => 'Utilisateurs', 'href' => route('admin.users.index')]];
        if ($isEdit) {
            $breadcrumb[] = ['label' => $item->name, 'href' => route('admin.users.show', $item->token)];
        }
        $breadcrumb[] = ['label' => $isEdit ? 'Modification' : 'Nouveau compte'];

        return Inertia::render('Admin/Form', [
            'title' => $isEdit ? 'Modifier '.$item->name : 'Nouveau compte utilisateur',
            'subtitle' => $isEdit
                ? 'Mise a jour des informations de compte, du role et des affectations.'
                : "Creation d'un profil utilisateur avec ses affectations metier.",
            'form' => [
                'fields' => $fields,
                'submit' => $isEdit
                    ? ['url' => route('admin.users.update', $item->token), 'method' => 'put', 'label' => 'Enregistrer les modifications']
                    : ['url' => route('admin.users.store'), 'method' => 'post', 'label' => 'Creer le compte'],
                'cancel' => ['href' => route('admin.users.index'), 'label' => 'Retour a la liste'],
            ],
            'actions' => $isEdit
                ? [
                    K::link('show', 'Voir la fiche', route('admin.users.show', $item->token)),
                    K::toggle((bool) $item->active, route('admin.user.enable', $item->token), route('admin.user.disable', $item->token)),
                ]
                : [K::link('back', 'Retour', route('admin.users.index'), 'arrow_back')],
            'breadcrumb' => $breadcrumb,
        ]);
    }

    protected static function affectationCell(User $item): array
    {
        if (($item->organisation_type ?? null) === 'entite') {
            return ['title' => 'Entite: '.($item->organisationEntite?->name ?? '-'), 'subtitle' => 'Type: '.($item->organisationEntite?->type ?? '-')];
        }

        return ['title' => 'Agence: '.($item->agence?->name ?? '-'), 'subtitle' => 'Direction: '.($item->agence?->representation?->name ?? '-')];
    }

    protected function getReferenceData(): array
    {
        return [
            'roles' => Role::where('metier', 1)->orderBy('name')->get(),
            'agences' => Agence::query()->orderBy('name')->get(),
            'organisationEntites' => OrganisationEntite::query()->where('active', true)->orderBy('type')->orderBy('name')->get(),
        ];
    }

    protected function getUsersQuery(): Builder
    {
        return User::query()
            ->where('role_id', '>', 1)
            ->with(['role', 'agence.representation', 'organisationEntite'])
            ->orderByDesc('id');
    }

    protected function findUserByToken(string $token): User
    {
        return $this->getUsersQuery()
            ->where('token', $token)
            ->firstOrFail();
    }

    protected function normalizePayload(array $validated, bool $withPassword = true): array
    {
        $organisationType = $validated['organisation_type'] ?? null;
        $agenceId = (int) ($validated['agence_id'] ?? 0);
        $entiteId = $validated['organisation_entite_id'] ?? null;
        $entiteId = filled($entiteId) ? (int) $entiteId : null;

        if ($organisationType === 'agence') {
            $entiteId = null;
        } elseif ($organisationType === 'entite') {
            $agenceId = 0;
        } else {
            // fallback compat: si agence_id présent => agence, sinon entité
            $organisationType = $agenceId > 0 ? 'agence' : 'entite';
            if ($organisationType === 'agence') {
                $entiteId = null;
            } else {
                $agenceId = 0;
            }
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role_id' => (int) $validated['role_id'],
            'agence_id' => $agenceId,
            'organisation_type' => $organisationType,
            'organisation_entite_id' => $entiteId,
        ];

        if ($withPassword || array_key_exists('password', $validated)) {
            $password = $validated['password'] ?? null;
            if (filled($password)) {
                $data['password'] = Hash::make($password);
            }
        }

        return $data;
    }

    protected function ensureUserTokens(): void
    {
        User::query()
            ->where('role_id', '>', 1)
            ->where(function (Builder $query) {
                $query->whereNull('token')->orWhere('token', '');
            })
            ->get()
            ->each(function (User $user) {
                $user->forceFill([
                    'token' => sha1(Str::uuid()->toString()),
                ])->saveQuietly();
            });
    }

    protected function resetLegacyAssignments(User $user): void
    {
        $user->representation_id = 0;
        $user->banque_id = 0;
        $user->secteur_id = 0;
    }
}
